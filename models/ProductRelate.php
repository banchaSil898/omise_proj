<?php

namespace app\models;

use app\models\base\ProductRelate as BaseProductRelate;

class ProductRelate extends BaseProductRelate {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'relate_id' => 'สินค้าที่เกี่ยวข้อง',
        ]);
    }

}
