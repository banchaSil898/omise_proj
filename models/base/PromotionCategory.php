<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_category".
 *
 * @property int $promotion_id
 * @property int $category_id
 * @property int $category_item_id
 *
 * @property Promotion $promotion
 * @property Category $category
 * @property CategoryItem $categoryItem
 */
class PromotionCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_id', 'category_id', 'category_item_id'], 'required'],
            [['promotion_id', 'category_id', 'category_item_id'], 'integer'],
            [['promotion_id', 'category_id', 'category_item_id'], 'unique', 'targetAttribute' => ['promotion_id', 'category_id', 'category_item_id']],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotion::className(), 'targetAttribute' => ['promotion_id' => 'id']],
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
            'promotion_id' => 'Promotion ID',
            'category_id' => 'Category ID',
            'category_item_id' => 'Category Item ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
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
