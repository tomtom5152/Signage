<?php
/**
 * View all the content for a feed passed to it by the controller
 * @var ContentController $this
 * @var Content $model
 * @var CActiveDataProvider $feeds
 */
$this->pageTitle=Yii::t('signage','browsecontent');
?>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$feeds,
    'itemView'=>'_feed',
));