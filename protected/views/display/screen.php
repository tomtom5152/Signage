<?php
// Content display content
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$assets=Yii::app()->getAssetManager()->publish('protected/assets', false, -1, YII_DEBUG);
Yii::app()->clientScript->registerScriptFile($assets.'/scripts/jquery.aautoscroll.2.41.js');

Yii::app()->clientScript->registerCssFile($assets.'/css/display.css');
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div id="left">
            <div id="ticker">
                <div class="content" data-type="ticker"></div>
            </div>
            <div id="bar">
                <div id="img">
                    <div class="content" data-type="img"></div>
                </div>
            </div>
        </div>
        <div id="right">
            <div id="time">
                <div class="content" data-type="time"></div>
            </div>
            <div id="text">
                <div class="content" data-type="text"></div>
            </div>
            <div id="weather">
                <div class="content" data-type="weather"></div>
            </div>
        </div>
        <script>
            function loadContent($e) {
                $.ajax({
                    url: '/display/content/type/'+$e.data('type'),
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if($e.data('type') == 'time' || $e.data('type') == 'weather') {
                            $e.html(data.content);
                        } else {
                            $e.fadeOut(function() {
                                $e.html(data.content).fadeIn();
                                if($e.data('type') == 'text') {
                                    $e.css({top:0});
                                    if($e.height() > $e.parent().height()) {
                                        setTimeout(function() {
                                            $e.animate({
                                                top: - $e.height() + $e.parent().height()
                                            }, data.duration*1000-4);
                                        }, data.duration*1000 - (data.duration*1000-2));
                                    }
                                }
                            })
                        }
                        setTimeout(function(){loadContent($e)}, data.duration*1000);
                    },
                    error: function() {
		                    // retry in 5 seconds
		                    setTimeout(funtion(){loadContent($e)}, 5000)
                    }
                });
            }
            $(document).ready(function() {
                $('.content').each(function() {
                    loadContent($(this));
                });
            });
        </script>
    </body>
</html>