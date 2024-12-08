<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_coupon".
 *
 * @property int $id
 * @property int $promotion_id
 * @property string $code
 * @property string $valid_date
 * @property string $expire_date
 * @property int $usage_max
 * @property int $usage_current
 * @property int $member_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_used
 * @property int $is_single_use
 *
 * @property Promotion $promotion
 */
class PromotionCoupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_coupon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id'], 'required'],
            [['promotion_id', 'usage_max', 'usage_current', 'member_id', 'is_used', 'is_single_use'], 'integer'],
            [['valid_date', 'expire_date', 'created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 64],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotion::className(), 'targetAttribute' => ['promotion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promotion_id' => 'Promotion ID',
            'code' => 'Code',
            'valid_date' => 'Valid Date',
            'expire_date' => 'Expire Date',
            'usage_max' => 'Usage Max',
            'usage_current' => 'Usage Current',
            'member_id' => 'Member ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_used' => 'Is Used',
            'is_single_use' => 'Is Single Use',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }
}
