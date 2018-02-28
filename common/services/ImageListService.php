<?php
namespace common\services;

use common\utils\DataPackager;
use yii;
use common\models\ImageList;

class ImageListService extends ImageList{


    /**
     * 获取首页图片方法
     * @param $querys
     * @param $perPage
     * @param $orderBy
     * @return array
     */
    public static function getImageList($querys, $perPage,  $orderBy)
    {

        //初始化数据
        $query = ImageList::find();
        $AdminMenus = new ImageList();

        //查询条件
        static::fetchIndexCondition($query, $querys, $orderBy);

        //分页实例
        $count = $query->count();
        $pageInfo = BaseService::getPageInfos($count, $perPage);

        //列表查询
        $models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();

        return [
            'models' => $models,
            'pageInfo' => $pageInfo,
            'query' => $querys,
            'per_page' => $perPage,
            'labels' => $AdminMenus->attributeLabels(),
        ];

    }


    /**图片列表view
     * @return string
     */
    public static function getImageListView($id)
    {
        $model = ImageList::findOne($id);
        return $model->getAttributes();
    }




    protected static function fetchIndexCondition($query, $querys, $orderBy)
    {
        if (count($querys) > 0) {
            $condition = array();
            foreach ($querys as $key => $value) {
                $value = trim($value);
                if (empty($value) == false) {
                        $condition[$key] = [$key => (string)$value];
                }
            }
            if (count($condition) > 0) {
                foreach ($condition as $value) {
                    $query->andWhere($value);
                }
            }
        }
        $query->orderBy($orderBy);
    }



    /**
     * 创建首页图片
     * @param $data
     * @return array
     */
    public static function getImageListCreate($data)
    {
        $model = new imagelist();
        $count = ImageList::find()->asArray()->count("id");
        if($count >= ImageList::IMAGE_COUNT_LIMIT){
            return DataPackager::pack('' ,2,'已超过图片上限');
        }
        if ($model->load($data)) {
            if ($model->save()) {
                return DataPackager::pack('', 0, '保存成功');
            } else {
                $error = $model->getErrors();
                return DataPackager::pack('', 1, array_pop($error));
            }
        } else {
            $error = $model->getErrors();
            $msg = array_pop($error) ? array_pop($error) : '数据出错';
            return DataPackager::pack('', 1, $msg);
        }
    }

    /**
     * 编辑首页图片
     * @param $id
     * @param $data
     * @return string
     */
    public static function getImageListUpdate($menuId, $data){

        $model = ImageList::findOne($menuId);

        if ($model->load($data)) {
            if ($model->save()) {
                return DataPackager::pack('', 0, '保存成功');
            } else {
                $error = $model->getErrors();
                return DataPackager::pack('', 1, array_pop($error));
            }
        } else {
            $error = $model->getErrors();
            $msg = array_pop($error) ? array_pop($error) : '数据出错';
            return DataPackager::pack('', 1, $msg);
        }
    }

   
}
