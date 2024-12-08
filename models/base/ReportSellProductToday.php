<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "report_sell_product_today".
 *
 * @property string $rec_date
 * @property int $product_id
 * @property string $rec_amount
 * @property string $rec_count
 */
class ReportSellProductToday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_sell_product_today';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rec_date'], 'safe'],
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['rec_amount', 'rec_count'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rec_date' => 'Rec Date',
            'product_id' => 'Product ID',
            'rec_amount' => 'Rec Amount',
            'rec_count' => 'Rec Count',
        ];
    }
}
