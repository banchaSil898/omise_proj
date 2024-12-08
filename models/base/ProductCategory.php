<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $product_id
 * @property int $category_id
 * @property int $category_item_id
 *
 * @property Product $product
 * @property Category $category
 * @property CategoryItem $categoryItem
 */
class ProductCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id', 'category_item_id'], 'required'],
            [['product_id', 'category_id', 'category_item_id'], 'integer'],
            [['product_id', 'category_id', 'category_item_id'], 'unique', 'targetAttribute' => ['product_id', 'category_id', 'category_item_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['category_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryItem::className(), 'targetAttribute' => ['category_item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
            'category_item_id' => 'Category Item ID',
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
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItem()
    {
        return $this->hasOne(CategoryItem::className(), ['id' => 'category_item_id']);
    }
}
