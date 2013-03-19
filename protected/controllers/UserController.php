<?php

class UserController extends Controller
{
        /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
        
        /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
                        array('allow', // allow all users to login/logout
                                'actions'=>array('login','logout'),
                                'users'=>array('*'),
                        ),
                        array('allow',
                                'actions'=>array('view'),
                                'users'=>array('@'),
                        ),
			array('allow', // allow admin user to perform these actions
				'actions'=>array('index','create','delete'),
                                'users'=>array('@'),
				'expression'=>'User::model()->findByPk($user->id)->isAdmin==1',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Lists all of the users using a CGridView
         */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
        
        /**
         * Displays the new user form.
         * Redirects to index on successful complition
         */
	public function actionCreate()
	{
		$model=new User;
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['User'])) {
                        $model->setAttributes($_POST['User']);
                        if($model->save()) {
                                $this->redirect(array('user/'));
                        }
                }
                
                $this->render('create',array('model'=>$model));
	}
        
        /**
         * Views a user based on id in an editable form.
         * @param int $id The ID of the user being viewed
         */
        public function actionView($id)
        {
                $model = $this->loadModel($id);
                
                if(!($model->iduser == Yii::app()->user->id ||
                        User::model()->findByPk(Yii::app()->user->id)->isAdmin))
                        throw new CHttpException(403,'You are not allowed to perform this action');
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['User'])) {
                        $model->setAttributes($_POST['User']);
                        if($model->save() && User::model()->findByPk(Yii::app()->user->id)->isAdmin) {
                                $this->redirect(array('user/'));
                        }
                }
                
                $model->password = null;
                $this->render('view', array('model'=>$model));
        }

        /**
         * Deletes a user based on ID
         * @param int $id The ID of the user to be deleted
         */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('user/'));
	}

        /**
         * Displays an instance of the LoginForm. Uses AJAX validation and
         * methods from the LoginForm object.
         */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

        /**
         * Logs any user out from the system
         */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /**
         * Validates the standard form for user details.
         * @param User $model The user model to be validated
         */
        private function _performAjaxValidation($model)
        {
                if(isset($_POST['ajax']) && $_POST['ajax']==='user-form') {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }
        }
}