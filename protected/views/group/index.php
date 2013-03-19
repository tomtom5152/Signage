<?php
/**
 * Displays a Grid View of the groups in the database
 * @var GroupController $this
 * @var CActiveDataProvder $dataProvider
 */
$this->pageTitle=Yii::t('signage','groups');
?>

<div class="right">
        <?php echo CHtml::link(Yii::t('signage','create'), $this->createUrl('group/create'), array('class'=>'button right')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'idgroup',
        'groupname',
        array(
            'name'=>Yii::t("signage","members"),
            'value'=>'GroupMembership::model()->countbyAttributes(array("idgroup"=>$data->idgroup))',
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