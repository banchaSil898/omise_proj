<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "report_sell_daily".
 *
 * @property string $rec_date
 * @property string $rec_money
 */
class ReportSellDaily extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_sell_daily';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rec_date'], 'required'],
            [['rec_date'], 'safe'],
            [['rec_money'], 'number'],
            [['rec_date'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rec_date' => 'Rec Date',
            'rec_money' => 'Rec Money',
        ];
    }
}
