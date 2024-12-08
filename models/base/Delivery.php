<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property string $name
 * @property int $fee_type
 * @property string $fee
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_enabled
 * @property string $condition
 *
 * @property DeliveryRate[] $deliveryRates
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['fee_type', 'is_enabled'], 'integer'],
            [['fee'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['condition'], 'string'],
            [['name'], 'string', 'max' => 160],
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
            'fee_type' => 'Fee Type',
            'fee' => 'Fee',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_enabled' => 'Is Enabled',
            'condition' => 'Condition',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryRates()
    {
        return $this->hasMany(DeliveryRate::className(), ['delivery_id' => 'id']);
    }
}
