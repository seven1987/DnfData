<?php

namespace common\services;

use common\models\Dota2MatchHistory;
use common\models\Dota2MatchList;
use common\models\Dota2MatchResult;
use common\models\Dota2MatchSchedule;
use common\models\Dota2MatchTeamRanking;
use common\models\Dota2PlayerRanking;
use common\models\Dota2PlayerWorthRange;
use common\models\Dota2TeamRanking;
use common\models\Dota2TeamStrengthRange;
use Yii;
use common\utils\DataPackager;
use common\utils\SysCode;
use common\utils\CommonFun;
use common\models\Dota2ChampionUsage;
use common\models\Dota2ChampionWin;

use common\models\Dota2MatchDetail;

ini_set("memory_limit", "1024M");
require_once ROOT_PATH . 'common/utils/phpspider/core/init.php';

/**
 * 电竞魔方dota2爬虫服务
 * Class SpiderService
 * @package common\models
 */
class SpiderDota2Service
{
	//如果存在分页数据， 最大爬取的分页数
	const MAX_PAGE_REQUEST = 1000;


	/**
	 * DOTA2 爬取数据总入口
	 */
	public static function spiderGetDota2Data()
	{
		static::getDota2ChampionUsage();
		static::getDota2ChampionWin();
		static::getDota2MatchSchedule();
		static::getDota2MatchResult();
		static::getDota2MatchDetail();

		static::getDota2TeamRanking();
		static::getDota2PlayerRanking();

		static::getDota2GlobalMatchList();
		static::getDota2MatchTeamRanking();
		static::getDota2MatchHistory();

		static::getDota2PlayerWorthRange();
		static::getDota2TeamStrengthRange();
	}


	/**
	 * dota2英雄使用率列表
	 */
	public static function getDota2ChampionUsage()
	{
		//3个月的排行数据
		static::handleDota2ChampionUsage(3);
		//6个月的排行数据
		static::handleDota2ChampionUsage(6);
	}
	/**
	 * DOTA2英雄胜率列表
	 */
	public static function getDota2ChampionWin()
	{
		//3个月的排行数据
		static::handleDota2ChampionWin(3);
		//6个月的排行数据
		static::handleDota2ChampionWin(6);
	}

	/**
	 * dota2数据中心-赛事进程
	 */
	public static function getDota2MatchSchedule()
	{
		$url = "http://dota2.esportsmatrix.com/zh-CN/Match/GetSchedule?pageIndex=%d";
		$curUrl = sprintf($url, 1);//第一页， 用来获取总分页数
		static::log("[SpiderDota2Service] getDota2MatchSchedule, request url:" . $curUrl);
		$result = \requests::get($curUrl);
		$result = json_decode($result, true);
		if(empty($result) || json_last_error() || empty($result['Data']))
		{
			static::log("[SpiderDota2Service] getDota2MatchSchedule, no result url:" . $curUrl);
			return;
		}

		$totalPage = $result['Total'];
		if($totalPage > 0)
		{
			//实际爬取页数
			$existsCount = Dota2MatchSchedule::find()->count();//系统已爬取总条数
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

			$i = 1;
			while($i<=$rangePage && $i < static::MAX_PAGE_REQUEST){
				$curUrl = sprintf($url, $i);
				static::log("[SpiderDota2Service] getDota2MatchSchedule, request url:" . $curUrl);
				$result = \requests::get($curUrl);
				$result = json_decode($result,true);
				if(empty($result) || json_last_error() || empty($result['Data']))
				{
					static::log("[SpiderDota2Service] getDota2MatchSchedule, no result url:" . $curUrl);
					$i++;
					continue;
				}

				$data = $result['Data'];
				foreach ($data as $val)
				{
					$matchId = $val['MatchId'];//最新赛事id
					$val = static::formatJsonData($val);
					$model = Dota2MatchSchedule::find()->where(["MatchId" => $matchId])->one();
					if(empty($model))
					{
						$model = new Dota2MatchSchedule();
					}

					//比赛时间
					$val['StartTime'] = (int)($val['StartTime'] ? substr($val['StartTime'],6,10) : 0);

					$model->load($val,'');
					if(!$model->save())
					{
						static::log("[SpiderDota2Service] getDota2MatchSchedule, save error data:" . json_encode($model->getErrors()));
						$i++;
						continue;
					}
				}
				$i++;
			};

			//爬取成功后， 删除过时的(8小时以前)赛事进程
			Dota2MatchSchedule::deleteAll(['<','StartTime',time() - 60*60*8]);

			static::log("[SpiderDota2Service] getDota2MatchSchedule , done!");
		}
	}

	/**
	 * dota2数据中心-最新赛果
	 */
	public static function getDota2MatchResult()
	{
		$url = "http://dota2.esportsmatrix.com/zh-CN/Match/GetResults?pageIndex=%d";
		$curUrl = sprintf($url, 1);//第一页， 用来获取总分页数
		static::log("[SpiderDota2Service] getDota2MatchResult, request url:" . $curUrl);
		$result = \requests::get($curUrl);
		$result = json_decode($result, true);
		if(empty($result) || json_last_error() || empty($result['Data']))
		{
			static::log("[SpiderDota2Service] getDota2MatchResult, no result url:" . $curUrl);
			return;
		}

		$totalPage = $result['Total'];
		if($totalPage > 0)
		{
			//实际爬取页数
			$existsCount = Dota2MatchResult::find()->count();//系统已爬取总条数
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

			$i = 1;
			while($i<=$rangePage && $i < static::MAX_PAGE_REQUEST){

				$curUrl = sprintf($url, $i);
				static::log("[SpiderDota2Service] getDota2MatchResult, request url:" . $curUrl);
				$result = \requests::get($curUrl);
				$result = json_decode($result,true);
				if(empty($result) || json_last_error() || empty($result['Data']))
				{
					static::log("[SpiderDota2Service] getDota2MatchResult, no result url:" . $curUrl);
					$i++;
					continue;
				}

				$data = $result['Data'];
				foreach ($data as $val)
				{
					$id = $val['Id'];//最新赛事id
					$val = static::formatJsonData($val);

					$model = Dota2MatchResult::find()->where(["Id" => (int)$id])->one();
					if(empty($model))
					{
						$model = new Dota2MatchResult();
					}
					//比赛时间
					$val['StartTime'] = (int)($val['StartTime'] ? substr($val['StartTime'],6,10) : 0);
					$model->load($val,'');
					if(!$model->save())
					{
						static::log("[SpiderDota2Service] getDota2MatchResult, save error data:" . json_encode($model->getErrors()));
						$i++;
						continue;
					}
				}
				$i++;
			};
			static::log("[SpiderDota2Service] getDota2MatchResult , done!");
		}
	}

	/**
	 * dota2数据中心-战队排行数据
	 */
	public static function getDota2TeamRanking()
	{
		$url = "http://dota2.esportsmatrix.com/zh-CN/Team/GetRankingList?season=-1&name=";
		static::log("[SpiderDota2Service] getDota2TeamRanking, request url:" . $url);
		$result = \requests::get($url);
		$result = json_decode($result, true);
		if(empty($result) || json_last_error() || empty($result['Data']))
		{
			static::log("[SpiderDota2Service] getDota2TeamRanking, no result url:" . $url);
			return;
		}

		$data = $result['Data'];
		//先清空数据
		Dota2TeamRanking::deleteAll();
		$insertRows = [];
		foreach ($data as $val)
		{
			$val['team_name'] = isset($val['Team']['Name']) ? $val['Team']['Name'] : '';//战队名称， 用于条件搜索
			$val = static::formatJsonData($val);
			$insertRows[] = $val;
		}
		$ret = Dota2TeamRanking::getDb()->createCommand()->batchInsert(Dota2TeamRanking::tableName(), $insertRows);
		if(!$ret)
		{
			static::log("[SpiderDota2Service] getDota2TeamRanking , save error, data:" . json_encode($insertRows));
		}

		static::log("[SpiderDota2Service] getDota2TeamRanking , done!");
	}

	/**
	 * dota2数据中心-战队排行数据
	 */
	public static function getDota2PlayerRanking()
	{
		$url = "http://dota2.esportsmatrix.com/zh-CN/Player/GetRankingList?roleId=-1&season=-1&name=";
		static::log("[SpiderDota2Service] getDota2TeamRanking, request url:" . $url);
		$result = \requests::get($url);
		$result = json_decode($result, true);
		if(empty($result) || json_last_error() || empty($result['Data']))
		{
			static::log("[SpiderDota2Service] getDota2TeamRanking, no result url:" . $url);
			return;
		}

		$data = $result['Data'];
		//先清空数据
		Dota2PlayerRanking::deleteAll();
		$insertRows = [];
		foreach ($data as $val)
		{
			$val['player_name'] = isset($val['Player']['Name']) ? $val['Player']['Name'] : '';
			$val['RoleId'] = isset($val['Role']['Id']) ? $val['Role']['Id'] : -1;//游戏位置ID，单独字段
			$val['RoleName'] = isset($val['Role']['Name']) ? $val['Role']['Name'] : '';//游戏位置名称，单独字段
			$val = static::formatJsonData($val);
			$insertRows[] = $val;
		}
		$ret = Dota2PlayerRanking::getDb()->createCommand()->batchInsert(Dota2PlayerRanking::tableName(), $insertRows);
		if(!$ret)
		{
			static::log("[SpiderDota2Service] getDota2TeamRanking , save error, data:" . json_encode($insertRows));
		}

		static::log("[SpiderDota2Service] getDota2PlayerRanking , done!");
	}

	/**
	 * dota2 获取全球赛事列表
	 */
	public static function getDota2GlobalMatchList()
	{
		//联赛积分表页面url
		$htmlUrl = 'http://dota2.esportsmatrix.com/zh-CN/Match/League';
		static::log("[SpiderDota2Service] getDota2GlobalMatchList, request url:" . $htmlUrl);
		$html = \requests::get($htmlUrl);
		if(!$html)
		{
			static::log("[SpiderDota2Service] getDota2GlobalMatchList, no result url:" . $htmlUrl);
			return;
		}

		//最后插入数据
        $insertRows = [];

        //1 #### 常规赛事列表 ####
		//获取赛事数量使用
		$selector = "/html/body/div[3]/div[2]/div/section[1]/div[1]/div[2]/ul/li/h3";
		$result = \selector::select($html, $selector);
		if(empty($result) || count($result) < 1)
		{
			static::log("[SpiderDota2Service] getDota2GlobalMatchList, select empty, url: $htmlUrl, selector:" . $selector);
			return;
		}

		$count = count($result);
		for ($i=1; $i<= $count; $i++)
		{
			//赛事id
			$selector = "/html/body/div[3]/div[2]/div/section[1]/div[1]/div[2]/ul/li[".$i."]/h3/@data-id";
			$matchId = \selector::select($html, $selector);

			//赛事名称
			$selector = "/html/body/div[3]/div[2]/div/section[1]/div[1]/div[2]/ul/li[".$i."]/h3";
			$name = \selector::select($html, $selector);

			//赛事logo
			$selector = "/html/body/div[3]/div[2]/div/section[1]/div[1]/div[2]/ul/li[".$i."]/div/img/@src";
			$logo = \selector::select($html, $selector);

            if(empty($matchId) || empty($logo) || empty($name))
            {
                continue;
            }

			$insertRows[] = [
				'LeagueId' => (int)$matchId,
				'LeagueLogo' => (string)$logo,
				'LeagueName' => (string)$name,
			];
		}

        //2 #### 淘汰赛事列表 ####
        //获取赛事数量使用
		$selector = "/html/body/div[3]/div[2]/div/section[2]/div[1]/div[2]/ul/li/h3";
        $result = \selector::select($html, $selector);
        if(empty($result) || count($result) < 1)
        {
            static::log("[SpiderDota2Service] getDota2GlobalMatchList, select empty, url: $htmlUrl, selector:" . $selector);
            return;
        }

        $count = count($result);
        for ($i=1; $i<= $count; $i++)
        {
            //赛事id
            $selector = "/html/body/div[3]/div[2]/div/section[2]/div[1]/div[2]/ul/li[".$i."]/h3/@data-id";
            $matchId = \selector::select($html, $selector);

            //赛事名称
            $selector = "/html/body/div[3]/div[2]/div/section[2]/div[1]/div[2]/ul/li[".$i."]/h3";
            $name = \selector::select($html, $selector);

            //赛事logo
            $selector = "/html/body/div[3]/div[2]/div/section[2]/div[1]/div[2]/ul/li[".$i."]/div/img/@src";
            $logo = \selector::select($html, $selector);

            if(empty($matchId) || empty($logo) || empty($name))
            {
                continue;
            }

            $insertRows[] = [
                'LeagueId' => (int)$matchId,
                'LeagueLogo' => (string)$logo,
                'LeagueName' => (string)$name,
            ];
        }

//        var_dump($insertRows);exit;
		//分析有效数据
		if(empty($insertRows))
		{
			static::log("[SpiderDota2Service] getDota2GlobalMatchList , empty data!");
			return;
		}

		//先清空数据
		Dota2MatchList::deleteAll();

		//保存
		$ret = Dota2MatchList::getDb()->createCommand()->batchInsert(Dota2MatchList::tableName(), $insertRows);
		if(!$ret)
		{
			static::log("[SpiderDota2Service] getDota2GlobalMatchList, save error data:" . json_encode($insertRows));
		}

		static::log("[SpiderDota2Service] getDota2GlobalMatchList , done!");
	}

	/**
	 * dota2数据中心-全球赛事战队排行数据
	 */
	public static function getDota2MatchTeamRanking()
	{
		//获取所有的赛事
		$matchList = Dota2MatchList::find()->asArray()->all();
		if(empty($matchList))
		{
			static::log("[SpiderDota2Service] getDota2MatchTeamRanking, no match data" );
			return;
		}

		//遍历查询赛事对应的战队排行榜
		$url = "http://dota2.esportsmatrix.com/zh-CN/Match/GetTeamsStandings?leagueId=%d";
		foreach ($matchList as $val)
		{
			$matchId = $val['LeagueId'];
			//判断赛事战队排行表是否已存在数据
			$exists = Dota2MatchTeamRanking::find()->where(['LeagueId' => (int)$matchId])->one();
			if(!empty($exists))
			{
				continue;
			}
			$curUrl = sprintf($url, $matchId);
			static::log("[SpiderDota2Service] getDota2MatchTeamRanking, request, url:" . $curUrl);
			$result = \requests::get($curUrl);
			$result = json_decode($result, true);
			if(empty($result) || json_last_error() || empty($result['Data']))
			{
				static::log("[SpiderDota2Service] getDota2MatchTeamRanking, no result, url:" . $curUrl);
				continue;
			}

			$data = $result['Data'];
			$insertRows = [];
			$i = 1;
			foreach ($data as $vv)
			{
				$vv['ranking'] = $i;
				$vv['LeagueId'] = $matchId;
				$i++;
				$insertRows[] = $vv;
			}

			//分析有效数据
			if(empty($insertRows))
			{
				continue;
			}

			//先清空当前赛事的战队排行数据
			Dota2MatchTeamRanking::deleteAll(["LeagueId" => $matchId]);

			//保存
			$ret = Dota2MatchTeamRanking::getDb()->createCommand()->batchInsert(Dota2MatchTeamRanking::tableName(), $insertRows);
			if(!$ret)
			{
				static::log("[SpiderDota2Service] getDota2MatchTeamRanking, save error data:" . json_encode($insertRows));
			}

		}//遍历所有赛事完成

		static::log("[SpiderDota2Service] getDota2MatchTeamRanking , done!");
	}

	/**
	 * dota2数据中心-历史赛事
	 */
	public static function getDota2MatchHistory()
	{
		$url = "http://dota2.esportsmatrix.com/zh-CN/Match/GetChampionshipList?pageIndex=%d";
		$curUrl = sprintf($url, 1);
		static::log("[SpiderDota2Service] getDota2MatchHistory, request url:" . $curUrl);
		$result = \requests::get($curUrl);
		$result = json_decode($result, true);
		if(empty($result) || json_last_error() || empty($result['Data']))
		{
			static::log("[SpiderDota2Service] getDota2MatchHistory, no result url:" . $curUrl);
			return;
		}

		$totalPage = $result['Total'];
		if($totalPage > 0)
		{
			//实际爬取页数
			$existsCount = Dota2MatchHistory::find()->count();//系统已爬取总条数
			$existsPage = ceil($existsCount / 8);//计算已爬取的页数，按照电竞魔方8条一页
			//如果已爬取的跟实际页数相差5页以上， 则爬取全部; 否则只爬取最近的5页
			if(($totalPage - $existsPage) > 5)
			{
				$rangePage = $totalPage;
			}
			else
			{
				$rangePage = 5;
			}

			$i = 1;
			while($i<=$rangePage && $i < static::MAX_PAGE_REQUEST){
				$curUrl = sprintf($url, $i);
				static::log("[SpiderDota2Service] getDota2MatchHistory, request url:" . $curUrl);
				$result = \requests::get($curUrl);
				$result = json_decode($result,true);
				if(empty($result) || json_last_error() || empty($result['Data']))
				{
					static::log("[SpiderDota2Service] getDota2MatchHistory, no result url:" . $curUrl);
					$i++;
					return;
				}

				$data = $result['Data'];
				foreach ($data as $val)
				{
					$name = $val['Name'];//赛事名称唯一
					$val = static::formatJsonData($val);

					$model = Dota2MatchHistory::find()->where(["Name" => $name])->one();
					if(empty($model))
					{
						$model = new Dota2MatchHistory();
					}
					$model->load($val,'');
					if(!$model->save())
					{
						static::log("[SpiderDota2Service] getDota2MatchHistory, save error data:" . json_encode($model->getErrors()));
						$i++;
						return;
					}
				}
				$i++;
			};

			static::log("[SpiderDota2Service] getDota2MatchHistory , done!");
		}
	}

	/**
	 * dota2选手魔方价值变动榜
	 */
	public static function getDota2PlayerWorthRange()
	{
		$htmlUrl = 'http://dota2.esportsmatrix.com/zh-CN/';
		static::log("[SpiderDota2Service] getDota2PlayerWorthRangeUp, request url:" . $htmlUrl);
		$html = \requests::get($htmlUrl);
		if(!$html)
		{
			static::log("[SpiderDota2Service] getDota2PlayerWorthRangeUp, no result url:" . $htmlUrl);
			return;
		}

		//获取赛事数量使用
		$selector = "//*[@id=\"goodPlayer\"]/li";
		$result = \selector::select($html, $selector);
		if(empty($result) || count($result) < 1)
		{
			static::log("[SpiderDota2Service] getDota2PlayerWorthRangeUp, select empty, url: $htmlUrl, selector:" . $selector);
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

		for($i=1;$i<=$count;$i++)
		{
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

				if ($type == 1)
				{
					$goodRrows[] = $row;
				}
				if ($type == 2)
				{
					$badRrows[] = $row;
				}
			}

		}

		//插入升幅榜数据
		if($goodRrows)
		{
			//先清空升幅榜数据
			Dota2PlayerWorthRange::deleteAll(['type' => 1]);
			Dota2PlayerWorthRange::getDb()->createCommand()->batchInsert(Dota2PlayerWorthRange::tableName(), $goodRrows);
		}

		//添加跌幅榜数据
		if($badRrows)
		{
			//先清空升幅榜数据
			Dota2PlayerWorthRange::deleteAll(['type' => 2]);
			Dota2PlayerWorthRange::getDb()->createCommand()->batchInsert(Dota2PlayerWorthRange::tableName(), $badRrows);
		}

		static::log("[SpiderDota2Service] getDota2PlayerWorthRange , done!");
//		var_dump([$goodRrows, $badRrows]);exit;

	}

	/**
	 * dota2队伍实力变动榜
	 */
	public static function getDota2TeamStrengthRange()
	{
		$htmlUrl = 'http://dota2.esportsmatrix.com/zh-CN/';
		static::log("[SpiderDota2Service] getDota2PlayerStrengthRangeUp, request url:" . $htmlUrl);
		$html = \requests::get($htmlUrl);
		if(!$html)
		{
			static::log("[SpiderDota2Service] getDota2PlayerStrengthRangeUp, no result url:" . $htmlUrl);
			return;
		}

		//获取赛事数量使用
		$selector = "//*[@id=\"goodTeam\"]/li";
		$result = \selector::select($html, $selector);
		if(empty($result) || count($result) < 1)
		{
			static::log("[SpiderDota2Service] getDota2PlayerStrengthRangeUp, select empty, url: $htmlUrl, selector:" . $selector);
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

		for($i=1;$i<=$count;$i++)
		{
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


				//首肉山
				$selector = '//*[@id="' . $idStr . '"]/li[' . ($i + 1) . ']/div[4]/span';
				$result = \selector::select($html, $selector);
				$row['Fr'] = $result;

				//首肉山-变化趋势 1：上升  0：持平 -1：下降
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

				if ($type == 1)
				{
					$goodRrows[] = $row;
				}
				if ($type == 2)
				{
					$badRrows[] = $row;
				}
			}

		}

		//插入升幅榜数据
		if($goodRrows)
		{
			//先清空升幅榜数据
			Dota2TeamStrengthRange::deleteAll(['type' => 1]);
			Dota2TeamStrengthRange::getDb()->createCommand()->batchInsert(Dota2TeamStrengthRange::tableName(), $goodRrows);
		}

		//添加跌幅榜数据
		if($badRrows)
		{
			//先清空升幅榜数据
			Dota2TeamStrengthRange::deleteAll(['type' => 2]);
			Dota2TeamStrengthRange::getDb()->createCommand()->batchInsert(Dota2TeamStrengthRange::tableName(), $badRrows);
		}

		static::log("[SpiderDota2Service] getDota2TeamStrengthRange , done!");
//		var_dump([$goodRrows, $badRrows]);exit;

	}

    public static function getDota2MatchDetail()
    {
        set_time_limit(1800);
        $dota2Url = Dota2MatchResult::find()->orderBy("StartTime DESC")->asArray()->all();

        foreach ($dota2Url as $kk => $vv) {
            //总是更新今天的赛事详情
            $isToday = $vv['StartTime'] >= strtotime(date("Y-m-d"));

            //非今天的赛事，判断数据库是否存在详情信息
            if(!$isToday)
            {
                $res = Dota2MatchDetail::find()->where(['LeagueId' => (int)$vv['Id']])->asArray()->one();
                if ($res ) 
				{
				    continue;
			    }
            }

            $teamAName = json_decode($vv['TeamA'], true)['Name'];
            $teamBName = json_decode($vv['TeamB'], true)['Name'];
            $date = date('Y-m-d',$vv['StartTime']);
            $url = 'http://dota2.esportsmatrix.com/zh-CN/Match/Detail?id=' . $vv['Id'];//异常的测试数据Id 11830//11785//11698//11544//11580//11573
            static::log("[SpiderDota2Service] GetLolMatchDetail request url" . $url);
            $html = \requests::get($url);
            if (!$html) {
                static::log("[SpiderDota2Service] GetLolMatchDetail no result url" . $url);
                continue;
            }
            static::spiderDota2MatchDetail($html, $url, $vv['Id'], $teamAName, $teamBName, $date);
        }
    }

    private static function spiderDota2MatchDetail($html, $lolUrl, $leagueId, $teamAName, $teamBName, $date)
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
            static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector7);

        }
        $arr['avsB'] = '';
        for ($i = 1; $i <= count($arr2); $i++) {
            $selector = "/html/body/div/div[2]/div[4]/div[2]/p[1]/span[" . $i . "]";
            $arr['avsB'] .= \selector::select($html, $selector);//比分  A对B队的比分， 如 2:1
            $avsB = $arr['avsB'];
        }

        $selector8 = '/html/body/div/div[3]/div[2]/div';
        $arr3 = \selector::select($html, $selector8);
        if (empty($arr3) || count($arr3) < 1) {
            static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector8);

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
                static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector6);

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
            if (empty($arr3) || count($arr3_1) < 1) {
                static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector8_1);
            }

            //遍历每局一血一塔等数据
            for ($ii = 1; $ii <= count($arr3_1); $ii++) {
                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]";
                $arr3_1_1 = \selector::select($html, $selector);

                if (stristr($arr3_1_1, 'div')) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]/div";
                    $arr3_2_1 = \selector::select($html, $selector);
                }
                if (stristr($arr3_1_1, 'src')) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/ul/li[" . $ii . "]/div/img/@src";
                    $arr3_2_1 = \selector::select($html, $selector);
                }

                $arr['RoundData'][$i]['fAll'][$ii] = isset($arr3_2_1) ? $arr3_2_1 : '';
            }

            $selector8_2 = '/html/body/div/div[3]/div[2]/div[1]/div[2]/ul';
            $arr4_1 = \selector::select($html, $selector8_2);
            if (empty($arr3) || count($arr4_1) < 1) {
                static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector8_2);
            }
            $arr4_2 = [];
            //遍历每局两队战队数据
            for ($ii = 1; $ii <= count($arr4_1); $ii++) {
                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[2]/img";
                $arr4_2_2 = \selector::select($html, $selector);
                if (empty($arr4_2_2) || count($arr4_2_2) < 1) {
                    static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector);
                }

                //单局每队ban数量
                for ($iii = 1; $iii <= count($arr4_2_2); $iii++) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[2]/ul[" . $ii . "]/li[2]/img[" . $iii . "]/@src";
                    $arr4_2_1 = \selector::select($html, $selector);
                    $arr['RoundData'][$i][$ii]['ban'][] = $arr4_2_1;
                }
                $arr4_2[$i][] = $arr4_2_1;

                $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li";
                $arr4_2_5 = \selector::select($html, $selector);
                if (empty($arr4_2_5) || count($arr4_2_5) < 1) {
                    static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector);
                }
                $arr4_2_4 = [];
                //单局每队选手数据
                for ($iii = 1; $iii <= count($arr4_2_5); $iii++) {
                    $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div";
                    $arr4_2_6 = \selector::select($html, $selector);
                    if (empty($arr4_2_6) || count($arr4_2_6) < 1) {
                        static::log("[SpiderDota2Service] GetLolMatchDetail, select empty , url $lolUrl , selector url" . $selector);
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
                        if (stristr($arr4_2_1, 'span')) {
                            $selector = "/html/body/div/div[3]/div[2]/div[" . $i . "]/div[3]/ul[" . $ii . "]/li[" . $iii . "]/div[" . $iiii . "]/span";
                            $arr4_2_1 = \selector::select($html, $selector);
                        }
                        $arr['RoundData'][$i][$ii]['player_data'][] = $arr4_2_1;
                    }
                }
            }

        }
        static::matchDota2DeatailCrud($arr, $leagueId, $teamAName, $teamBName, $date);
    }

    private static function matchDota2DeatailCrud($arr, $leagueId, $teamAName, $teamBName, $date)
    {
        $newArr = [];
        $newArr['LeagueLogo'] = $arr['leagueLogo'];
        $newArr['LeagueName'] = $arr['leagueName'];
        $newArr['match_date'] = $date;
//        $newArr['match_date'] = substr($arr['matchDate'], 0, 10);
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
                    switch ($k1) {
                        case 1:
                            $fData['Fb'] = $v1;
                            $fData['FbName'] = '一血';
                            break;
                        case 2:
                            $fData['Ft'] = $v1;
                            $fData['FtName'] = '一塔';
                            break;
                        case 3:
                            $fData['Tk'] = $v1;
                            $fData['TkName'] = '十杀';
                            break;
                        case 4:
                            $fData['Frs'] = $v1;
                            $fData['FrsName'] = '首肉山';
                            break;
                        case 5:
                            $fData['Fmc'] = $v1;
                            $fData['FmcName'] = '首近战兵营';
                            break;
                    }
                    $newArr1['fData'] = $fData;
                }
                $newArr1['TeamA']['ban'] = $value[1]['ban'][0] ? $value[1]['ban'] : [];
                foreach ($value[1]['player_data'] as $k2 => $v2) {
                    if ($k2 < 7) {
                        continue;
                    }
                    $pos = (int)$k2 / 7;
                    if ($k2 % 7 == 0) {
                        $newArr1['TeamA']['members'][$pos]['Name'] = $v2;
                    }
                    if ($k2 % 7 == 1) {
                        $newArr1['TeamA']['members'][$pos]['money/min'] = $v2;
                    }
                    if ($k2 % 7 == 2) {
                        $newArr1['TeamA']['members'][$pos]['exp/min'] = $v2;
                    }
                    if ($k2 % 7 == 3) {
                        $newArr1['TeamA']['members'][$pos]['KDA'] = $v2;
                    }
                    if ($k2 % 7 == 4) {
                        if (stristr($v2, '&#13;')) {
                            $v2 = '';
                        } else {
                            $v2 = 'http://dota2.esportsmatrix.com' . $v2;
                        }
                        $newArr1['TeamA']['members'][$pos]['Carry'] = $v2;
                    }
                    if ($k2 % 7 == 5) {
                        if (stristr($v2, '&#13;')) {
                            $v2 = '';
                        } else {
                            $v2 = 'http://dota2.esportsmatrix.com' . $v2;
                        }
                        $newArr1['TeamA']['members'][$pos]['Troll'] = $v2;
                    }
                    if ($k2 % 7 == 6) {
                        $newArr1['TeamA']['members'][$pos]['HeroLogo'] = $v2;
                    }
                }
                if (!isset($value[2])) {
                    $arr['RoundData'] = [];
                } else {
                    $newArr1['gameTime'] = explode(' ', explode('：', $value['gameTime'])[1])[0];
                    $newArr1['TeamAGoldDiff25'] = $value['teamAGoldDiff25'];
                    $newArr1['TeamBGoldDiff25'] = $value['teamBGoldDiff25'];
                    $newArr1['victoryTeam'] = substr($value['victoryTeam'], 0, mb_stripos($value['victoryTeam'], '获'));
                    if ($newArr1['victoryTeam']==$teamAName){
                        $newArr1['victoryLogo']=$newArr['TeamBLogo'];
                    }elseif ($newArr1['victoryTeam']==$teamBName){
                        $newArr1['victoryLogo']=$newArr['TeamALogo'];
                    }
                    $newArr1['TeamB']['ban'] = $value[2]['ban'] ? ($value[2]['ban'][0] ? $value[2]['ban'] : []) : [];
                    foreach ($value[2]['player_data'] as $k2 => $v2) {
                        if ($k2 < 7) {
                            continue;
                        }
                        $pos = (int)$k2 / 7;
                        if ($k2 % 7 == 0) {
                            $newArr1['TeamB']['members'][$pos]['HeroLogo'] = $v2;
                        }
                        if ($k2 % 7 == 1) {
                            if (stristr($v2, '&#13;')) {
                                $v2 = '';
                            } else {
                                $v2 = 'http://dota2.esportsmatrix.com' . $v2;
                            }
                            $newArr1['TeamB']['members'][$pos]['Troll'] = $v2;
                        }
                        if ($k2 % 7 == 2) {
                            if (stristr($v2, '&#13;')) {
                                $v2 = '';
                            } else {
                                $v2 = 'http://dota2.esportsmatrix.com' . $v2;
                            }
                            $newArr1['TeamB']['members'][$pos]['Carry'] = $v2;
                        }
                        if ($k2 % 7 == 3) {
                            $newArr1['TeamB']['members'][$pos]['KDA'] = $v2;
                        }
                        if ($k2 % 7 == 4) {
                            $newArr1['TeamB']['members'][$pos]['exp/min'] = $v2;
                        }
                        if ($k2 % 7 == 5) {
                            $newArr1['TeamB']['members'][$pos]['money/min'] = $v2;
                        }
                        if ($k2 % 7 == 6) {
                            $newArr1['TeamB']['members'][$pos]['Name'] = $v2;
                        }
                    }
                    $newArr['RoundData'][$key] = $newArr1;
                }

            }
        } else {
            $arr['RoundData'] = [];
        }

        $lolMatchDetail = Dota2MatchDetail::findOne(['LeagueId' => (int)$newArr['LeagueId']]);
        if (!empty($newArr)) {
            if (!empty($lolMatchDetail)) {
                Dota2MatchDetail::deleteAll(['LeagueId' => (int)$newArr['LeagueId']]);
                $res = Dota2MatchDetail::getDb()->createCommand()->Insert(Dota2MatchDetail::tableName(), $newArr);
            } else {
                $res = Dota2MatchDetail::getDb()->createCommand()->Insert(Dota2MatchDetail::tableName(), $newArr);
            }
            var_dump($res);
        }
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

	protected static function handleDota2ChampionUsage($month=3)
	{
		//基本接口
		$url = "http://dota2.esportsmatrix.com/zh-CN/Home/HeroWinUsage?month=%d&type=HeroUsage";
		$url= sprintf($url, $month);

		static::log("[SpiderDota2Service] getDota2ChampionUsage $month month , request url:" . $url);
		$result = \requests::get($url);
		$result = json_decode($result, $result);
		if(empty($result) || json_last_error() || empty($result[0]['Hero']))
		{
			static::log("[SpiderDota2Service] getDota2ChampionUsage $month month , no result url:" . $url);
		}
		else
		{
			//先清空数据
			Dota2ChampionUsage::deleteAll(["month" => $month]);
			$insertRows = [];
			foreach ($result as $val)
			{
				$row = [
					'Name' => $val['Hero']['Name'],
					'Id' => $val['Hero']['Id'],
					'Logo' => $val['Hero']['Logo'],//image
					'Rate' => $val['Rate'],
					'GrowthState' => $val['GrowthState'],
					'month' => $month,
				];
				$insertRows[] = $row;
			}
			$ret = Dota2ChampionUsage::getDb()->createCommand()->batchInsert(Dota2ChampionUsage::tableName(), $insertRows);
			if(!$ret)
			{
				static::log("[SpiderDota2Service] getDota2ChampionUsage 3 month, save error, data:" . json_encode($insertRows));
			}

			static::log("[SpiderDota2Service] handleDota2ChampionUsage $month month, done!");
		}
	}
	protected static function handleDota2ChampionWin($month=3)
	{
		//基本接口
		$url = "http://dota2.esportsmatrix.com/zh-CN/Home/HeroWinUsage?month=%d&type=HeroWin";
		$url= sprintf($url, $month);

		static::log("[SpiderDota2Service] handleDota2ChampionWin $month month , request url:" . $url);
		$result = \requests::get($url);
		$result = json_decode($result, $result);
		if(empty($result) || json_last_error() || empty($result[0]['Hero']))
		{
			static::log("[SpiderDota2Service] getDota2ChampionUsage $month month , no result url:" . $url);
		}
		else
		{
			//先清空数据
			Dota2ChampionWin::deleteAll(["month" => $month]);
			$insertRows = [];
			foreach ($result as $val)
			{
				$row = [
					'Name' => $val['Hero']['Name'],
					'Id' => $val['Hero']['Id'],
					'Logo' => $val['Hero']['Logo'],//image
					'Rate' => $val['Rate'],
					'GrowthState' => $val['GrowthState'],
					'month' => $month,
				];
				$insertRows[] = $row;
			}
			$ret = Dota2ChampionWin::getDb()->createCommand()->batchInsert(Dota2ChampionWin::tableName(), $insertRows);
			if(!$ret)
			{
				static::log("[SpiderDota2Service] getDota2ChampionUsage 3 month, save error, data:" . json_encode($insertRows));
			}

			static::log("[SpiderDota2Service] handleDota2ChampionWin $month month, done!");
		}
	}
}