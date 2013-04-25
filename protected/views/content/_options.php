<?php
/**
 * Universal inputs accross all content types
 * @var Content $model
 * @var CActiveForm $form
 */
$required=(isset($bulk) && $bulk == true)?null:'required';
?>

<div class="row">
        <?php echo $form->labelEx($model,'duration'); ?>
        <?php echo $form->numberField($model,'duration',array('required'=>$required,'min'=>'1')); ?>
        <?php echo $form->error($model,'duration'); ?>
</div>

<div class="row">
        <?php echo $form->labelEx($model,'start'); ?>
        <?php echo $form->dateField($model,'start',array('required'=>$required)); ?>
        <?php echo $form->error($model,'start'); ?>
</div>

<div class="row">
        <?php echo $form->labelEx($model,'end'); ?>
        <?php echo $form->dateField($model,'end',array('required'=>$required)); ?>
        <?php echo $form->error($model,'end'); ?>
</div>

<div class="row">
		<?php echo $form->labelEx($model,'rank'); ?>
		<?php echo $form->numberField($model,'rank',array('required'=>$required,'min'=>'1','max'=>'5')); ?>
		<?php echo $form->error($model,'rank'); ?>
</div>

<div class="row">
        <?php echo $form->labelEx($model,'idfeed'); ?>
        <?php echo $form->dropDownList($model,
                'idfeed',
                // Create a list from the appropriate model
                CHtml::listData(
                        Feed::model()->findAll(),
                        'idfeed',
                        'feedname'
        ),array('required'=>'required',)); ?>
</div>

<div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
</div>