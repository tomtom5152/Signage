<?php
// Universal form
/* @var $this FeedController */
/* @var $model Feed */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'feed-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); 
$group = Group::model();
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'feedname'); ?>
		<?php echo $form->textField($model,'feedname'); ?>
		<?php echo $form->error($model,'feedname'); ?>
	</div>

	<div class="row">
                <?php echo $form->labelEx($model,'idgroup'); ?>
                <label class="selector">
                        <?php echo $form->dropDownList($model,
                                'idgroup',
                                // Create a list from the appropriate model
                                CHtml::listData(
                                        Group::model()->findAll(),
                                        'idgroup',
                                        'groupname'
                        )); ?>
                </label>
                <?php echo $form->error($model,'idgroup'); ?>
        </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->