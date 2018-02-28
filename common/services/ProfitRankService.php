<?php

namespace common\services;

use Yii;
use common\models\HisBet;
use common\models\HisIcoinBet;
use common\models\ProfitRank;
use common\models\MoneyRank;
use common\models\User;
use common\utils\CommonFun;
use common\utils\DataPackager;

class ProfitRankService
{
    /**
     * @param $type 币种 1：B币 | 2：I币
     * @return array|\yii\mongodb\ActiveRecord
     */
    public static function getRankList($type, $size = 10)
    {
        if (!is_int($type) || $type == 0 || !is_int($size) || $size == 0) {
            $type = 1;
            $size = 10;
        }

        //获取表中最新一期的日期
        $lastOne = ProfitRank::find()->select(['date'])->orderBy(['date' => SORT_DESC])->where(['type' => $type])->limit(1)->asArray()->one();
        $lastDate = $lastOne['date'];

        $query = ProfitRank::find();
        $query->where(['type' => $type]);

        if ($lastOne) {
            $query->andWhere(['date' => $lastDate]);
        }

        $lists = $query->select([
            'user_id',
            'date',
            'user_name',
            'profit',
            'type',
            'rank',
            'wins',
            'last_rank',
            'rank_status'
        ])->orderBy('rank')->limit($size)->asArray()->all();

        return DataPackager::pack(['data' => $lists]);
    }

    /**
     * 计算时间区间内B币或I币排行
     * @param $type 币种 1：B币 | 2：I币
     * @param $startTime 开始时间 例：'2017-05-20 00:00:00'
     * @param $endTime 结束时间 例：'2017-05-26 23:59:59'
     * @return array
     */
    public static function getRank($type, $startTime, $endTime, $limit = ProfitRank::OUTPUT_NUMBERS)
    {
        if ($type == ProfitRank::CURRENY_B) {
            $his_model = new HisBet();
        } elseif ($type == ProfitRank::CURRENY_I) {
            $his_model = new HisIcoinBet();
        } else {
            return [];
        }

        //获取时间段内有效盈利金额排行前100用户
        $hisBetCommand = $his_model::getDb()->createCommand();
        $pipeline = [
            ['$match' => ["between", "updatetime", $startTime, $endTime]],
            ['$match' => ['=', 'status', $his_model::STATUS_RECKON]],//只统计已结算注单
            ['$match' => ["=", "refundamount", null]],//排除存在退款金额的注单
            [
                '$group' => [
                    '_id' => ['user_id' => '$user_id', 'user_name' => '$user_name'],
//                    'profit' => ['$sum' => '$returnamount'],//不去除本金（日后可能不许统计去除本金数据，先留着）
                    'profit' => ['$sum' => ['$subtract' => ['$returnamount', '$betamount']]],//去除本金
                ]
            ],
            ['$sort' => ['profit' => -1]],
            ['$limit' => $limit]
        ];
        $profitData = $hisBetCommand->aggregate($his_model::tableName(), $pipeline);



        //排行榜前100名user_id集合
        $ids = array_column(array_column($profitData, '_id'), 'user_id');

        //获取$ids中user_id上期排行榜信息
        $lastOne = ProfitRank::find()->select(['date'])->orderBy(['date' => SORT_DESC])->where(['type' => $type])->limit(1)->asArray()->one();
        $lastDate = $lastOne['date'];
        $last_ranks = ProfitRank::find()->where([
            'user_id' => $ids,
            'date' => $lastDate
        ])->where(['type' => $type])->asArray()->all();
        $last_ids = array_column($last_ranks, 'rank', 'user_id');

        $date = (int)date('Ymd', time());

        //完善排行榜信息
        $profitRank = [];
        $i = 0;
        foreach ($profitData as $item) {

            //当期排名
            $rank = $i + 1;

            //获取用户上期排名
            if (isset($last_ids[$item['_id']['user_id']])) {
                $last_rank = $last_ids[$item['_id']['user_id']];
            } else {//新上榜用户
                $last_rank = 0;
            }

            //获取用户排名变化
            $rank_diff = $rank - $last_rank;
            if ($rank_diff == 0) {
                $rank_status = ProfitRank::RANK_UNCANGED;
            } elseif ($last_rank == 0) {
                $rank_status = ProfitRank::RANK_UP;
            } else {
                $rank_status = $rank_diff > 0 ? ProfitRank::RANK_DOWN : ProfitRank::RANK_UP;
            }

            //获取用户胜率
            $count_reckoned = $his_model::find()->where([
                'status' => $his_model::STATUS_RECKON,
                'refundamount' => null,
                'user_id' => (string)$item['_id']['user_id']
            ])->andWhere(['between', 'updatetime', $startTime, $endTime])->count();

            if ($count_reckoned > 0) {
                $count_win = $his_model::find()->where([
                    'status' => $his_model::STATUS_RECKON,
                    'refundamount' => null,
                    'user_id' => (string)$item['_id']['user_id']
                ])->andWhere(['>', 'returnamount', 0.0])->andWhere([
                    'between',
                    'updatetime',
                    $startTime,
                    $endTime
                ])->count();

                $wins = round($count_win / $count_reckoned * 100, 2);
            } else {
                $wins = 0.00;
            }

            $profitRank[] = [
                'date' => $date,
                'user_id' => $item['_id']['user_id'],
                'user_name' => $item['_id']['user_name'],
                'profit' => $item['profit'],
                'type' => $type,
                'rank' => $rank,
                'last_rank' => $last_rank,
                'rank_status' => $rank_status,
                'wins' => $wins,
                'updatetime' => date("Y-m-d H:i:s", time())
            ];
            $i++;
        }
        return $profitRank;
    }


}
