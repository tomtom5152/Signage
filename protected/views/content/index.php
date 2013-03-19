<?php
/**
 * Summerise the content status
 * @var TicketController $this
 * @var int $currentCount
 * @var int $approvedCount
 * @var int $moderateCount
 */
?>

<h3>Welcome to <?php echo Yii::app()->name; ?> <?php echo User::model()->findByPk(Yii::app()->user->id)->firstname; ?></h3>
<div>
        <h4>Content Status</h4>
        
        <ul>
                <li>There <?php echo $currentCount==1 ? 'is' : 'are'; ?> currently <?php echo $currentCount; ?> active item<?php if($currentCount!=1) echo 's'; ?></li>
                <li>There <?php echo $approvedCount==1 ? 'is' : 'are'; ?> currently <?php echo $approvedCount; ?> item<?php if($approvedCount!=1) echo 's'; ?> waiting to be displayed</li>
                <li>There <?php echo $moderateCount==1 ? 'is' : 'are'; ?> currently <?php echo $moderateCount; ?> item<?php if($moderateCount!=1) echo 's'; ?> awaiting moderation.</li>
        </ul>
</div>