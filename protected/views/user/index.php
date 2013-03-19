<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle=Yii::t('signage','users');
?>

<div class="right">
        <?php echo CHtml::link(Yii::t('signage','create'), $this->createUrl('user/create'), array('class'=>'button right')); ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'iduser',
        'username',
        'firstname',
        'lastname',
        'email',
        array(
            'name'=>'isAdmin',
            'cssClassExpression' => '$data["isAdmin"] == 1 ? "icon-yes" : "icon-no"',
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
