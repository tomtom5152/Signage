<?php

class GroupController extends Controller 
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
				'actions'=>array('index','create','view','delete','useradd','userdelete'),
                                'users'=>array('@'),
				'expression'=>'User::model()->findByPk($user->id)->isAdmin==1',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Lists the groups in the database
         */
        public function actionIndex() {
                $dataProvider=new CActiveDataProvider('Group');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
        }
        
        /**
         * Create a new group
         */
        public function actionCreate() {
                $model=new Group;
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Group'])) {
                        $model->setAttributes($_POST['Group']);
                        if($model->save())
                                $this->redirect($model->idgroup);
                }
                
                $this->render('create',array(
                        'model'=>$model,
                ));
        }
        
        /**
         * View a specific group in an editable form with the options to edit
         * user group members
         * @param int $id
         */
        public function actionView($id) {
                $model = $this->loadModel($id);
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Group'])) {
                        $model->setAttributes($_POST['Group']);
                        $model->save();
                }
                
                $membership=new CActiveDataProvider('GroupMembership', array(
                        'criteria'=>array(
                            'condition'=>"idgroup=$id",
                            'with'=>array('iduser0'),
                        )
                ));
                
                $this->render('view',array(
                        'model'=>$model,
                        'membership'=>$membership,
                ));
        }
        
        /**
         * Deletes a group based on ID
         * @param int $id The ID of the group to be deleted
         */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('group/'));
	}
        
        /**
         * Add a user to a group using the form
         */
        public function actionUseradd()
        {
                if(isset($_POST['GroupMembership'])) {
                        $model=new GroupMembership;
                        $model->setAttributes($_POST['GroupMembership']);
                        $model->save();
                        $this->redirect($model->idgroup);
                }
                $this->redirect('group/');
        }
        
        /**
         * Remove a user from a group
         * @param int $id the ID of the GroupMembership to be deleted
         */
        public function actionUserdelete($id)
        {
                $model=GroupMembership::model()->findByPk($id);
                $idgroup=$model->idgroup;
                $model->delete();
                // if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
                        $this->redirect($idgroup);
        }
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Group the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Group::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /**
         * Validates the standard form for user details.
         * @param Group $model The user model to be validated
         */
        private function _performAjaxValidation($model)
        {
                if(isset($_POST['ajax']) && $_POST['ajax']==='group-form') {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }
        }
}

?>
