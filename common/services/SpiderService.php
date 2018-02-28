<?php

namespace common\services;

use common\models\LolPlayerWorthRange;
use common\models\LolTeamStrengthRange;
use Yii;
use common\models\LolChampionUsage;
use common\models\LolChampionWin;
use common\utils\DataPackager;
use common\utils\SysCode;
use common\utils\CommonFun;
use common\models\LolMatchResult;
use common\models\LolMatchTeamRanking;
use common\models\LolMatchHistory;
use common\models\LolMatchSchedule;
use common\models\LolTeamRanking;
use common\models\LolPlayerRanking;
use common\models\LolMatchList;
use common\models\LolMatchDetail;
use common\models\Dota2MatchResult;
use common\models\Dota2MatchDetail;

ini_set("memory_limit", "1024M");
set_time_limit(1500);
require_once ROOT_PATH . 'common/utils/phpspider/core/init.php';

/**
 * 爬虫服务
 * Class SpiderService
 * @package common\models
 */
class SpiderService
{

    public static $content = [];
    const MAX_PAGE_REQUEST = 1000;

    /**
     * 执行电竞魔方数据抓取， 全部结束后发邮件通知测试
     */
    public static function doSpider()
    {

        static::$content=[];
        $startTime = date("Y-m-d H:i:s");
        SpiderService::spiderGetData();
        // for test
        $endTime = date("Y-m-d H:i:s");

        //执行完成邮件通知测试
        $emailList = [
//			'qinmu@sensefun.com',
            'linduoming@sensefun.com',
            'guisiqi@sensefun.com',
        ];
        $content = "电竞魔方数据爬取完成<br/>".implode(',',static::$content);
        $title = '电竞魔方数据爬取完成, 开始时间：' . $startTime . ", 结束时间：" . $endTime;
        foreach ($emailList as $email) {
            MailService::sendMail($email, $content, $title);
        }

        return $emailList;
    }

    public static function spiderGetData()
    {
        static::getLolChampionUsage();
        static::getLolChampionWin();
        static::getLolMatchResult();
        static::getLolMatchTeamRanking();
        static::getLolPlayerRanking();
        static::getLolMatchHistory();
        static::getLolMatchSchedule();
        static::getLolTeamRanking();
        static::getLolMatchList();
        static::getLolMatchDetail();
		static::getLolPlayerWorthRange();
		static::getLolTeamStrengthRange();
    }

    /**
     * 获取LOL英雄使用率
     */
    public static function getLolChampionUsage()
    {
        $lolUrl = [
            'http://lol.esportsmatrix.com/zh-CN/Home/ChampionWinUsage?month=3',
            'http://lol.esportsmatrix.com/zh-CN/Home/ChampionWinUsage?month=6'
        ];
        foreach ($lolUrl as $key => $value) {
            $month = substr($value, strpos($value, '=') + 1, 1);
            //模拟get请求
			static::log("[SpiderService] getLolChampionUsage, request url:" . $value);
			$jsonData = \requests::get($value);
            $result = json_decode($jsonData, true);
            if (empty($result) || json_last_error() || empty($result['ChampionUsage'])) {
                static::log("[SpiderService] getLolChampionUsage, no result url:" . $value);
                continue;
            }
            $championUsage = $result['ChampionUsage'];
            $arr = [];
            foreach ($championUsage as $k1 => $v1) {
                $arr[$k1] = $v1;
                $arr[$k1]['month'] = (int)$month;
            }
            static::$content[] = 'test1';
            if (!empty($arr)) {
                $lolChampionUsage = new LolChampionUsage();
                foreach ($arr as $k2 => $v2) {
					$v2 = static::formatJsonData($v2);
                    $lolChampionUsage->load($v2, '');
                    if (!empty($lolChampionUsage->getErrors())) {
                        static::$content[] = "[SpiderService] getChampionUsage " . $lolChampionUsage->getErrors();
                        unset($arr[$k2]);
                    }
                }
                $arr = array_merge($arr);
                LolChampionUsage::deleteAll(['month' => (int)$month]);
                LolChampionUsage::getDb()->createCommand()->batchInsert(LolChampionUsage::tableName(), $arr);

                static::log("[SpiderService] getChampionUsage $month month , done!");
            } else {
                static::log("[SpiderService] getChampionUsage $month month , no result url" . $value);
            }
        }
    }


    /**
     * 获取LOL英雄胜率
     */
    public static function getLolChampionWin()
    {
        $lolUrl = [
            'http://lol.esportsmatrix.com/zh-CN/Home/ChampionWinUsage?month=3',
            'http://lol.esportsmatrix.com/zh-CN/Home/ChampionWinUsage?month=6'
        ];
        foreach ($lolUrl as $key => $value) {
            $month = substr($value, strpos($value, '=') + 1, 1);
            //模拟get请求
			static::log("[SpiderService] getLolChampionWin, request url:" . $value);
			$jsonData = \requests::get($value);
            $result = json_decode($jsonData, true);
            if (empty($result) || json_last_error() || empty($result['ChampionWin'])) {
                static::log("[SpiderService] getLolChampionWin, no result url:" . $value);
                continue;
            }
            $championUsage = $result['ChampionWin'];
            $arr = [];
            foreach ($championUsage as $k1 => $v1) {
                $arr[$k1] = $v1;
                $arr[$k1]['month'] = (int)$month;
            }
            if (!empty($arr)) {
                $lolChampionWin = new LolChampionWin();
                foreach ($arr as $k2 => $v2) {
					$v2 = static::formatJsonData($v2);
                    $lolChampionWin->load($v2, '');
                    if (!empty($lolChampionWin->getErrors())) {
                        static::$content[] = "[SpiderService] getLolChampionWin " . $lolChampionWin->getErrors();
                        unset($arr[$k2]);
                    }
                }
                $arr = array_merge($arr);
                LolChampionWin::deleteAll(['month' => (int)$month]);
                LolChampionWin::getDb()->createCommand()->batchInsert(LolChampionWin::tableName(), $arr);

                static::log("[SpiderService] getLolChampionWin $month month , done!");
            } else {
                static::log("[SpiderService] getLolChampionWin $month month , no result url" . $value);
            }
        }
    }

    /**
     * 获取LOL比赛结果列表   数据中心-最新赛果
     */
    public static function getLolMatchResult()
    {
        set_time_limit(1800);
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Match/GetResults';
        $arr = [];
		static::log("[SpiderService] getLolMatchResult, request url:" . $lolUrl);
		$arr0 = \requests::get($lolUrl);
        if (!empty($arr0)) {
            $totalPage = json_decode($arr0, true)['Total'];
			//实际爬取页数
			$existsCount = LolMatchResult::find()->count();//系统已爬取总条数
			$existsPage = ceil($existsCount / 6);//计算已爬取的页数，按照电竞魔方6条一页
			//如果已爬取的跟实际页数相差5页以上， 则爬取全部; 否则只爬取最近的5页
			if(($totalPage - $existsPage) > 5)
			{
				$rangePage = $totalPage;
			}
			else
			{
				$rangePage = 5;
			}

            for ($i = 1; $i <= $rangePage; $i++) {
                $url = 'http://lol.esportsmatrix.com/zh-CN/Match/GetResults?pageIndex=' . $i;
				static::log("[SpiderService] getLolMatchResult, request url:" . $url);
				$result = \requests::get($url);
                $result = json_decode($result, true);
                if (empty($result) || json_last_error() || empty($result['Data'])) {
                    static::log("[SpiderService] getLolMatchResult, no result url:" . $url);
                    continue;
                }
                $datas = $result['Data'];
                if (!is_null($datas)) {
                    foreach ($datas as $key => $value) {
                        $arr[] = $value;
                    }
                }
            }
        }
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $lolMatchResult = LolMatchResult::findOne(['Id' => $value['Id']]);
                if (empty($lolMatchResult)) {
                    $lolMatchResult = new LolMatchResult();
                }
                $value['TeamA'] = json_encode($value['TeamA']);
                $value['TeamB'] = json_encode($value['TeamB']);

				//比赛时间
				$value['StartTime'] = (int)($value['StartTime'] ? substr($value['StartTime'],6,10) : 0);

                $lolMatchResult->load($value, '');
                $lolMatchResult->save();
                if (!empty($lolMatchResult->getErrors())) {
                    static::log("[SpiderService] GetLolResults no result url" . $lolMatchResult->getErrors());
                }
            }

            static::log("[SpiderService] getLolMatchResult , done!");

//            LolMatchResult::getDb()->createCommand()->batchInsert(LolMatchResult::tableName(), $arr);
        } else {
            static::log("[SpiderService] GetLolResults no result url: " . $lolUrl);
        }
    }

    /**
     * LOL全球赛事   查看更多显示赛事中各战队的排名，接口
     */

    public static function getLolMatchTeamRanking()
    {
        set_time_limit(300);
        $leagueAll = LolMatchList::find()->asArray()->all();
        $arr = [];
        foreach ($leagueAll as $key => $value) {
			$lolMatchTeamRanking = LolMatchTeamRanking::find()->where(['LeagueId' => (int)$value['LeagueId']])->one();
			if(!empty($lolMatchTeamRanking))
			{
				continue;
			}
            $lolUrl = "http://lol.esportsmatrix.com/zh-CN/Match/GetTeamsStandings?leagueId=" . $value['LeagueId'];
            $leagueId = (int)$value['LeagueId'];
            //模拟get请求
			static::log("[SpiderService] getLolMatchTeamRanking, request url:" . $lolUrl);
			$jsonData = \requests::get($lolUrl);
            $result = json_decode($jsonData, true);
            if (empty($result) || json_last_error() || empty($result['Data'])) {
                static::log("[SpiderService] getLolMatchTeamRanking, no result url:" . $lolUrl);
                continue;
            }
            $datas = $result['Data'];
            foreach ($datas as $k1 => $v1) {
                $v1['LeagueId'] = (int)$leagueId;
                $v1['ranking'] = (int)($k1 + 1);
                $arr[] = $v1;
            }
        }

        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $lolMatchTeamRanking = LolMatchTeamRanking::find()->where(['Id' => (int)$value['Id']])->andWhere(['leagueId' => (int)$value['LeagueId']])->one();
                if (empty($lolMatchTeamRanking)) {
                    $lolMatchTeamRanking = new LolMatchTeamRanking();
                }
                $lolMatchTeamRanking->load($value, '');
                $lolMatchTeamRanking->save();
                if (!empty($lolMatchTeamRanking->getErrors())) {
                    static::log("[SpiderService] getLolMatchTeamRanking no result url" . $lolMatchTeamRanking->getErrors());
                }
            }
            static::log("[SpiderService] getLolMatchTeamRanking , done!");
        } else {
            static::log("[SpiderService] GetTeamsStandings no new result url http://lol.esportsmatrix.com/zh-CN/Match/GetTeamsStandings");
        }

    }

    /**
     * LOL全球赛事   历史赛事
     */

    public static function getLolMatchHistory()
    {
        set_time_limit(1800);
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Match/GetChampionshipList?pageIndex=1';
        $arr = [];
		static::log("[SpiderService] getLolMatchHistory, request url:" . $lolUrl);
		$arr0 = \requests::get($lolUrl);
        if (!empty($arr0)) {
            $totalPage = json_decode($arr0, true)['Total'];

			//实际爬取页数
			$existsCount = LolMatchHistory::find()->count();//系统已爬取总条数
			$existsPage = ceil($existsCount / 8);//计算已爬取的页数，按照电竞魔方8条一页

			$rangePage = $totalPage - $existsPage + 1;//
			$rangePage = max($rangePage, 3);
			$rangePage = min($totalPage, $rangePage);

			//如果已爬取的跟实际页数相差5页以上， 则爬取全部; 否则只爬取最近的5页
			if(($totalPage - $existsPage) > 5)
			{
				$rangePage = $totalPage;
			}
			else
			{
				$rangePage = 5;
			}

            for ($i = 1; $i <= $rangePage; $i++) {
                $url = 'http://lol.esportsmatrix.com/zh-CN/Match/GetChampionshipList?pageIndex=' . $i;
				static::log("[SpiderService] getLolMatchHistory, request url:" . $url);
				$result = \requests::get($url);
                $result = json_decode($result, true);
                if (empty($result) || json_last_error() || empty($result['Data'])) {
                    static::log("[SpiderService] getLolMatchHistory, no result url:" . $url);
                    continue;
                }
                $datas = $result['Data'];
                if (!is_null($datas)) {
                    foreach ($datas as $key => $value) {
                        $arr[] = $value;
                    }
                }
            }
        }
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $lolMatchHistory = LolMatchHistory::findOne(['Name' => $value['Name']]);
                if (empty($lolMatchHistory)) {
                    $lolMatchHistory = new LolMatchHistory();
                }
                $lolMatchHistory->load($value, '');
                $lolMatchHistory->save();
                if (!empty($lolMatchHistory->getErrors())) {
                    static::log("[SpiderService] getLolMatchHistory no result url" . $lolMatchHistory->getErrors());
                }
            }
            static::log("[SpiderService] getLolMatchHistory , done!");
        } else {
            static::log("[SpiderService] GetChampionshipList no result url" . $lolUrl);
        }
    }

    /**
     * LOL数据中心-赛事进程
     */

    public static function getLolMatchSchedule()
    {
        set_time_limit(600);
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Match/GetSchedule?pageIndex=1';
        $arr = [];
		static::log("[SpiderService] getLolMatchSchedule, request url:" . $lolUrl);
		$arr0 = \requests::get($lolUrl);
        if (!empty($arr0)) {
            $totalPage = json_decode($arr0, true)['Total'];
			//实际爬取页数
			$existsCount = LolMatchSchedule::find()->count();//系统已爬取总条数
			$existsPage = ceil($existsCount / 6);//计算已爬取的页数，按照电竞魔方6条一页
			//如果已爬取的跟实际页数相差5页以上， 则爬取全部; 否则只爬取最近的5页
			if(($totalPage - $existsPage) > 5)
			{
				$rangePage = $totalPage;
			}
			else
			{
				$rangePage = 5;
			}


            for ($i = 1; $i <= $rangePage; $i++) {
                $url = 'http://lol.esportsmatrix.com/zh-CN/Match/GetSchedule?pageIndex=' . $i;
				static::log("[SpiderService] getLolMatchSchedule, request url:" . $url);
				$result = \requests::get($url);
                $result = json_decode($result, true);
                if (empty($result) || json_last_error() || empty($result['Data'])) {
                    static::log("[SpiderService] getLolMatchSchedule, no result url:" . $url);
                    continue;
                }
                $datas = $result['Data'];
                if (!is_null($datas)) {
                    foreach ($datas as $key => $value) {
                        $arr[] = $value;
                    }
                }
            }
        }
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $lolMatchSchedule = LolMatchSchedule::findOne(['MatchTickerId' => $value['MatchTickerId']]);
                if (empty($lolMatchSchedule)) {
                    $lolMatchSchedule = new LolMatchSchedule();
                }
                $value['TeamA'] = json_encode($value['TeamA']);
                $value['TeamB'] = json_encode($value['TeamB']);
				//比赛时间
				$value['StartTime'] = (int)($value['StartTime'] ? substr($value['StartTime'],6,10) : 0);

                $lolMatchSchedule->load($value, '');
                $lolMatchSchedule->save();
                if (!empty($lolMatchSchedule->getErrors())) {
                    static::log("[SpiderService] GetSchedule no result url" . $lolMatchSchedule->getErrors());
                }
            }

            //爬取成功后， 删除过时的(8小时以前)赛事进程
			LolMatchSchedule::deleteAll(['<','StartTime',time() - 60*60*8]);

            static::log("[SpiderService] getLolMatchSchedule , done!");
        } else {
            static::log("[SpiderService] GetSchedule no result url" . $lolUrl);
        }
    }

    /**
     * LOL数据中心-战队数据
     */
    public static function getLolTeamRanking()
    {
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Team/GetTeamRankingList?season=-1&teamName=';

        //模拟get请求
		static::log("[SpiderService] getLolTeamRanking, request url:" . $lolUrl);
		$jsonData = \requests::get($lolUrl);
        $result = json_decode($jsonData, true);
        if (empty($result) || json_last_error() || empty($result['Data'])) {
            static::log("[SpiderService] getLolTeamRanking, no result url:" . $lolUrl);
            return;
        }
        $datas = $result['Data'];
        $arr = [];
        foreach ($datas as $key => $value) {
            $value['team_name'] = isset($value['TeamInfo']['Name']) ? $value['TeamInfo']['Name'] : '';
            $arr[$key] = $value;
        }

        if (!empty($arr)) {
            $lolTeamRanking = new LolTeamRanking();
            foreach ($arr as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolTeamRanking->load($v2, '');
                if (!empty($lolTeamRanking->getErrors())) {
                    static::$content[] = "[SpiderService] getLolTeamRanking error: " . $lolTeamRanking->getErrors();
                    unset($arr[$k2]);
                }
            }
            $arr = array_merge($arr);
            LolTeamRanking::deleteAll();
            LolTeamRanking::getDb()->createCommand()->batchInsert(LolTeamRanking::tableName(), $arr);

            static::log("[SpiderService] getLolTeamRanking , done!");
        } else {
            static::log("[SpiderService] getLolTeamRanking no result url" . $lolUrl);
        }
    }

    /**
     * LOL数据中心-选手信息
     */
    public static function getLolPlayerRanking()
    {
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Player/GetPlayerList?roleId=-1&season=-1&playerName=';

        //模拟get请求
		static::log("[SpiderService] getLolPlayerRanking, request url:" . $lolUrl);
		$jsonData = \requests::get($lolUrl);
        $result = json_decode($jsonData, true);
        if (empty($result) || json_last_error() || empty($result['Data'])) {
            static::log("[SpiderService] getLolPlayerRanking, no result url:" . $lolUrl);
            return;
        }
        $datas = $result['Data'];
        $arr = [];
        foreach ($datas as $key => $value) {
            $value['player_name'] = isset($value['PlayerBasicInfo']['Name']) ? $value['PlayerBasicInfo']['Name'] : '';
            $value['Ranking'] = isset($value['PlayerRanking']['Ranking']) ? $value['PlayerRanking']['Ranking'] : '';
            $value['RoleId'] = isset($value['PlayerBasicInfo']['RoleInfo']['Id']) ? $value['PlayerBasicInfo']['RoleInfo']['Id'] : -1;//游戏位置ID，单独字段
            $value['RoleName'] = isset($value['PlayerBasicInfo']['RoleInfo']['Name']) ? $value['PlayerBasicInfo']['RoleInfo']['Name'] : '';//游戏位置名称，单独字段
            $arr[$key] = $value;
        }
        if (!empty($arr)) {
            $lolPlayerRanking = new LolPlayerRanking();
            foreach ($arr as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolPlayerRanking->load($v2, '');
                if (!empty($lolPlayerRanking->getErrors())) {
                    static::$content[] = "[SpiderService] getLolPlayerRanking " . $lolPlayerRanking->getErrors();
                    unset($arr[$k2]);
                }
            }
            $arr = array_merge($arr);
            LolPlayerRanking::deleteAll();
            LolPlayerRanking::getDb()->createCommand()->batchInsert(LolPlayerRanking::tableName(), $arr);

            static::log("[SpiderService] getLolPlayerRanking , done!");
        } else {
            static::log("[SpiderService] GetPlayerList no result url" . $lolUrl);
        }
    }

    /**
     * LOL全球赛事列表
     */
    public static function getLolMatchList()
    {
        $lolUrl = 'http://lol.esportsmatrix.com/zh-CN/Match/League';
        $html = \requests::get($lolUrl);
        if (!$html) {
            static::log("[SpiderService] GetMatchList no result url" . $lolUrl);
            return;
        }

        //获取所有赛事
        $selector = '/html/body/div[1]/div[2]/div[2]/div/section[1]/div[2]/div[2]/ul/li/h3';
        $res = \selector::select($html, $selector);
        if (empty($res) || count($res) < 1) {
            static::log("[SpiderService] GetMatchList, select empty , url $lolUrl , selector url" . $selector);
            return;
        }

        $count = count($res);
        $arr = [];
        for ($i = 1; $i <= $count; $i++) {
            //赛事id
            $selector = "/html/body/div[1]/div[2]/div[2]/div/section[1]/div[2]/div[2]/ul/li[" . $i . "]/h3/@data-id";
            $matchId = \selector::select($html, $selector);

            //赛事名称
            $selector = "/html/body/div[1]/div[2]/div[2]/div/section[1]/div[2]/div[2]/ul/li[" . $i . "]/h3";
            $name = \selector::select($html, $selector);

            //赛事logo
            $selector = "/html/body/div[1]/div[2]/div[2]/div/section[1]/div[2]/div[2]/ul/li[" . $i . "]/div/img/@src";
            $logo = \selector::select($html, $selector);

            if(empty($matchId) || empty($logo) || empty($name))
            {
                continue;
            }

            $arr[] = [
                'LeagueId' => $matchId,
                'LeagueName' => $name,
                'LeagueLogo' => $logo
            ];
        }
        if (!empty($arr)) {
            $lolMatchList = new LolMatchList();
            foreach ($arr as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolMatchList->load($v2, '');
                if (!empty($lolMatchList->getErrors())) {
                    static::$content[] = "[SpiderService] getLolMatchList " . $lolMatchList->getErrors();
                    unset($arr[$k2]);
                }
            }
            $arr = array_merge($arr);
            LolMatchList::deleteAll();
            LolMatchList::getDb()->createCommand()->batchInsert(LolMatchList::tableName(), $arr);

            static::log("[SpiderService] getLolMatchList , done!");
        } else {
            static::log("[SpiderService] GetMatchList no result url" . $lolUrl);
        }


    }

    /**
     * 数据中心-最新赛果-比赛详情
     */
    public static function getLolMatchDetail()
    {
		//所有最新赛果列表
		$lolUrl = LolMatchResult::find()->orderBy("StartTime DESC")->asArray()->all();

        foreach ($lolUrl as $kk => $vv) {
            //总是更新今天的赛事详情
            $isToday = $vv['StartTime'] >= strtotime(date("Y-m-d"));

            //非今天的赛事，判断数据库是否存在详情信息
            if(!$isToday)
            {
                $res = LolMatchDetail::find()->where(['LeagueId' => (int)$vv['Id']])->asArray()->one();
                if ($res ) 
                {
				    continue;
			    }
            }
  
            $teamAName = json_decode($vv['TeamA'], true)['Name'];
            $teamBName = json_decode($vv['TeamB'], true)['Name'];
            $date = date('Y-m-d',$vv['StartTime']);
            $url = 'http://lol.esportsmatrix.com/zh-CN/Match/Detail?id=' . $vv['Id'];////4260 ;//4109
			static::log("[SpiderService] GetLolMatchDetail request url:" . $url);
            $html = \requests::get($url);
            if (!$html) {
                static::log("[SpiderService] GetLolMatchDetail no result url:" . $url);
                continue;
            }
            static::spiderLolMatchDetail($html, $url, $vv['Id'], $teamAName, $teamBName, $date);
        }

        static::log("[SpiderService] getLolMatchDetail , done!");
    }

    private static function spiderLolMatchDetail($html, $lolUrl, $leagueId, $teamAName, $teamBName, $date)
    {
        $arr = [];
        $selector1 = '/html/body/div/div[2]/div[1]/img/@src';
        $arr['leagueLogo'] = $leagueLogo = \selector::select($html, $selector1);//赛事logo
        $selector2 = '/html/body/div/div[2]/div[2]/h2';
        $arr['leagueName'] = $leagueName = \selector::select($html, $selector2);//赛事名称
        $selector3 = '/html/body/div/div[2]/div[4]/div[2]/p[2]';
        $arr['matchType'] = $matchType = \selector::select($html, $selector3);//比赛规则
        $selector5 = '/html/body/div/div[2]/div[3]/span';
        $arr['matchDate'] = $matchDate = \selector::select($html, $selector5);//比赛日期


        $selector6 = '/html/body/div/div[2]/div[4]/div[1]/a/img/@src';
        $arr['teamALogo'] = $matchDate = \selector::select($html, $selector6);//战队Alogo

        $selector6 = '/html/body/div/div[2]/div[4]/div[3]/a/img/@src';
        $arr['teamBLogo'] = $matchDate = \selector::select($html, $selector6);//战队Blogo

        $selector7 = '/html/body/div/div[2]/div[4]/div[2]/p[1]/span';
        $arr2 = \selector::select($html, $selector7);
        if (empty($arr2) || count($arr2) < 1) {
            static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector7);
        }
        $arr['avsB'] = '';
        for ($i = 1; $i <= count($arr2); $i++) {
            $selector = "/html/body/div/div[2]/div[4]/div[2]/p[1]/span[" . $i . "]";
            $arr['avsB'] .= \selector::select($html, $selector);//比分  A对B队的比分， 如 2:1
        }
        $selector8 = '/html/body/div/div[3]/div[2]/div';
        $arr3 = \selector::select($html, $selector8);
        if (empty($arr3) || count($arr3) < 1) {
            static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector8);
        }

        //遍历多局数
        for ($i = 1; $i <= count($arr3); $i++) {

            //获取每局获胜队伍
            $selector1 = "/html/body/div/div[3]/div[2]/div[" . $i . "]/h3";
            $arr['RoundData'][$i]['victoryTeam'] = $gameTime = \selector::select($html, $selector1);

            //获取每局游戏时间
            $selector4 = "/html/body/div/div[3]/div[2]/div[" . $i . "]/p";
            $arr['RoundData'][$i]['gameTime'] = $gameTime = \selector::select($html, $selector4);//游戏时间-分钟
            if (!$arr['RoundData'][$i]['gameTime']) {//格式可能不匹配
                $selector4 = "/html/body/div/div[3]/div[" . $i . "]/div/p";
                $arr['RoundData'][$i]['gameTime'] = $gameTime = \selector::select($html, $selector4);//游戏时间-分钟
            }

            //获取每局经济差
            $selector6 = "/html/body/div/div[2]/div[4]/div[1]/div[" . $i . "]/ul/li";
            $arr1 = \selector::select($html, $selector6);
            if (empty($arr1) || count($arr1) < 1) {
                static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector6);
            }
            $arr['RoundData'][$i]['teamAGoldDiff25'] = '';
            for ($a = 1; $a <= count($arr1); $a++) {
                $selector = "/html/body/div/div[2]/div[4]/div[1]/div[" . $i . "]/ul/li[" . $a . "]/span";
                $arr['RoundData'][$i]['teamAGoldDiff25'] .= \selector::select($html, $selector);//25分钟金额差
            }

            $arr['RoundData'][$i]['teamBGoldDiff25'] = '';
            for ($a = 1; $a <= count($arr1); $a++) {
                $selector = "/html/body/div/div[2]/div[4]/div[3]/div[" . $i . "]/ul/li[" . $a . "]/span";
                $arr['RoundData'][$i]['teamBGoldDiff25'] .= \selector::select($html, $selector);//25分钟金额差
            }

            //对弃权局进行处理  有多局时
            $selector9 = "/html/body/div/div[3]/div[2]/div[".$i."]";
            $arr4_2_2 = \selector::select($html, $selector9);
            if (!empty($arr4_2_2) && count($arr4_2_2) == 1) {
                $selector = "/html/body/div/div[3]/div[2]/div[".$i."]/div[4]/@class";
                $arr4_2_1 = \selector::select($html, $selector);
                if (!stristr($arr4_2_1, 'p-hide')) {
                    $arr['RoundData'][$i]['state'] = 2;
                }else{
                    $arr['RoundData'][$i]['state'] = 1;
                }
            }

            //对弃权局进行处理  只有一局时
            $selector9 = '/html/body/div/div[3]/div[2]/div';
            $arr4_2_2 = \selector::select($html, $selector9);
            if (!empty($arr4_2_2) && count($arr4_2_2) == 1) {
                $selector = "/html/body/div/div[3]/div[2]/div/div[4]/@class";
                $arr4_2_1 = \selector::select($html, $selector);
                if (!stristr($arr4_2_1, 'p-hide')) {
                    $arr['RoundData'][$i]['state'] = 2;
                }else{
                    $arr['RoundData'][$i]['state'] = 1;
                }
            }

            if (!isset($arr['RoundData'][$i]['state'])){
                $arr['RoundData'][$i]['state']='';
            }

            $selector8_1 = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li";
            $arr3_1 = \selector::select($html, $selector8_1);
            if (empty($arr3_1) || count($arr3_1) < 1) {
                static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr3_1);
            }

            //遍历每局一血一塔等数据
            for ($ii = 1; $ii <= count($arr3_1); $ii++) {
                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]";
                $arr3_1_1 = \selector::select($html, $selector);
                if (stristr($arr3_1_1, 'src')) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]/img/@src";
                    $arr3_2_1 = \selector::select($html, $selector);
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]/h4/@class";
                    $arr3_2_1_1 = \selector::select($html, $selector);
                    $color = substr($arr3_2_1_1, 0, strripos($arr3_2_1_1, '-'));
                    $arr3_2_1 = $arr3_2_1 . ',' . $color;
                }
                if (stristr($arr3_1_1, 'div')) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]/div";
                    $arr3_2_1 = \selector::select($html, $selector);
                    $arr3_2_1 = $arr3_2_1 . ',grey';
                }


                $arr['RoundData'][$i]['fAll'][$ii] = $arr3_2_1;
            }

            $selector8_2 = '/html/body/div/div[3]/div[2]/div[1]/div[2]/ul';
            $arr4_1 = \selector::select($html, $selector8_2);
            if (empty($arr4_1) || count($arr4_1) < 1) {
                static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr4_1);
            }
            $arr4_2 = [];
            //遍历每局两队战队数据
            for ($ii = 1; $ii <= count($arr4_1); $ii++) {
                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[2]/img";
                $arr4_2_2 = \selector::select($html, $selector);
                if (empty($arr4_2_2) || count($arr4_2_2) < 1) {
                    static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr4_2_2);
                }

                //单局每队ban数量
                for ($iii = 1; $iii <= count($arr4_2_2); $iii++) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[2]/img[" . $iii . "]/@src";
                    $arr4_2_1 = \selector::select($html, $selector);
                    $arr['RoundData'][$i][$ii]['ban'][] = $arr4_2_1;
                }
                $arr4_2[$i][] = $arr4_2_1;

                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[3]/div";
                $arr4_2_3 = \selector::select($html, $selector);
                if (empty($arr4_2_3) || count($arr4_2_3) < 1) {
                    static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr4_2_3);
                }

                //单局每队杀敌、敌杀、助攻数据
                for ($iii = 1; $iii <= count($arr4_2_3); $iii++) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[3]/div[" . $iii . "]/text()";
                    $arr4_2_1 = \selector::select($html, $selector);
                    $arr['RoundData'][$i][$ii]['text'][] = $arr4_2_1;
                }

                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/@class";
                $arr4_2_2 = \selector::select($html, $selector);
                if (stristr($arr4_2_2, 'red')) {
                    $arr['RoundData'][$i][$ii]['signColor'] = 'red';
                } elseif (stristr($arr4_2_2, 'blue')) {
                    $arr['RoundData'][$i][$ii]['signColor'] = 'blue';
                }

                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li";
                $arr4_2_5 = \selector::select($html, $selector);
                if (empty($arr4_2_5) || count($arr4_2_5) < 1) {
                    static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr4_2_5);
                }
                $arr4_2_4 = [];
                //单局每队选手数据
                for ($iii = 1; $iii <= count($arr4_2_5); $iii++) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div";
                    $arr4_2_6 = \selector::select($html, $selector);
                    if (empty($arr4_2_6) || count($arr4_2_6) < 1) {
                        static::log("[SpiderService] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $arr4_2_6);
                    }
                    $arr4_2_4[] = $arr4_2_1;
                    //每个选手的个人数据
                    for ($iiii = 1; $iiii <= count($arr4_2_6); $iiii++) {
                        $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div[" . $iiii . "]";
                        $arr4_2_1 = \selector::select($html, $selector);
                        if (stristr($arr4_2_1, 'href')) {
                            $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div[" . $iiii . "]/a";
                            $arr4_2_1 = \selector::select($html, $selector);
                        }
                        if (stristr($arr4_2_1, 'src')) {
                            $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div[" . $iiii . "]/img/@src";
                            $arr4_2_1 = \selector::select($html, $selector);
                        }
                        $arr['RoundData'][$i][$ii]['player_data'][] = $arr4_2_1;
                    }
                }
            }
        }
        static::matchDeatailCrud($arr, $leagueId, $teamAName, $teamBName, $date);
    }

    private static function matchDeatailCrud($arr, $leagueId, $teamAName, $teamBName, $date)
    {
        $newArr = [];
        $newArr['LeagueLogo'] = $arr['leagueLogo'];
        $newArr['LeagueName'] = $arr['leagueName'];
        $newArr['match_date'] = $date;
        $newArr['AvsB'] = $arr['avsB'];
        $newArr['MatchType'] = explode(' ', $arr['matchType'])[1];
        $newArr['LeagueId'] = (int)$leagueId;
        $newArr['TeamALogo'] = $arr['teamALogo'];
        $newArr['TeamBLogo'] = $arr['teamBLogo'];
        $newArr['TeamAName'] = $teamAName;
        $newArr['TeamBName'] = $teamBName;
        $newArr['RoundData'] = [];
        if (!empty($arr['RoundData'])) {
            foreach ($arr['RoundData'] as $key => $value) {
                $newArr1 = [];
                $newArr1['state'] = $value['state'];
                $fData = [];
                foreach ($value['fAll'] as $k1 => $v1) {
                    $vArr = explode(',', $v1);
                    switch ($k1) {
                        case 1:
                            $fData['Fb'] = $vArr[0];
                            $fData['FbColor'] = $vArr[1];
                            $fData['FbName'] = '一血';
                            break;
                        case 2:
                            $fData['Ft'] = $vArr[0];
                            $fData['FtColor'] = $vArr[1];
                            $fData['FtName'] = '一塔';
                            break;
                        case 3:
                            $fData['Fd'] = $vArr[0];
                            $fData['FdColor'] = $vArr[1];
                            $fData['FdName'] = '一龙';
                            break;
                        case 4:
                            $fData['Fbd'] = $vArr[0];
                            $fData['FbdColor'] = $vArr[1];
                            $fData['FbdName'] = '首大龙';
                            break;
                        case 5:
                            $fData['Fh'] = $vArr[0];
                            $fData['FhColor'] = $vArr[1];
                            $fData['FhName'] = '一先锋';
                            break;
                        case 6:
                            $fData['Fan'] = $vArr[0];
                            $fData['FanColor'] = $vArr[1];
                            $fData['FanName'] = '首远古巨龙';
                            break;
                    }
                    $newArr1['fData'] = $fData;
                }
                $newArr1['TeamA']['ban'] = $value[1]['ban'][0] ? $value[1]['ban'] : [];
                $newArr1['TeamA']['kill'] = $value[1]['text'][0];
                $newArr1['TeamA']['dead'] = $value[1]['text'][1];
                $newArr1['TeamA']['assist'] = $value[1]['text'][2];
                $newArr1['TeamA']['signColor'] = $value[1]['signColor'];
                foreach ($value[1]['player_data'] as $k2 => $v2) {
                    if ($k2 < 6) {
                        continue;
                    }
                    $pos = (int)$k2 / 6;
                    if ($k2 % 6 == 0) {
                        $newArr1['TeamA']['members'][$pos]['Name'] = $v2;
                    }
                    if ($k2 % 6 == 1) {
                        $newArr1['TeamA']['members'][$pos]['LastHit15'] = $v2;
                    }
                    if ($k2 % 6 == 2) {
                        $newArr1['TeamA']['members'][$pos]['KDA'] = $v2;
                    }
                    if ($k2 % 6 == 3) {
                        if (stristr($v2, '&#13;')) {
                            $v2 = '';
                        } else {
                            $v2 = 'http://lol.esportsmatrix.com' . $v2;
                        }
                        $newArr1['TeamA']['members'][$pos]['Carry'] = $v2;
                    }
                    if ($k2 % 6 == 4) {
                        if (stristr($v2, '&#13;')) {
                            $v2 = '';
                        } else {
                            $v2 = 'http://lol.esportsmatrix.com' . $v2;
                        }
                        $newArr1['TeamA']['members'][$pos]['Troll'] = $v2;
                    }
                    if ($k2 % 6 == 5) {
                        $newArr1['TeamA']['members'][$pos]['HeroLogo'] = $v2;
                    }
                }
                if (!isset($value[2])) {
                    $arr['RoundData'] = [];
                } else {
                    $newArr1['TeamB']['ban'] = $value[2]['ban'][0] ? $value[2]['ban'] : [];
                    $newArr1['TeamB']['kill'] = $value[2]['text'][0];
                    $newArr1['TeamB']['dead'] = $value[2]['text'][1];
                    $newArr1['TeamB']['assist'] = $value[2]['text'][2];
                    $newArr1['TeamB']['signColor'] = $value[2]['signColor'];
                    foreach ($value[2]['player_data'] as $k2 => $v2) {
                        if ($k2 < 6) {
                            continue;
                        }
                        $pos = (int)$k2 / 6;
                        if ($k2 % 6 == 0) {
                            $newArr1['TeamB']['members'][$pos]['Name'] = $v2;
                        }
                        if ($k2 % 6 == 1) {
                            $newArr1['TeamB']['members'][$pos]['LastHit15'] = $v2;
                        }
                        if ($k2 % 6 == 2) {
                            $newArr1['TeamB']['members'][$pos]['KDA'] = $v2;
                        }
                        if ($k2 % 6 == 3) {
                            if (stristr($v2, '&#13;')) {
                                $v2 = '';
                            } else {
                                $v2 = 'http://lol.esportsmatrix.com' . $v2;
                            }
                            $newArr1['TeamB']['members'][$pos]['Carry'] = $v2;
                        }
                        if ($k2 % 6 == 4) {
                            if (stristr($v2, '&#13;')) {
                                $v2 = '';
                            } else {
                                $v2 = 'http://lol.esportsmatrix.com' . $v2;
                            }
                            $newArr1['TeamB']['members'][$pos]['Troll'] = $v2;
                        }
                        if ($k2 % 6 == 5) {
                            $newArr1['TeamB']['members'][$pos]['HeroLogo'] = $v2;
                        }
                    }
                    $newArr1['gameTime'] = explode(' ', explode('：', $value['gameTime'])[1])[0];
                    $newArr1['TeamAGoldDiff25'] = $value['teamAGoldDiff25'];
                    $newArr1['TeamBGoldDiff25'] = $value['teamBGoldDiff25'];
                    $newArr1['victoryTeam'] = substr($value['victoryTeam'], 0, mb_stripos($value['victoryTeam'], '获'));
                    if ($newArr1['victoryTeam']==$teamAName){
                        $newArr1['victoryLogo']=$newArr['TeamBLogo'];
                    }elseif ($newArr1['victoryTeam']==$teamBName){
                        $newArr1['victoryLogo']=$newArr['TeamALogo'];
                    }
                    $newArr['RoundData'][$key] = $newArr1;
                }
            }

        } else {
            $arr['RoundData'] = [];
        }
        $lolMatchDetail = LolMatchDetail::findOne(['LeagueId' => (int)$newArr['LeagueId']]);
        $lolMatchDetailObj = new LolMatchDetail();
		$newArr2 = static::formatJsonData($newArr);
        $lolMatchDetailObj->load($newArr2, '');
        if (!empty($lolMatchDetailObj->getErrors())) {
            static::$content[] = "[SpiderService] getLolMatchDetail " . $lolMatchDetailObj->getErrors();
            unset($newArr);
        }
        if (!empty($newArr)) {
            if (!empty($lolMatchDetail)) {
                LolMatchDetail::deleteAll(['LeagueId' => (int)$newArr['LeagueId']]);
                $res = LolMatchDetail::getDb()->createCommand()->Insert(LolMatchDetail::tableName(), $newArr);
            } else {
                $res = LolMatchDetail::getDb()->createCommand()->Insert(LolMatchDetail::tableName(), $newArr);
            }
            var_dump($res);
        }
    }

    /**
     * lol选手魔方价值变动榜
     */
    public static function getLolPlayerWorthRange()
    {
        $htmlUrl = 'http://lol.esportsmatrix.com/zh-CN/';
        $html = \requests::get($htmlUrl);
        if (!$html) {
            static::log("[SpiderService] getLolPlayerWorthRange, no result url:" . $htmlUrl);
            return;
        }

        //获取赛事数量使用
        $selector = "//*[@id=\"goodPlayer\"]/li";
        $result = \selector::select($html, $selector);
        if (empty($result) || count($result) < 1) {
            static::log("[SpiderService] getLolPlayerWorthRange, select empty, url: $htmlUrl, selector:" . $selector);
            return;
        }

        //	遍历类型 升幅榜 ， 跌幅榜
        $types = [1 => 'goodPlayer', 2 => 'badPlayer'];

        //总遍历行数
        $count = count($result) - 1;

        //升幅排行榜
        $goodRrows = [];
        //跌幅排行榜
        $badRrows = [];

        for ($i = 1; $i <= $count; $i++) {
            foreach ($types as $type => $idStr) {
                //每一行数据
                $row = [];

                //排行类型  1:升幅榜  2-跌幅榜
                $row['type'] = $type;

                //名次
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/span';
                $result = \selector::select($html, $selector);
                $row['Ranking'] = $result;

                //头像
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/a/span[1]/img';
                $result = \selector::select($html, $selector);
                $row['HeadImg'] = $result;

                //队员姓名
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/a/span[2]';
                $result = \selector::select($html, $selector);
                $row['Name'] = $result;

                //KDA
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[2]/span';
                $result = \selector::select($html, $selector);
                $row['KDA'] = $result;

                //KDA上升趋势  1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[2]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['KDADiff'] = $result;

                //胜率
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[3]/span';
                $result = \selector::select($html, $selector);
                $row['WinLoss'] = $result;

                //胜率上升趋势  1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[3]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['WinLossDiff'] = $result;

                //魔方价值
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[4]/span';
                $result = \selector::select($html, $selector);
                $row['Strength'] = $result;

                //魔方价值-变动波幅
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[5]/span';
                $result = \selector::select($html, $selector);
                $row['StrengthDiff'] = $result;

                if ($type == 1) {
                    $goodRrows[] = $row;
                }
                if ($type == 2) {
                    $badRrows[] = $row;
                }
            }

        }
        $lolPlayerWorthRange = new LolPlayerWorthRange();
        //插入升幅榜数据
        if ($goodRrows) {
            foreach ($goodRrows as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolPlayerWorthRange->load($v2, '');
                if (!empty($lolPlayerWorthRange->getErrors())) {
                    static::$content[] = "[SpiderService] getLolPlayerWorthRange " . $lolPlayerWorthRange->getErrors();
                    unset($goodRrows[$k2]);
                }
            }
            $goodRrows = array_merge($goodRrows);
            //先清空升幅榜数据
            LolPlayerWorthRange::deleteAll(['type' => 1]);
            LolPlayerWorthRange::getDb()->createCommand()->batchInsert(LolPlayerWorthRange::tableName(), $goodRrows);
        }

        //添加跌幅榜数据
        if ($badRrows) {
            foreach ($badRrows as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolPlayerWorthRange->load($v2, '');
                if (!empty($lolPlayerWorthRange->getErrors())) {
                    static::$content[] = "[SpiderService] getLolPlayerWorthRange " . $lolPlayerWorthRange->getErrors();
                    unset($badRrows[$k2]);
                }
            }
            $badRrows = array_merge($badRrows);
            //先清空升幅榜数据
            LolPlayerWorthRange::deleteAll(['type' => 2]);
            LolPlayerWorthRange::getDb()->createCommand()->batchInsert(LolPlayerWorthRange::tableName(), $badRrows);
        }

        static::log("[SpiderService] getLolPlayerWorthRange , done!");
//		var_dump([$goodRrows, $badRrows]);exit;

    }

    /**
     * lol队伍实力变动榜
     */
    public static function getLolTeamStrengthRange()
    {
        $htmlUrl = 'http://lol.esportsmatrix.com/zh-CN/';
        $html = \requests::get($htmlUrl);
        if (!$html) {
            static::log("[SpiderService] getLolTeamStrengthRange, no result url:" . $htmlUrl);
            return;
        }

        //获取赛事数量使用
        $selector = "//*[@id=\"goodTeam\"]/li";
        $result = \selector::select($html, $selector);
        if (empty($result) || count($result) < 1) {
            static::log("[SpiderService] getLolTeamStrengthRange, select empty, url: $htmlUrl, selector:" . $selector);
            return;
        }

        //	遍历类型 升幅榜 ， 跌幅榜
        $types = [1 => 'goodTeam', 2 => 'badTeam'];

        //总遍历行数
        $count = count($result) - 1;

        //升幅排行榜
        $goodRrows = [];
        //跌幅排行榜
        $badRrows = [];

        for ($i = 1; $i <= $count; $i++) {
            foreach ($types as $type => $idStr) {
                //每一行数据
                $row = [];

                //排行类型  1:升幅榜  2-跌幅榜
                $row['type'] = $type;

                //名次
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/span';
                $result = \selector::select($html, $selector);
                $row['Ranking'] = $result;

                //头像
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/a/span[1]/img';
                $result = \selector::select($html, $selector);
                $row['HeadImg'] = $result;

                //队员姓名
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[1]/a/span[2]';
                $result = \selector::select($html, $selector);
                $row['Name'] = $result;

                //胜率
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[2]/span';
                $result = \selector::select($html, $selector);
                $row['WinLoss'] = $result;

                //胜率上升趋势  1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[2]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['WinLossDiff'] = $result;

                //一塔
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[3]/span';
                $result = \selector::select($html, $selector);
                $row['Ft'] = $result;

                //一塔变化趋势  1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[3]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['FtDiff'] = $result;


                //一龙
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[4]/span';
                $result = \selector::select($html, $selector);
                $row['Fr'] = $result;

                //一龙-变化趋势 1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[4]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['FrDiff'] = $result;

                //一血
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[5]/span';
                $result = \selector::select($html, $selector);
                $row['Fb'] = $result;

                //一血变化趋势 1：上升  0：持平 -1：下降
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[5]/@data-diff';
                $result = \selector::select($html, $selector);
                $row['FbDiff'] = $result;

                //积分
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[6]/span';
                $result = \selector::select($html, $selector);
                $row['Strength'] = $result;

                //积分变动趋势
                $selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[7]/span';
                $result = \selector::select($html, $selector);
                $row['StrengthDiff'] = $result;

                if ($type == 1) {
                    $goodRrows[] = $row;
                }
                if ($type == 2) {
                    $badRrows[] = $row;
                }
            }

        }
        $lolTeamStrengthRange = new LolTeamStrengthRange();
        //插入升幅榜数据
        if ($goodRrows) {
            foreach ($goodRrows as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolTeamStrengthRange->load($v2, '');
                if (!empty($lolTeamStrengthRange->getErrors())) {
                    static::$content[] = "[SpiderService] getLolPlayerWorthRange " . $lolTeamStrengthRange->getErrors();
                    unset($goodRrows[$k2]);
                }
            }
            $goodRrows = array_merge($goodRrows);
            //先清空升幅榜数据
            LolTeamStrengthRange::deleteAll(['type' => 1]);
            LolTeamStrengthRange::getDb()->createCommand()->batchInsert(LolTeamStrengthRange::tableName(), $goodRrows);
        }

        //添加跌幅榜数据
        if ($badRrows) {
            foreach ($badRrows as $k2 => $v2) {
				$v2 = static::formatJsonData($v2);
                $lolTeamStrengthRange->load($v2, '');
                if (!empty($lolTeamStrengthRange->getErrors())) {
                    static::$content[] = "[SpiderService] getLolPlayerWorthRange " . $lolTeamStrengthRange->getErrors();
                    unset($badRrows[$k2]);
                }
            }
            $badRrows = array_merge($badRrows);
            //先清空升幅榜数据
            LolTeamStrengthRange::deleteAll(['type' => 2]);
            LolTeamStrengthRange::getDb()->createCommand()->batchInsert(LolTeamStrengthRange::tableName(), $badRrows);
        }

        static::log("[SpiderService] getLolTeamStrengthRange , done!");

//		var_dump([$goodRrows, $badRrows]);exit;

    }

    /**
     * log info
     * @param $msg
     */
    protected static function log($msg)
    {
        Yii::info($msg, "backend.SpiderService");
        $time = date('Y-m-d H:i:s', time());
        echo $time . " " . $msg . "\n";
    }

	/**
	 * 格式化保存mongo数据库的json数据， 数组字段进行json加密
	 * @param array $data
	 * @return array
	 */
	protected static function formatJsonData($data=[])
	{
		if(empty($data) || !is_array($data))
		{
			return $data;
		}

		foreach ($data as & $val)
		{
			if(is_array($val))
			{
				$val = json_encode($val);
			}
		}
		return $data;
	}
}
