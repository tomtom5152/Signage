<?php
/* @var $this FeedController */
/* @var $model User */
$this->pageTitle = Yii::t('signage','editfeed',array('idfeed'=>$model->idfeed));

$this->renderPartial('_form',array('model'=>$model));
