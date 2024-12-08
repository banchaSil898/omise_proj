<?php

namespace app\models;

use app\models\base\CategoryItem as BaseCategoryItem;

class CategoryItem extends BaseCategoryItem {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อหมวดย่อย',
        ]);
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PromotionCategory::deleteAll([
                'category_item_id' => $this->id,
            ]);
            ProductCategory::deleteAll([
                'category_item_id' => $this->id,
            ]);
            return true;
        }
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getIsDeletable() {
        return true;
    }

}
