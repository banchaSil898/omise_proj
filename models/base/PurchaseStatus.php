<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "purchase_status".
 *
 * @property int $id
 * @property int $purchase_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property int $is_sendmail
 * @property string $description
 * @property string $parcel_track
 *
 * @property Purchase $purchase
 */
class PurchaseStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_id', 'status'], 'required'],
            [['purchase_id', 'status', 'is_sendmail'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['parcel_track'], 'string', 'max' => 32],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_id' => 'Purchase ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'is_sendmail' => 'Is Sendmail',
            'description' => 'Description',
            'parcel_track' => 'Parcel Track',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }
}
