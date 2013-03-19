
<?php
$form=$this->beginWidget('CActiveForm', array(
        'id'=>'content-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); ?>
<?php  $this->widget('zii.widgets.grid.CGridView',array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'id'=>'approved',
            'selectableRows'=>2,
            'headerTemplate'=>Yii::t('signage','content_approve').'{item}',
        ),
        array(
            'class'=>'CCheckBoxColumn',
            'id'=>'rejected',
            'selectableRows'=>2,
            'headerTemplate'=>Yii::t('signage','content_reject').'{item}',
        ),
        array(
            'name'=>'idcontent',            
            'header'=>Yii::t('signage','idcontent'),
        ),
        array(
            'name'=>'idfeed',
            'value'=>'Feed::model()->findByPk($data->idfeed)->feedname',
            'header'=>Yii::t('signage','content_feed'),
        ),
        array(
            'name'=>'content_type',
            'value'=>'Yii::t(\'signage\',"content_{$data->content_type}")',
            'header'=>Yii::t('signage','content_type'),
        ),
        array(
            'name'=>'content',
            'type'=>'raw',
            'value'=>'($data->content_type=="img")?\'<img src="\'.'.
                'Yii::app()->getAssetManager()->publish("protected/assets/content/img/{$data->content}")'.
                '.\'" />\':CHtml::encode($data->content)',
            'header'=>Yii::t('signage','content'),
        ),
        array(
            'name'=>'duration',
            'header'=>Yii::t('signage','content_duration'),
        ),
        array(
            'name'=>'start',
            'type'=>'date',
            'header'=>Yii::t('signage','content_start'),
        ),
        array(
            'name'=>'end',
            'type'=>'date',
            'header'=>Yii::t('signage','content_end'),
        ),
        array(
            'name'=>'approved',
            'header'=>Yii::t('signage','content_approved'),
        ),
        array(
            'name'=>'iduser',
            'value'=>'$data->iduser0->firstname." ".$data->iduser0->lastname',
            'header'=>Yii::t('signage','content_iduser'),
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
        ),
    )
)); ?>
<div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
</div>
<?php $this->endWidget(); ?>