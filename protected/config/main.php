<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Signage',
        'defaultController'=>'content',
        'language'=>'en_gb',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.*',
	),

	'modules'=>array(
//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>false,
//		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        'loginUrl'=>array('user/login'),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
                        'showScriptName'=>false,
                        'caseSensitive'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
                'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
                 */
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=',
			'emulatePrepare' => true,
			'username' => '',
			'password' => '',
			'charset' => 'utf8',
                        'tablePrefix'=>'signage_',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				///*
//				array(
//					'class'=>'CWebLogRoute',
//				),
				//*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@codedinternet.com',
            
                // PHPass configuration options
                'phpass'=>array(
                        'iteration_count_log2'=>8,
                        'portable_hashes'=>false,
                ),
                'imgFormats'=>'gif,jpeg,jpg,png,bmp,tiff',
            
                // Clock and Weather Options
                'clock'=>array(
                    'hour'=>'short',
                    'min'=>'short',
                    'interval'=>10,
                ),
                'weather'=>array(
                        'location'=>'EUR|UK|UK001|STAFFORD',
                        'metric'=>true,
                        'interval'=>60*15,
                ),
	),
);