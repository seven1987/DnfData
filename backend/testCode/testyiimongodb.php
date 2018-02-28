<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('ROOT_PATH', dirname(dirname(__DIR__)) . '/');
define('COMMON_PATH', ROOT_PATH . '/common/');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);

$application = new yii\web\Application($config);

use common\models\MyTest;

public function test(){
    //插入10w张单:
    $count = 10000;
    //  $command = Bet::getDb()->createCommand();
    for ($i=0;$i<$count;$i++){
        $bet = new Bet();
        $bet->bet_id = (string)($i+100003);
        $bet->han_id = 8;
        $bet->user_id = "3";
        $bet->bettype = 0;
        $bet->betamount = 3000;
        $bet->part_id = 2;
        $bet->part_odds = 1700;
        $bet->status = 2;
        $bet->bettime = date("Y-m-d H:i:s",time());
        $bet->updatetime = date("Y-m-d H:i:s",time());
        $bet->save();
        // $command->addInsert($bet->getAttributes());
    }
    // $command->executeBatch("bet");
};

//$models = Handicap::find()->all();
//echo "wwwwwwww ".count($models);
$model = new MyTest();
$model->name = "colen";
$model->password = "colen2";
$model->save();



?>