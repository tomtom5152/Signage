<?php
/**
 * Displays the new group creation form
 * @var GroupController $this
 * @var Group $model
 */
$this->pageTitle=Yii::t('signage','addgroup');
?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>