<?php

namespace backend\commands;

use common\services\SpiderDota2Service;
use common\services\SpiderService;
use common\utils\CommonFun;
use yii\console\Controller;

/**
 * backend  Main 控制台脚本
 * Class MainController
 * @package backend\commands
 */
class MainController extends Controller
{
	/**
	 * 修正redis自增ID与数据库差异
	 */
	public function actionUpdatemodelidgenerator()
	{
		CommonFun::updateModelIdGenerator();
	}

	public function actionSpider()
    {
//        SpiderDota2Service::getDota2ChampionUsage();

        SpiderService::getLolChampionWin();
    }
}