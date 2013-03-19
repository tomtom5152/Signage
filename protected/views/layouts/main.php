<?php 
/* @var $this Controller */
/* @var User $user Current user */

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(
	Yii::app()->clientScript->getCoreScriptUrl().
	'/jui/css/base/jquery-ui.css'
);

$assets=Yii::app()->getAssetManager()->publish('protected/assets', false, -1, YII_DEBUG);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo $assets; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo $assets; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $assets; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

        <title><?php echo CHtml::encode("{$this->pageTitle} | ".Yii::app()->name); ?></title>
</head>

<body>
        <div id="root">
                <aside>
                        <div id="logo">
                                <a href="<?php echo Yii::app()->baseUrl; ?>">
                                        <img src="<?php echo $assets; ?>/img/logo.svg" />
                                </a>
                        </div>
                        <div>
                                <?php if(Yii::app()->user->isGuest): ?>
                                        <a href="<?php echo $this->createUrl(Yii::app()->user->loginUrl[0]); ?>">Login</a>
                                <?php else: ?>
                                        <h3>Welcome</h3>
                                        <a href="<?php echo $this->createUrl('user/'.Yii::app()->user->id); ?>">
                                                <?php echo User::model()->findByPk(Yii::app()->user->id)->firstname . ' ' .
                                                        User::model()->findByPk(Yii::app()->user->id)->lastname; ?>
                                        </a>
                                        <a href="<?php echo $this->createUrl('user/logout'); ?>">Logout</a>
                                <?php endif; ?>
                        </div>
                </aside>
                
                <header>
                        <?php if(!Yii::app()->user->isGuest): ?>
                                <nav>
                                        <?php $this->widget('zii.widgets.CMenu',array(
                                                'items'=>array(
                                                        array('label'=>'Home', 'url'=>array('content/ ')),
                                                        array('label'=>'Add Content', 'url'=>array('content/add')),
                                                        array('label'=>'Browse Content', 'url'=>array('content/browse')),
                                                        array('label'=>'Moderate Content', 'url'=>array('content/moderate')),
                                                        array('label'=>'Browse Feeds', 'url'=>array('feed/ ')),
                                                        array('label'=>'Screens', 'url'=>array('screen/ ')),
                                                        array('label'=>'Users', 'url'=>array('user/ ')),
                                                        array('label'=>'Groups', 'url'=>array('group/ ')),
                                                ),
                                        )); ?>
                                </nav>
                        <?php else: ?>
                                <h2>If you have been given a username and password, you may login to the left.</h2>
                        <?php endif; ?>
                </header>
                
                <div id="content">
                        <h2><?php echo CHtml::encode($this->pageTitle); ?></h2>
                        <?php echo $content; ?>
                </div>
                <div id="root_footer"></div>
        <!--#root-->
        </div>

        <div>
                <div class="center">&copy; <?php echo date('Y'); ?> Coded Internet</div>
                <?php if(YII_DEBUG): ?>
                <div class="center">
                        Page generated in: <?php echo round(Yii::getLogger()->getExecutionTime(), 3); ?>s
                        Memory Usage: <?php echo Yii::getLogger()->getMemoryUsage(); ?>
                </div>
                <?php endif; ?>
        </div>

</body>
</html>
