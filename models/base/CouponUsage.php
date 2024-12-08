<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "coupon_usage".
 *
 * @property int $id
 * @property int $account_id
 * @property int $coupon_id
 * @property int $purchase_id
 * @property string $created_at
 * @property string $updated_at
 */
class CouponUsage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon_usage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'coupon_id', 'purchase_id'], 'required'],
            [['account_id', 'coupon_id', 'purchase_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'coupon_id' => 'Coupon ID',
            'purchase_id' => 'Purchase ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
