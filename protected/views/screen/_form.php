<?php
/* @var $this ScreenController */
/* @var $model Screen */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'screen-form',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'screenname'); ?>
		<?php echo $form->textField($model,'screenname'); ?>
		<?php echo $form->error($model,'screenname'); ?>
	</div>
        
        <div class="row">
                <?php echo $form->labelEx($model,'location'); ?>
                <?php echo $form->textField($model,'location'); ?>
                <?php echo $form->error($model,'location'); ?>
        </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->