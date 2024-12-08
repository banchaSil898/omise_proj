<?php

namespace app\models;

use app\models\base\PromotionFolder as BasePromotionFolder;
use yii\data\ActiveDataProvider;

class PromotionFolder extends BasePromotionFolder {

    public $search;

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
