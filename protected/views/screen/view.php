<?php
/**
 * Displays a Grid View of the screens in the database
 * @var ScreenController $this
 * @var Screen $model
 * @var CActiveDataProvder $membership
 */
$this->pageTitle = Yii::t('signage','editscreen',array('idscreen'=>$model->idscreen));
?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$membership,
    'columns'=>array(
        'idfeed',
        array(
            'name'=>Yii::t("signage","feedname"),
            'value'=>'$data->idfeed0->feedname',
        ),
        array(
            'class'=>'CButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'visible'=>'false',
                ),
                'update'=>array(
                    'visible'=>'false',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("screen/feeddelete/{$data->idscreenfeed}")',
                )
            ),
        ),
    ),
));
?>

<?php
$form=$this->beginWidget('CActiveForm', array(
        'action'=>"/screen/feedadd",
	'id'=>'screen-feed-add',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
));

$screenFeed=  ScreenFeed::model();
$screenFeed->idscreen = $model->idscreen;
echo $form->hiddenField($screenFeed,'idscreen');

$feeds = Feed::model()->findAll();
$memberships = $membership->getData();
$members = array();
$nonmembers = array();

foreach($memberships as $member) {
        $members[]=$member->idfeed;
}

foreach($feeds as $feed) {
        if(!in_array($feed->idfeed, $members))
                $nonmembers[$feed->idfeed]=$feed->feedname;
}
?>

        <div class="row">
                <?php echo $form->dropDownList(ScreenFeed::model(),
                        'idfeed',
                        $nonmembers
                );
                ?>
        </div>

        <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('signage','addfeed')); ?>
	</div>
<?php $this->endWidget(); ?>