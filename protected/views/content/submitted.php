<?php
/* Messages to confirm the contents sumission and inform the user of the approval status
/* @var $this ContentController */
/* @var $model Content */

$this->pageTitle=Yii::t('signage','content_submitted');
?>

<p>
        <?php echo Yii::t('signage','content_submit_success',array(':user'=>User::model()->findByPk(Yii::app()->user->id)->firstname)); ?>
</p>
<p>
        <?php
        if(isset($imgs)) {
                echo Yii::t('signage','content_submit_count',array(':imgs'=>$imgs));
        }
        ?>
</p>
<p>
        <?php
        if($model->approved == true) {
                echo Yii::t('signage','content_submit_approved');
        } else {
                echo Yii::t('signage','content_submit_moderated');
        }
        ?>
</p>