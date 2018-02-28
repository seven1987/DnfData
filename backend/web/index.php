<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('ROOT_PATH', dirname(dirname(__DIR__)) . '/');
define('COMMON_PATH', ROOT_PATH . 'common/');
define('LOG_TYPE_BACKEND','backend');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);


//var_dump(yii\web\Application::defaultRoute());
$application = new yii\web\Application($config);

/**
 * @var $debugModule \yii\debug\Module;
 */
$debugModule = $application->getModule('debug');
$debugModule->allowedIPs[] = '192.168.*';


//var_dump($application->defaultRoute);
$application->run();
