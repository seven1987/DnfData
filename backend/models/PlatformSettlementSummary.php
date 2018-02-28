<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "platform_settlement_summary".
 *
 * @property string $createtime
 * @property double $bcoinbalance
 * @property double $icoinbalance
 * @property double $moneybalance
 * @property double $moneyrecharge
 * @property double $moneyexchange
 * @property double $bcoinincrease
 * @property double $bcoindecrease
 * @property double $icoinincrease
 * @property double $icoindecrease
 * @property double $bcoinrecharge
 * @property double $bcoinwin
 * @property double $bcoinsign
 * @property double $bcoininvited
 * @property double $bcoinreward
 * @property double $bcoinexchange
 * @property double $bcoinlose
 * @property double $bcoinfee
 * @property double $icoinwin
 * @property double $icoinreg
 * @property double $icoinfirstrecharge
 * @property double $icoinsign
 * @property double $icoinreward
 * @property double $icoinexchange
 * @property double $icoinlose
 */
class PlatformSettlementSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'platform_settlement_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime'], 'required'],
            [['createtime'], 'safe'],
            [['bcoinbalance', 'icoinbalance', 'moneybalance', 'moneyrecharge', 'moneyexchange', 'bcoinincrease', 'bcoindecrease', 'icoinincrease', 'icoindecrease', 'bcoinrecharge', 'bcoinwin', 'bcoinsign', 'bcoininvited', 'bcoinreward', 'bcoinexchange', 'bcoinlose', 'bcoinfee', 'icoinwin', 'icoinreg', 'icoinfirstrecharge', 'icoinsign', 'icoinreward', 'icoinexchange', 'icoinlose'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'createtime' => 'Createtime',
            'bcoinbalance' => 'Bcoinbalance',
            'icoinbalance' => 'Icoinbalance',
            'moneybalance' => 'Moneybalance',
            'moneyrecharge' => 'Moneyrecharge',
            'moneyexchange' => 'Moneyexchange',
            'bcoinincrease' => 'Bcoinincrease',
            'bcoindecrease' => 'Bcoindecrease',
            'icoinincrease' => 'Icoinincrease',
            'icoindecrease' => 'Icoindecrease',
            'bcoinrecharge' => 'Bcoinrecharge',
            'bcoinwin' => 'Bcoinwin',
            'bcoinsign' => 'Bcoinsign',
            'bcoininvited' => 'Bcoininvited',
            'bcoinreward' => 'Bcoinreward',
            'bcoinexchange' => 'Bcoinexchange',
            'bcoinlose' => 'Bcoinlose',
            'bcoinfee' => 'Bcoinfee',
            'icoinwin' => 'Icoinwin',
            'icoinreg' => 'Icoinreg',
            'icoinfirstrecharge' => 'Icoinfirstrecharge',
            'icoinsign' => 'Icoinsign',
            'icoinreward' => 'Icoinreward',
            'icoinexchange' => 'Icoinexchange',
            'icoinlose' => 'Icoinlose',
        ];
    }
}
