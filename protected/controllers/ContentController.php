<?php

class ContentController extends Controller
{
        public $types = array('ticker','img','text');
        
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
			array('allow',  // allow authenticated users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
                        array('allow',
                                // Allow authenticated options to perform most of the operations
                                'actions'=>array('view','add','browse','upload',
                                    'postimg','delete','update','moderate'),
                                'users'=>array('@'),
                        ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

        /**
         * Display a splash screen to unauthenticated users and a content status to authenticated users
         */
        public function actionIndex()
        {
                if(Yii::app()->user->isGuest) {
                        $this->render('guest');
                        return;
                }
                
                $model=Content::model();
                
                // Get some different counts
                $currentCount = $model->count('start<=CURDATE() AND end>=CURDATE() AND approved=1');
                $approvedCount = $model->count('start>CURDATE() AND end>=CURDATE() AND approved=1');
                $moderateCount = $model->count('end>=CURDATE() AND approved=0');
                
                // Show these counts for to the user
                $this->render('index', array(
                        'currentCount'=>$currentCount,
                        'approvedCount'=>$approvedCount,
                        'moderateCount'=>$moderateCount,
                ));
        }
        
        /**
         * Action to add new content to the system
         */
        public function actionAdd()
        {
                // Display an approved or moderated message if content submitted successfully
                if(Yii::app()->user->hasFlash('content.submitted')) {
                        $this->render('submitted', Yii::app()->user->getFlash('content.submitted'));
                        return;
                }
                
                $model = new Content;
                
                // Check if submitted
                if(isset($_POST['Content'])) {
                        $model->setAttributes($_POST['Content']);
                        
                        // If the content is a list of images, then we want to
                        // explode this and do more with it, but otherwise just
                        // carry on.
                        if(empty($_POST['Content']['imgs'])) {
                                // Identify this user
                                $model->iduser = Yii::app()->user->id;

                                // Alter the validation rules
                                $model->setScenario($model->content_type);

                                // If we have an image to upload, then we need
                                // deal with that. To ensure unique file names,
                                // the timestamp is added.
                                if($model->content_type == 'img') {
                                        $model->img=CUploadedFile::getInstance($model, 'img');
                                        $t = time();
                                        $model->content="{$model->iduser}-{$t}-{$model->img->name}";
                                }

                                $this->_performAjaxValidation($model);

                                if($model->save()) {
                                        if($model->content_type == 'img') {
                                                $model->img->saveAs(Yii::app()->basePath.'/assets/content/img/'.$model->content);
                                        }
                                        Yii::app()->user->setFlash('content.submitted', array(
                                            'model'=>$model,
                                        ));
                                        $this->refresh();
                                }
                        } else {
                                // A list of the images uploaded via AJAX will
                                // have been submitted, so this needs decoding
                                // and each one needs saving.
                                $imgs = json_decode($_POST['Content']['imgs']);
                                foreach($imgs as $img) {
                                        $model = new Content;
                                        $model->setAttributes($_POST['Content']);
                                        $model->iduser = Yii::app()->user->id;
                                        
                                        $model->content = $img->file_name;
                                        
                                        $model->save();
                                }
                                Yii::app()->user->setFlash('content.submitted', array(
                                    // As we are only checking for approval, all
                                    // the modules will have the the same value
                                    'model'=>$model,
                                    // So the user can check the uploaded images
                                    'imgs'=>count($imgs),
                                ));
                                $this->refresh();
                        }
                }
                
                $this->render('add',array('model'=>$model));
        }
        
        /**
         * Recieves the images via AJAX and saves them with the correct name.
         * Outputs the name of the saved image so that it can be resubmitted
         * at a later date with the rest of the details.
         */
        public function actionPostimg() {
                $model = new Content;
                $out = new stdClass();
                $model->setScenario('img');
                $model->img=CUploadedFile::getInstance($model, 'img');
                $model->validate('img');
                $t = time();
                $out->file_name=Yii::app()->user->id."-{$t}-{$model->img->name}";
                $model->img->saveAs(Yii::app()->basePath.'/assets/content/img/'.$out->file_name);
                echo json_encode($out);
                Yii::app()->end();
        }
        
        /**
         * Displays a summary of all of the content in the feeds for which this
         * user is a moderator.
         */
        public function actionBrowse()
        {
                $model=Content::model();
                $feeds=new CActiveDataProvider('Feed');
                
                $this->render('browse', array(
                    'model'=>$model,
                    'feeds'=>$feeds,
                ));
        }
        
        /**
         * Shows the current users moderation que.
         */
        public function actionModerate()
        {
                $model=Content::model();
                
                // Bulk approval/rejection
                $approved = array();
                if(isset($_POST['approved'])) {
                        foreach($_POST['approved'] as $idcontent) {
                                $this->loadModel($idcontent)->save();
                                $approved[]=$idcontent;
                        }
                }
                if(isset($_POST['rejected'])) {
                        foreach($_POST['rejected'] as $idcontent) {
                                if(!in_array($idcontent, $approved))
                                        $this->actionDelete($idcontent,false);
                        }
                }
                
                // Get the user object for this user
                $user = User::model()->findByPk(Yii::app()->user->id);
                
                $groupMemberships = $user->groupMemberships; // Users groups
                $idfeeds = array();
                foreach($groupMemberships as $groupMembership) {
                        // Feeds for this group
                        $feeds=$groupMembership->idgroup0->feeds;
                        foreach($feeds as $feed) {
                                // Add feed ID to a global array
                                $idfeeds[]=$feed->idfeed;
                        }
                }
                
                // Get the rawdata from the database using attributes
                $rawData = $model->findAllByAttributes(
                        array(
                            'idfeed'=>$idfeeds,
                            'approved'=>false,
                        ),
                        // required for date as >=
                        array(
                            'condition'=>'`end`>=:date',
                            'params'=>array(
                                ':date'=>date('Y-m-d'),
                            ),
                ));
                
                // Create a datprovider from the raw array
                $dataProvider = new CArrayDataProvider($rawData,array(
                    'keyField'=>'idcontent', // to prevent error looking for `id` as PK
                ));
                
                $this->render('moderate',array('model'=>$model,'dataProvider'=>$dataProvider));
        }
        
        /**
         * View all of the content for a feed, and limit to content type
         * @param int $id The ID of the feed to show content for
         * @param string $type The content typte to limit to
         * @throws CHttpException Show 404 if no feed is present
         */
        public function actionView($id, $type=null)
        {
                // Bulk updating of content
                if(isset($_POST['idcontent'])) {
                        $idcontents = $_POST['idcontent']; // checkbox array
                        foreach($idcontents as $idcontent) {
                                // each checked option
                                $model=$this->loadModel($idcontent);
                                foreach($_POST['Content'] as $att => $val) {
                                        if($val==null)
                                                unset($_POST['Content'][$att]);
                                }
                                // set attributes to the changed values
                                $model->setAttributes($_POST['Content']);
                                $model->save();
                        }
                }
                
                // Load the feed form ID
                $feed=Feed::model()->findByPk($id);
		if($feed===null)
                        // 404 if not fount
			throw new CHttpException(404,'The requested page does not exist.');
                
                $criteria = new CDbCriteria;
                // add feed filter
                $criteria->addCondition("idfeed={$feed->idfeed}");
                // add type if set
                if($type)
                        $criteria->addCondition("`content_type`='$type'");
                
                $dataProvider = new CActiveDataProvider('Content',array('criteria'=>$criteria));
                
                $this->render('feed',array(
                    'feed'=>$feed,
                    'dataProvider'=>$dataProvider,
                ));
        }
        
        /**
         * View and update a piece of content
         * @param int $id The id of the content to be viewed
         */
        public function actionUpdate($id) 
        {
                $model = $this->loadModel($id);
                $this->checkAccess($model);
                
                $this->_performAjaxValidation($model);
                
                if(isset($_POST['Content'])) {
                        $model->setAttributes($_POST['Content']);
                        if($model->save()) {
                                $this->redirect(array('update','id'=>$model->idcontent));
                        }
                }
                
                $this->render('update', array('model'=>$model));
        }
        
        /**
         * Deletes content based on ID
         * @param int $id The ID of the content to be deleted
         */
	public function actionDelete($id, $action=true)
	{
		$model = $this->loadModel($id);
                $this->checkAccess($model);
                
                if($model->content_type == 'img')
                        unlink(Yii::app()->basePath.'/assets/content/img/'.$model->content);
                
                $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
                // we should not redirect the browser
		if(!isset($_GET['ajax']) || $action)
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('content/browse'));
	}
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Content the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Content::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
        /**
	 * Performs the AJAX validation.
	 * @param Ticket $model the model to be validated
	 */
	protected function _performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        /**
         * Checks if the current user is allowed to view/update the current model
         * @param Content $model
         * @return ContentController $this
         * @throws CHttpException
         */
        public function checkAccess($model) {
                $iduser = Yii::app()->user->id;
                if(!($iduser == $model->iduser || 
                                // Is the user in a group allowed to moderate this feed
                                GroupMembership::model()->countByAttributes(array(
                                    'idgroup'=>$model->idfeed0->idgroup,
                                    'iduser'=>$iduser,
                                )) ||
                                User::model()->findByPk($iduser)->isAdmin
                                ))
                        throw new CHttpException(403,'You are not authorised to perform this action');
                return $this;
        }
}
?>
