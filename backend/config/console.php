<?php
$params = require(__DIR__ . '/params.php');
$config = [
	'id' => 'backend-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'backend\commands',
	'components' => [
		'log' => [
			//  'traceLevel' => YII_DEBUG ? 3 : 0,
			'traceLevel' => 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning', 'info'],
					'logVars' => [],
				],
			],
		],

	],
	'params' => $params,
];
return $config;
