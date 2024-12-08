<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_2".
 *
 * @property int $promotion_id
 * @property string $discount
 * @property string $price
 *
 * @property Promotion $promotion
 */
class Promotion2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id'], 'required'],
            [['promotion_id'], 'integer'],
            [['discount', 'price'], 'number'],
            [['promotion_id'], 'unique'],
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
            'discount' => 'Discount',
            'price' => 'Price',
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
