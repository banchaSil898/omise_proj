<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "coupon_group".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $valid_date
 * @property string $expire_date
 * @property string $discount_value
 * @property int $discount_type
 * @property int $usage_max
 * @property int $coupon_count
 * @property string $code_prefix
 * @property string $code_suffix
 *
 * @property Coupon[] $coupons
 */
class CouponGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'discount_type', 'usage_max', 'coupon_count'], 'integer'],
            [['created_at', 'updated_at', 'valid_date', 'expire_date'], 'safe'],
            [['discount_value'], 'number'],
            [['name'], 'string', 'max' => 160],
            [['code_prefix', 'code_suffix'], 'string', 'max' => 60],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'valid_date' => 'Valid Date',
            'expire_date' => 'Expire Date',
            'discount_value' => 'Discount Value',
            'discount_type' => 'Discount Type',
            'usage_max' => 'Usage Max',
            'coupon_count' => 'Coupon Count',
            'code_prefix' => 'Code Prefix',
            'code_suffix' => 'Code Suffix',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::className(), ['coupon_group_id' => 'id']);
    }
}
