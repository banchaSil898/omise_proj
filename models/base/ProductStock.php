<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_stock".
 *
 * @property int $id
 * @property int $amount
 * @property string $base_price
 * @property string $created_at
 * @property string $updated_at
 * @property int $product_id
 * @property int $amount_old
 * @property int $amount_new
 * @property string $description
 *
 * @property Product $product
 */
class ProductStock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'product_id', 'amount_old', 'amount_new'], 'required'],
            [['amount', 'product_id', 'amount_old', 'amount_new'], 'integer'],
            [['base_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'base_price' => 'Base Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'product_id' => 'Product ID',
            'amount_old' => 'Amount Old',
            'amount_new' => 'Amount New',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
