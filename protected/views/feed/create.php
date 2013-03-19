<?php
/* @var $this FeedController */
/* @var $model Feed */
$this->pageTitle=Yii::t('signage','addfeed');
?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>