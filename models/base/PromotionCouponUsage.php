<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_coupon_usage".
 *
 * @property int $id
 * @property int $promotion_coupon_id
 * @property int $purchase_id
 * @property int $promotion_id
 * @property int $member_id
 * @property string $discount_amount
 * @property string $created_at
 * @property string $updated_at
 */
class PromotionCouponUsage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_coupon_usage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_coupon_id', 'purchase_id', 'promotion_id', 'member_id'], 'required'],
            [['promotion_coupon_id', 'purchase_id', 'promotion_id', 'member_id'], 'integer'],
            [['discount_amount'], 'number'],
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
            'promotion_coupon_id' => 'Promotion Coupon ID',
            'purchase_id' => 'Purchase ID',
            'promotion_id' => 'Promotion ID',
            'member_id' => 'Member ID',
            'discount_amount' => 'Discount Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
