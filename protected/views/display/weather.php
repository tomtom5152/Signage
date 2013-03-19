<?php
/**
 * Template for the weather widget
 * @var $this DisplayController
 * @var $xml
 * @var $icons
 */

$icons=Yii::app()->getAssetManager()->publish('protected/assets/img/weather', false, -1, YII_DEBUG);
?>

<div class="container">
        <div class="local">
                <span class="city">
                        <?php echo (string)$xml->local->city; ?>
                </span>
                <span class="curr_text">
                        <?php echo (string)$xml->currentconditions->weathertext; ?>
                </span>
                <span class="curr_temp">
                        <?php echo (int)$xml->currentconditions->temperature.'&deg;'.$xml->units->temp; ?>
                </span>
                <span class="curr_real">
                        <?php echo (int)$xml->currentconditions->realfeel.'&deg;'.$xml->units->temp; ?>
                </span>
                <span class="curr_humid">
                        <?php echo (string)$xml->currentconditions->humidity; ?>
                </span>
                <div class="curr_icon">
                        <img src="<?php echo $icons.'/'.(int)$xml->currentconditions->weathericon.'.png'; ?>" />
                </div>
        </div>
</div>