<?php
/**
 * Display a summery for a single Feed object
 * @var ContentController $this
 * @var Feed $data
 * @var int $index Current row count
 * @var CListView $widget
 * @var array $types content_type
 */
$types = $this->types;
?>

<?php if(GroupMembership::model()->countByAttributes(array(
    'idgroup'=>$data->idgroup,
    'iduser'=>Yii::app()->user->id,
))): ?>
        <div class="feedContent">
                <h3><?php echo $data->feedname; ?></h3>
                <table>
                        <thead>
                                <th><?php echo Yii::t('signage','content_type'); ?></th>
                                <th><?php echo Yii::t('signage','content_count_active'); ?></th>
                                <th><?php echo Yii::t('signage','content_count_waiting'); ?></th>
                                <th><?php echo Yii::t('signage','content_count_moderate'); ?></th>
                                <th><?php echo Yii::t('signage','content_count_expired'); ?></th>
                                <th class="link"></th>
                        </thead>
                        <tbody>
                                <?php foreach($types as $key => $type): ?>
                                        <tr class="<?php echo $key % 2 ? 'even' : 'odd'; ?>">
                                                <td><?php echo Yii::t('signage',"content_$type"); ?></td>
                                                <td><?php echo Content::model()->count('`idfeed`=:idfeed AND `approved`=:mod AND `content_type`=:type AND `start`<=:date AND `end`>=:date',array(
                                                    ':idfeed'=>$data->idfeed,
                                                    'mod'=>true,
                                                    ':type'=>$type,
                                                    ':date'=>date('Y-m-d'),
                                                )); ?></td>
                                                <td><?php echo Content::model()->count('`idfeed`=:idfeed AND `approved`=:mod AND `content_type`=:type AND `start`>:date',array(
                                                    ':idfeed'=>$data->idfeed,
                                                    'mod'=>true,
                                                    ':type'=>$type,
                                                    ':date'=>date('Y-m-d'),
                                                )); ?></td>
                                                <td><?php echo Content::model()->count('`idfeed`=:idfeed AND `approved`=:mod AND `content_type`=:type AND `start`<=:date AND `end`>=:date',array(
                                                    ':idfeed'=>$data->idfeed,
                                                    'mod'=>false,
                                                    ':type'=>$type,
                                                    ':date'=>date('Y-m-d'),
                                                )); ?></td>
                                                <td><?php echo Content::model()->count('`idfeed`=:idfeed AND `content_type`=:type AND `end`<:date',array(
                                                    ':idfeed'=>$data->idfeed,
                                                    ':type'=>$type,
                                                    ':date'=>date('Y-m-d'),
                                                )); ?></td>
                                                <td>
                                                        <a href="<?php echo $this->createUrl("content/{$data->idfeed}", array('type'=>$type)); ?>"
                                                           class="button"><?php echo Yii::t('signage','browsecontent'); ?></a>
                                                </td>
                                        </tr>
                                <?php endforeach; ?>
                        </tbody>
                </table>
        </div>
<?php endif; ?>