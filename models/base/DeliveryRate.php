<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "delivery_rate".
 *
 * @property int $delivery_id
 * @property string $country_id
 * @property string $weight
 * @property string $fee
 *
 * @property Delivery $delivery
 */
class DeliveryRate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_id', 'country_id', 'weight', 'fee'], 'required'],
            [['delivery_id'], 'integer'],
            [['weight', 'fee'], 'number'],
            [['country_id'], 'string', 'max' => 3],
            [['delivery_id', 'country_id', 'weight'], 'unique', 'targetAttribute' => ['delivery_id', 'country_id', 'weight']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::className(), 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'country_id' => 'Country ID',
            'weight' => 'Weight',
            'fee' => 'Fee',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }
}
