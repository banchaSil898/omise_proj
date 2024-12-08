<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_gift_item".
 *
 * @property int $promotion_id
 * @property int $gift_id
 * @property string $additional_price
 *
 * @property Gift $gift
 * @property PromotionGift $promotion
 */
class PromotionGiftItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_gift_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'gift_id'], 'required'],
            [['promotion_id', 'gift_id'], 'integer'],
            [['additional_price'], 'number'],
            [['promotion_id', 'gift_id'], 'unique', 'targetAttribute' => ['promotion_id', 'gift_id']],
            [['gift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gift::className(), 'targetAttribute' => ['gift_id' => 'id']],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => PromotionGift::className(), 'targetAttribute' => ['promotion_id' => 'promotion_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'promotion_id' => 'Promotion ID',
            'gift_id' => 'Gift ID',
            'additional_price' => 'Additional Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGift()
    {
        return $this->hasOne(Gift::className(), ['id' => 'gift_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(PromotionGift::className(), ['promotion_id' => 'promotion_id']);
    }
}
