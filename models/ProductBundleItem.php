<?php

namespace app\models;

use app\models\base\ProductBundleItem as BaseProductBundleItem;

class ProductBundleItem extends BaseProductBundleItem {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'product_id' => 'สินค้า',
        ]);
    }

}
