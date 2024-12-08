<?php

namespace app\models;

use app\models\base\Category as BaseCategory;

class Category extends BaseCategory {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อหมวด',
        ]);
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PromotionCategory::deleteAll([
                'category_id' => $this->id,
            ]);
            ProductCategory::deleteAll([
                'category_id' => $this->id,
            ]);
            CategoryItem::deleteAll([
                'category_id' => $this->id,
            ]);
            return true;
        }
    }

    public function getCategoryItems() {
        return $this->hasMany(CategoryItem::className(), ['category_id' => 'id']);
    }

    public function getIsDeletable() {
        return true;
    }

}
