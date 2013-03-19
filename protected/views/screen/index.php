<?php
/**
 * Displays a Grid View of the screens in the database
 * @var ScreenController $this
 * @var CActiveDataProvder $dataProvider
 */
$this->pageTitle=Yii::t('signage','screens');
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'idscreen',
        'screenname',
        'location',
        array(
            'name'=>Yii::t("signage","feeds"),
            'value'=>'ScreenFeed::model()->countbyAttributes(array("idscreen"=>$data->idscreen))',
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