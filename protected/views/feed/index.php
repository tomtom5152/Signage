<?php
/* @var $this FeedController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle=Yii::t('signage','feeds');
?>

<div class="right">
        <?php echo CHtml::link(Yii::t('signage','create'), $this->createUrl('feed/create'), array('class'=>'button right')); ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'idfeed',
        'feedname',
        array(
            'name'=>Yii::t('signage','feedgroup'),
            'value'=>'$data->idgroup0->groupname',
        ),
        array(
            'class'=>'CButtonColumn',
            'buttons'=>array(
                'update'=>array(
                    'visible'=>'false',
                ),
            ),
        ),
    ),
));
