<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = Yii::t('signage','edituser',array('iduser'=>$model->iduser));

$this->renderPartial('_form',array('model'=>$model));
