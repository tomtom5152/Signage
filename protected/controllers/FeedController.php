<?php

class FeedController extends Controller
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
			array('allow', // allow admin user to perform these actions
				'actions'=>array('index','create','view','delete'),
                                'users'=>array('@'),
				'expression'=>'User::model()->findByPk($user->id)->isAdmin==1',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Lists all of the feeds using a CGridView
         */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Feed');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
        
        /**
         * Displays the new feed form.
         * Redirects to index on successful complition
         */
	public function actionCreate()
	{
		$model=new Feed;
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Feed'])) {
                        $model->setAttributes($_POST['Feed']);
                        if($model->save()) {
                                $this->redirect(array('feed/'));
                        }
                }
                
                $this->render('create',array('model'=>$model));
	}
        
        /**
         * Views a feed based on id in an editable form.
         * @param int $id The ID of the feed being viewed
         */
        public function actionView($id)
        {
                $model = $this->loadModel($id);
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Feed'])) {
                        $model->setAttributes($_POST['Feed']);
                        if($model->save()) {
                                $this->redirect(array('feed/'));
                        }
                }
                
                $this->render('view', array('model'=>$model));
        }

        /**
         * Deletes a feed based on ID
         * @param int $id The ID of the feed to be deleted
         */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('feed/'));
	}

        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Feed the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Feed::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /**
         * Validates the standard form for feed details.
         * @param Feed $model The feed model to be validated
         */
        private function _performAjaxValidation($model)
        {
                if(isset($_POST['ajax']) && $_POST['ajax']==='feed-form') {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }
        }
}