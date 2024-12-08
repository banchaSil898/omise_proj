<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_addon".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $price
 */
class ProductAddon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_addon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }
}
