<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle=Yii::t('signage','adduser');
?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>