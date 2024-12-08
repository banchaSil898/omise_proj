<?php

namespace app\models;

use app\models\base\ProductFolder as BaseProductFolder;
use yii\data\ActiveDataProvider;

class ProductFolder extends BaseProductFolder {

    public $search;

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
