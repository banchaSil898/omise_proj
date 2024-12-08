<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "report_sell_folder_daily".
 *
 * @property string $rec_date
 * @property int $product_id
 * @property string $rec_amount
 * @property int $rec_count
 */
class ReportSellFolderDaily extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_sell_folder_daily';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rec_date', 'product_id'], 'required'],
            [['rec_date'], 'safe'],
            [['product_id', 'rec_count'], 'integer'],
            [['rec_amount'], 'number'],
            [['rec_date', 'product_id'], 'unique', 'targetAttribute' => ['rec_date', 'product_id']],
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
