<?php
namespace common\models;

require_once "IDGeneratorTrait.php";
use Yii;

/**
 * This is the model class for table "idgenerator".
 *
 * @property string $tablename
 * @property string $currentid
 * @property string $updatetime
 * @property string $createtime
 */
class IDGenerator extends Base
{
    use IDGeneratorTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tablename'], 'required'],
            [['currentid'], 'integer'],
            [['updatetime', 'createtime'], 'safe'],
            [['tablename'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tablename' => Yii::t('app', 'Tablename'),
            'currentid' => Yii::t('app', 'Currentid'),
            'updatetime' => Yii::t('app', 'Updatetime'),
            'createtime' => Yii::t('app', 'Createtime'),
        ];
    }

}
