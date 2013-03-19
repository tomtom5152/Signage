<?php
/**
 * Displays a Grid View of the groups in the database
 * @var GroupController $this
 * @var Group $model
 * @var CActiveDataProvder $membership
 */
$this->pageTitle = Yii::t('signage','editgroup',array('idgroup'=>$model->idgroup));
?>

<?php $this->renderPartial('_form',array('model'=>$model)); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$membership,
    'columns'=>array(
        'iduser',
        array(
            'name'=>Yii::t("signage","username"),
            'value'=>'$data->iduser0->username',
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
                    'url'=>'Yii::app()->createUrl("group/userdelete/{$data->idgroupmembership}")',
                )
            ),
        ),
    ),
));
?>

<?php
$form=$this->beginWidget('CActiveForm', array(
        'action'=>"/group/useradd",
	'id'=>'group-user-add',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
));

$groupMembership=  GroupMembership::model();
$groupMembership->idgroup = $model->idgroup;
echo $form->hiddenField($groupMembership,'idgroup');

$users = User::model()->findAll();
$memberships = $membership->getData();
$members = array();
$nonmembers = array();

foreach($memberships as $member) {
        $members[]=$member->iduser;
}

foreach($users as $user) {
        if(!in_array($user->iduser, $members))
                $nonmembers[$user->iduser]=$user->username;
}
?>

        <div class="row">
                <?php echo $form->dropDownList(GroupMembership::model(),
                        'iduser',
                        $nonmembers
                );
                ?>
        </div>

        <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('signage','adduser')); ?>
	</div>
<?php $this->endWidget(); ?>