<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "purchase_product".
 *
 * @property int $purchase_id
 * @property int $product_id
 * @property int $amount จำนวนสินค้าที่สั่ง
 * @property string $price ราคาต่อหน่วย
 * @property string $price_total ราคารวม
 * @property string $weight น้ำหนัก
 * @property string $weight_total น้ำหนักรวม
 * @property string $sku
 * @property string $name
 * @property int $product_magento_id
 * @property int $purchase_magento_id
 * @property int $item_magento_id
 * @property int $addon_id
 * @property string $addon_name
 * @property string $price_addon
 *
 * @property Product $product
 * @property Purchase $purchase
 */
class PurchaseProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_id', 'product_id'], 'required'],
            [['purchase_id', 'product_id', 'amount', 'product_magento_id', 'purchase_magento_id', 'item_magento_id', 'addon_id'], 'integer'],
            [['price', 'price_total', 'weight', 'weight_total', 'price_addon'], 'number'],
            [['sku'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 200],
            [['addon_name'], 'string', 'max' => 128],
            [['purchase_id', 'product_id'], 'unique', 'targetAttribute' => ['purchase_id', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'purchase_id' => 'Purchase ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'price' => 'Price',
            'price_total' => 'Price Total',
            'weight' => 'Weight',
            'weight_total' => 'Weight Total',
            'sku' => 'Sku',
            'name' => 'Name',
            'product_magento_id' => 'Product Magento ID',
            'purchase_magento_id' => 'Purchase Magento ID',
            'item_magento_id' => 'Item Magento ID',
            'addon_id' => 'Addon ID',
            'addon_name' => 'Addon Name',
            'price_addon' => 'Price Addon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }
}
