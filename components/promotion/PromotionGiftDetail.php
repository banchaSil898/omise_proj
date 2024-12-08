<?php

namespace app\components\promotion;

use yii\base\Model;

class PromotionGiftDetail extends Model {

    public $id;
    public $name;
    public $attr1_name;
    public $attr1_data;
    public $attr1_options;
    public $attr2_name;
    public $attr2_data;
    public $attr2_options;
    public $attr3_name;
    public $attr3_data;
    public $attr3_options;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ของที่ระลึก',
            'attr1_data' => $this->attr1_name,
            'attr2_data' => $this->attr2_name,
            'attr3_data' => $this->attr3_name,
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['id', 'name', 'attr1_name', 'attr2_name', 'attr3_name', 'attr1_data', 'attr2_data', 'attr3_data'], 'safe'];
        return $rules;
    }

}
