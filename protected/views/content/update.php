<?php
/**
 * Update form
 * @var ContentController $this
 * @var Content $model
 */
?>

<div class="form">
        <?php
        $form=$this->beginWidget('CActiveForm', array(
                'id'=>'content-form',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
        ));
        ?>

                <?php
                switch($model->content_type) {
                        case 'ticker': 
                                $assets=Yii::app()->getAssetManager()->publish('protected/assets', false, -1, YII_DEBUG);
                                Yii::app()->clientScript->registerScriptFile($assets.'/scripts/jquery.maxlength.js');
                                ?>
                                <div class="row" id="ticker">
                                        <?php echo $form->labelEx($model,'content'); ?>
                                        <?php echo $form->textArea($model,'content',
                                                array(
                                                    'maxlength'=>'140',
                                                    'cols'=>'50',
                                                    'rows'=>'3',
                                                    'required'=>'required'
                                        )); ?>
                                        <div><span id="tickerRemaining"></span> <?php echo Yii::t('signage','ticker_remaining'); ?></div>
                                        <?php echo $form->error($model,'content'); ?>
                                        <script>
                                                $(document).ready(function() {
                                                        $('#ticker textarea').maxlength({
                                                                'feedback': '#tickerRemaining'
                                                        });
                                                });
                                        </script>
                                </div>
                                <?php
                                Yii::app()->clientScript->registerScript('tickerRemaining',
                                        "$('#ticker textarea').maxlength({
                                                'feedback': '#tickerRemaining'
                                        });", CClientScript::POS_READY);
                                break;
                        
                        case 'text':
                                ?>
                                <div class="row">
                                        <?php echo $form->labelEx($model,'content'); ?>
                                        <?php echo $form->textArea($model,'content',
                                                array(
                                                    'cols'=>'50',
                                                    'required'=>'required'
                                        )); ?>
                                        <?php echo $form->error($model,'content'); ?>
                                </div>
                                <?php
                                break;
                }
                ?>
        
                <?php $this->renderPartial('_options',array('model'=>$model,'form'=>$form)); ?>
        
        <?php $this->endWidget(); ?>
</div>