<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_bundle_item".
 *
 * @property int $bundle_id
 * @property int $product_id
 *
 * @property Product $bundle
 * @property Product $product
 */
class ProductBundleItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_bundle_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bundle_id', 'product_id'], 'required'],
            [['bundle_id', 'product_id'], 'integer'],
            [['bundle_id', 'product_id'], 'unique', 'targetAttribute' => ['bundle_id', 'product_id']],
            [['bundle_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['bundle_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bundle_id' => 'Bundle ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBundle()
    {
        return $this->hasOne(Product::className(), ['id' => 'bundle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
