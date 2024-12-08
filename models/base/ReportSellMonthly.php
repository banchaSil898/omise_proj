<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "report_sell_monthly".
 *
 * @property string $rec_date
 * @property string $rec_money
 */
class ReportSellMonthly extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_sell_monthly';
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
