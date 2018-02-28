<?php
namespace common\services;

use yii\data\Pagination;
use yii\widgets\LinkPager;
//backend  common function
class BaseService
{

    //返回自定义分页
    public static function getPerPage($perPage, $pageArray = null)
    {
        $defaultpageArray = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 250, 500, 1000];
        $pageArray = empty($pageArray) ? $defaultpageArray : $pageArray;
        $content = "";
        $content = $content . '<div id="pageinfo" class="pageinfo">';
        $content = $content . '<a class="page_menu" id="page_menu" >每页<strong>' . $perPage . '</strong>条</a>';
        $content = $content . '<span class="page_icon"></span>';
        $content = $content . '<ul class="page_list" >';
        foreach ($pageArray as $value) {
            if ($value == $perPage) {
                $content = $content . "<li class='page_active' ><a onclick='changePerPage(" . $value . ");' >" . $value . "</a></li> ";
            } else {
                $content = $content . "<li><a onclick='changePerPage(" . $value . ");'>" . $value . "</a></li> ";
            }
        }
        $content = $content . '</ul>';
        $content = $content . '</div>';
        return $content;
    }

    /**
     * 获取页面分页数据显示
     * @param $pageObj
     * @param $perPage
     * @return mixed
     */
    public static function getPageInfo($pageObj, $perPage)
    {
        $pageContent = [];
        $resultPage = $resultPerPage = $resultPageNumber = "";

        if ($pageObj->totalCount){
            $resultPage .= "从".( $pageObj->getPage() * $pageObj->getPageSize() + 1 );
            $resultPage .= " 到 ".( ($pageCount = ($pageObj->getPage() + 1) * $pageObj->getPageSize()) < $pageObj->totalCount ? $pageCount : $pageObj->totalCount);
        }
        $resultPage .= " 共 ".$pageObj->totalCount." 条记录";

        $resultPerPage =static::getPerPage($perPage);

        $resultPageNumber = LinkPager::widget([
            'pagination' => $pageObj,
            'nextPageLabel' => '»',
            'prevPageLabel' => '«',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
        ]);
        $pageContent['page'] = $resultPage;
        $pageContent['perpage'] = $resultPerPage;
        $pageContent['pagenumber'] = $resultPageNumber;
        return $pageContent;

    }

	/**
	 * 获取页面分页数据显示
	 * @param $pageObj
	 * @param $perPage
	 * @return mixed
	 */
	public static function getPageInfos($count, $perPage)
	{
		$pageObj = new Pagination([
				'totalCount' => $count,
				'pageSize' => $perPage,
				'pageParam' => 'page',
				'pageSizeParam' => 'per-page']
		);

		$pageContent = [];
		$resultPage = $resultPerPage = $resultPageNumber = "";

		if ($count) {
            $resultPage .= "从".( $pageObj->getPage() * $pageObj->getPageSize() + 1 );
            $resultPage .= " 到 ".( ($pageCount = ($pageObj->getPage() + 1) * $pageObj->getPageSize()) < $pageObj->totalCount ? $pageCount : $pageObj->totalCount);
        }
		$resultPage .= " 共 ".$pageObj->totalCount." 条记录";

		$resultPerPage =static::getPerPage($perPage);

		$resultPageNumber = LinkPager::widget([
			'pagination' => $pageObj,
			'nextPageLabel' => '»',
			'prevPageLabel' => '«',
			'firstPageLabel' => '首页',
			'lastPageLabel' => '尾页',
		]);
		$pageContent['page'] = $resultPage;
		$pageContent['per_page'] = $resultPerPage;
		$pageContent['page_number'] = $resultPageNumber;
		$pageContent['offset'] = $pageObj->offset;
		$pageContent['limit'] = $pageObj->limit;

		return $pageContent;

	}

}

