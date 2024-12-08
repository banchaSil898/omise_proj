<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_no
 *
 * @property CategoryItem[] $categoryItems
 * @property ProductCategory[] $productCategories
 * @property PromotionCategory[] $promotionCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_no'], 'integer'],
            [['name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order_no' => 'Order No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryItems()
    {
        return $this->hasMany(CategoryItem::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionCategories()
    {
        return $this->hasMany(PromotionCategory::className(), ['category_id' => 'id']);
    }
}
