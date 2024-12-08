<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 * @property string $valid_date
 * @property string $expire_date
 * @property string $discount_value
 * @property int $discount_type
 * @property int $usage_max
 * @property int $usage_current
 * @property int $coupon_group_id
 * @property int $is_single_use
 *
 * @property CouponGroup $couponGroup
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'valid_date', 'expire_date'], 'safe'],
            [['discount_value'], 'number'],
            [['discount_type', 'usage_max', 'usage_current', 'coupon_group_id', 'is_single_use'], 'integer'],
            [['code'], 'string', 'max' => 64],
            [['coupon_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => CouponGroup::className(), 'targetAttribute' => ['coupon_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'valid_date' => 'Valid Date',
            'expire_date' => 'Expire Date',
            'discount_value' => 'Discount Value',
            'discount_type' => 'Discount Type',
            'usage_max' => 'Usage Max',
            'usage_current' => 'Usage Current',
            'coupon_group_id' => 'Coupon Group ID',
            'is_single_use' => 'Is Single Use',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponGroup()
    {
        return $this->hasOne(CouponGroup::className(), ['id' => 'coupon_group_id']);
    }
}
