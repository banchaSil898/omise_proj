<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "purchase_month".
 *
 * @property string $purchase_date
 * @property string $amount
 * @property int $purchase_count
 * @property string $created_at
 * @property string $updated_at
 */
class PurchaseMonth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_month';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_date'], 'required'],
            [['purchase_date', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
            [['purchase_count'], 'integer'],
            [['purchase_date'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'purchase_date' => 'Purchase Date',
            'amount' => 'Amount',
            'purchase_count' => 'Purchase Count',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
