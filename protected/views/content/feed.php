<?php
/**
 * Show a summary of the content with an update form to bulk update the content
 * @param CActiveForm $form
 */
?>

<?php
$form=$this->beginWidget('CActiveForm', array(
        'id'=>'content-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
)); ?>
<?php $this->widget('zii.widgets.grid.CGridView',array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'id'=>'idcontent',
            'selectableRows'=>2,
        ),
        'idcontent',
        array(
            'name'=>'content_type',
            'value'=>'Yii::t(\'signage\',"content_{$data->content_type}")',
        ),
        array(
            'name'=>'content',
            'type'=>'raw',
            'value'=>'($data->content_type=="img")?\'<img src="\'.'.
                'Yii::app()->getAssetManager()->publish("protected/assets/content/img/{$data->content}")'.
                '.\'" />\':CHtml::encode($data->content)',
        ),
        'duration',
        array(
            'name'=>'start',
            'type'=>'date',
        ),
        array(
            'name'=>'end',
            'type'=>'date',
        ),
        'approved',
        array(
            'name'=>'iduser',
            'value'=>'$data->iduser0->firstname." ".$data->iduser0->lastname',
        ),
		'rank',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
        ),
    )
)); ?>

<?php $this->renderPartial('_options',array('form'=>$form,'model'=>Content::model(),'bulk'=>true)); ?>
<?php $this->endWidget(); ?>