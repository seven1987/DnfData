<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);
// define("YII_ENV_DEV", true);

$config = [
    'id' => 'pfast-backend',
//     'homeUrl'=>'site/index',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'enableCsrfCookie' => true,
            'cookieValidationKey' => 'w3BnewAWmCrjijzkiLucYD5Ty1Ym_V9F',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
        ],

        'user'=>[
            'class'=>'yii\web\User',
            'identityClass' => 'backend\models\AdminUser',
            'enableAutoLogin' => true,
            'enableSession'=>true,
        ],
//       'log' => [
//          //  'traceLevel' => YII_DEBUG ? 3 : 0,
//           'traceLevel' => 0,
//           'targets' => [
//               [
//                   'class' => 'yii\log\FileTarget',
//                   'levels' => ['error', 'warning', 'info'],
//                   'categories' => ['application', 'backend*',],
//               ],
//           ],
//       ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
    'language'=>'zh-CN'
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
        'panels' => [
            'mongodb' => [
                'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                'db' => 'mongodb',
            ],
            'httpclient' =>\yii\httpclient\debug\HttpClientPanel::class,
            'queue' => \yii\debug\Panel::class,

        ]
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\GeneratorCommon',
                'templates' => [
//                     'adminlte' => '..\..\vendor\yiisoft\yii2-gii\generators\model\adminlte',
//                    'adminlte' => '../../vendor/yiisoft/yii2-gii/generators/model/adminlte',
                    'dmgame' => '../../backend/templates/model/dm',
                    'dm-mongo' => '../../backend/templates/model/dm-mongo',
                ]
            ],
            'crud' => [
                'class' => 'backend\templates\crud\Generator',
                'templates' => [
//                     'adminlte' => '..\..\vendor\yiisoft\yii2-gii\generators\crud\adminlte',
                    'adminlte' => '../../vendor/yiisoft/yii2-gii/generators/crud/adminlte',
                    'dmgame' => '../../backend/templates/crud/dm',
                    'dmgame-handicap' => '../../backend/templates/crud/dm-handicap',
                ]
            ],
        ]
    ];
}
/*if(YII_DEBUG && PHP_SAPI != 'cli'){
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['modules']['debug'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'panels' => [
            'mongodb' => [
                'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                'db' => 'db_dm_game',
            ],
            'httpclient' =>[
                'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
            ],
            'queue' => \yii\debug\Panel::class,

        ]
    ];
}*/

return $config;
