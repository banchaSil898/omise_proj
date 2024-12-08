<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_gift".
 *
 * @property int $promotion_id
 * @property int $gift_id
 * @property string $buy_rate
 *
 * @property Gift $gift
 * @property Promotion $promotion
 */
class PromotionGift extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_gift';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'gift_id'], 'required'],
            [['promotion_id', 'gift_id'], 'integer'],
            [['buy_rate'], 'number'],
            [['promotion_id', 'gift_id'], 'unique', 'targetAttribute' => ['promotion_id', 'gift_id']],
            [['gift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gift::className(), 'targetAttribute' => ['gift_id' => 'id']],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotion::className(), 'targetAttribute' => ['promotion_id' => 'id']],
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
            'buy_rate' => 'Buy Rate',
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
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }
}
