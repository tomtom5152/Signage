<?php

class ScreenController extends Controller 
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
			array('allow', // allow admin feed to perform these actions
				'actions'=>array('index','create','view','delete','feedadd','feeddelete'),
                                'users'=>array('@'),
				'expression'=>'User::model()->findByPk($user->id)->isAdmin==1',
			),
			array('deny',  // deny all feeds
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Lists the screens in the database
         */
        public function actionIndex() {
                $dataProvider=new CActiveDataProvider('Screen');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
        }
        
        /**
         * View a specific screen in an editable form with the options to edit
         * feed screen members
         * @param int $id
         */
        public function actionView($id) {
                $model = $this->loadModel($id);
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Screen'])) {
                        $model->setAttributes($_POST['Screen']);
                        $model->save();
                }
                
                $membership=new CActiveDataProvider('ScreenFeed', array(
                        'criteria'=>array(
                            'condition'=>"idscreen=$id",
                            'with'=>array('idfeed0'),
                        )
                ));
                
                $this->render('view',array(
                        'model'=>$model,
                        'membership'=>$membership,
                ));
        }
        
        /**
         * Deletes a screen based on ID
         * @param int $id The ID of the screen to be deleted
         */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('screen/'));
	}
        
        /**
         * Add a feed to this screen
         */
        public function actionFeedadd()
        {
                if(isset($_POST['ScreenFeed'])) {
                        $model=new ScreenFeed;
                        $model->setAttributes($_POST['ScreenFeed']);
                        $model->save();
                        $this->redirect($model->idscreen);
                }
                $this->redirect('screen/');
        }
        
        /**
         * Remove a feed from this screen
         * @param int $id the ID of the ScreenFeed record to be deleted
         */
        public function actionFeeddelete($id)
        {
                $model=ScreenFeed::model()->findByPk($id);
                $idscreen=$model->idscreen;
                $model->delete();
                // if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']))
                        $this->redirect($idscreen);
        }
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Screen the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Screen::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /**
         * Validates the standard form for feed details.
         * @param Screen $model The feed model to be validated
         */
        private function _performAjaxValidation($model)
        {
                if(isset($_POST['ajax']) && $_POST['ajax']==='screen-form') {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }
        }
}

?>
