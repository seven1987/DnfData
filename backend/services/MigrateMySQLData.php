<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2016/12/30
 * Time: 17:48
 */


namespace backend\services;

use common\models\Agent;
use common\models\Game22;
use common\models\Game;
use common\models\Game2;
use common\models\Handicap;
use common\models\AgentHandicapRight;
use common\models\AgentBetRule;
use common\models\HandicapOdds;
use common\models\HandicapOddsHis;
use common\models\HandicapBet;
use common\models\Bet;
use common\models\Matches;
use common\models\Race;
use common\models\Team;
use common\models\TeamMember;
use common\models\Play;
use common\models\User;
use common\models\IDGenerator;

use backend\models\AdminLog;
use backend\models\AdminMenu;
use backend\models\AdminModule;
use backend\models\AdminRight;
use backend\models\AdminRightUrl;
use backend\models\AdminRole;
use backend\models\AdminRoleRight;
use backend\models\AdminUser;
use backend\models\AdminUserRole;

class MigrateMySQLData {

    public function doMigrate(){
        //  echo date_format(time(),'');

        //dm_game:
//        $this->mm(Game::find(),"game");
//        $this->mm(Matches::find(),Matches::tableName());
//        $this->mm(Race::find(),Race::tableName());
//        $this->mm(Team::find(),Team::tableName());
//        $this->mm(TeamMember::find(),TeamMember::tableName());
//        $this->mm(Play::find(),Play::tableName());

        //dm_member
//        $this->mm(Agent::find(),Agent::tableName());
//        $this->mm(User::find(),User::tableName());
//        $this->mm(AgentBetRule::find(),AgentBetRule::tableName());
//        $this->mm(AgentHandicapRight::find(),AgentHandicapRight::tableName());
//        $this->mm(AgentBetRule::find(),AgentBetRule::tableName());

        //dm_data:
//        $this->mm(HandicapBet::find(),HandicapBet::tableName());
//        $this->mm(Handicap::find(),Handicap::tableName());
//        $this->mm(HandicapOdds::find(),HandicapOdds::tableName());
//        $this->mm(HandicapOddsHis::find(),HandicapOddsHis::tableName());
//        $this->mm(Bet::find(),Bet::tableName());

        //dm_admin:
        $this->mm(IDGenerator::find(),IDGenerator::tableName());
//        $this->mm(AdminLog::find(),AdminLog::tableName());
//       $this->mm(AdminMenu::find(),"admin_menu");
//       $this->mm(AdminModule::find(),"admin_module");
//       $this->mm(AdminRight::find(),"admin_right");
//       $this->mm(AdminRightUrl::find(),"admin_right_url");
//       $this->mm(AdminRole::find(),"admin_role");
//       $this->mm(AdminRoleRight::find(),"admin_role_right");
//       $this->mm(AdminUser::find(),"admin_user");
//       $this->mm(AdminUserRole::find(),"admin_user_role");
    }

    public function batchInsertMongoDB($count){
        for ($i=0;$i<$count;$i++){
            $game = new Game();
            $game->name = "game".$i;
            $game->status = 1;
            $game->cdesc = "game desc";
            $game->save();
        }
    }

    public function batchInsertMySQL($count){
        for ($i=0;$i<$count;$i++){
            $game = new Game22();
            $game->name = "game".$i;
            $game->status = 1;
            $game->cdesc = "game desc";
            $game->save();
        }
    }

    public function batchInsertMongoByMongo($count){
        $connection = new \MongoClient("mongodb://root:123456@192.168.10.200:27017/djgame");
        $db = $connection->djgame;
        $collection = $db->game;

        $t1 = microtime(true);

        //mongo client insert:0.845秒
        for ($i=0;$i<$count;$i++) {
            $doc = array("name" => 'gamename'.$i, "cdesc" => 'cdesc', "game_id" => $i, "status" => 1);
            $collection->insert($doc);
        }

        $t2 = microtime(true);
        echo '耗时'.round($t2-$t1,3).'秒';
    }

    public function batchInsertRedis($count){
        $redis = new \Redis();
        //连接数据库
        $redis->connect('127.0.0.1',6379);

        $doc = array("name" => 'gamename', "cdesc" => 'cdesc', "game_id" =>1, "status" => 1);
        for ($i=0;$i<$count;$i++) {
            $redis->set('library',json_encode($doc));
        }
    }

    public function batchInsertRedisModel($count){
        $redis = new \Redis();
        //连接数据库
        $redis->connect('127.0.0.1',6379);

        $doc = array("name" => 'gamename', "cdesc" => 'cdesc', "game_id" =>1, "status" => 1);
        for ($i=0;$i<$count;$i++) {
            $gg = new Game2();
            $gg->name = "game".$i;
            $gg->save();
        }
    }

    private function mm($record,$tableName){
        $path = "D:\www\dmgame\doc\\";
        $models = $record->all();
        $data = array();
        $schemas = array();
        foreach ($models as $model){
            $attris = $model->getAttributes();
//            foreach ($attris as $name=>$value){
//                if (strstr($name,$key)){
//                    $attris[$name]=(string)$value;
//                }
//            }
            array_push($data,$attris);
            $schemas = $model->attributes();
        }
        $fileName = $path.$tableName.".json";
        $handle = fopen($fileName,'w') or die('打开<b>'.$fileName.'</br>文件失败！！');
        fwrite($handle, json_encode($data));
        fclose($handle);

        $fileName = $path.$tableName."_schema.json";
        $handle = fopen($fileName,'w') or die('打开<b>'.$fileName.'</br>文件失败！！');
        fwrite($handle, json_encode($schemas));
        fclose($handle);
    }
}