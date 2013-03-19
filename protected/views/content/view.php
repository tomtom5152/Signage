<?php
/**
 * @var ContentController $this
 * @var Content $model
 * @var CActiveForm $form
 */
?>

<?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'content-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="row">
                <?php switch ($model->content_type):
                        case "ticker":
                                echo $form->labelEx($model,'content');
                                echo $form->textArea($model,'content',array('class'=>'tickerText','rows'=>'3','maxheight'=>'140','style'=>'height: auto'));
                                echo $form->error($model,'content');
                                break;

                        case "img":
                                echo $form->label($model,'content');
                                echo $model->content;
                                break;

                        case "text":
                                echo $form->labelEx($model,'content');
                                echo $form->textArea($model,'content');
                                echo $form->error($model,'content');
                endswitch; ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'idfeed'); ?>
                <?php echo $form->dropDownList($model,'idfeed',
                        CHtml::listData(
                                Feed::model()->findAll(),
                                'idfeed',
                                'feedname'
                        )
                ); ?>
                <?php echo $form->error($model,'idfeed'); ?>
        </div>
        <hr />
        <div class="row">
                <?php echo $form->labelEx($model,'start'); ?>
                <?php echo $form->dateField($model,'start',array('class'=>'text')); ?>
                <?php echo $form->error($model,'start'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'end'); ?>
                <?php echo $form->dateField($model,'end',array('class'=>'text')); ?>
                <?php echo $form->error($model,'end'); ?>
        </div>
        <hr />
        <div class="row">
                <?php echo $form->labelEx($model,'duration'); ?>
                <?php echo $form->numberField($model,'duration',array('class'=>'text','min'=>'1',)); ?>
                <?php echo $form->error($model,'duration'); ?>
        </div>
        
        <?php echo CHtml::submitButton('Submit'); ?>

<?php $this->endWidget(); ?>