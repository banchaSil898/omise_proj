<?php

namespace app\models;

use app\models\base\Gift as BaseGift;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Gift extends BaseGift {

    public $thumb_width = 200;
    public $thumb_height = 200;
    public $cover_width = 500;
    public $cover_height = 500;
    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'image_url' => 'อัพโหลดรูป',
            'name' => 'ชื่อ',
            'description' => 'รายละเอียด',
            'created_at' => 'วันที่เพิ่ม',
            'attr1_name' => 'ชื่อคุณสมบัติ #1',
            'attr1_data' => 'ตัวเลือก #1',
            'attr2_name' => 'ชื่อคุณสมบัติ #2',
            'attr2_data' => 'ตัวเลือก #2',
            'attr3_name' => 'ชื่อคุณสมบัติ #3',
            'attr3_data' => 'ตัวเลือก #3',
            'stock' => 'คงเหลือ',
            'stock_est' => 'เหลือ (รวมจอง)',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PromotionGift::deleteAll([
                'gift_id' => $this->id,
            ]);
            return true;
        }
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if (is_array($this->attr1_data)) {
                $this->attr1_data = implode(',', $this->attr1_data);
            }
            if (is_array($this->attr2_data)) {
                $this->attr2_data = implode(',', $this->attr2_data);
            }
            if (is_array($this->attr3_data)) {
                $this->attr3_data = implode(',', $this->attr3_data);
            }
            return true;
        }
    }

    public function afterFind() {
        parent::afterFind();
        if ($this->attr1_data) {
            $this->attr1_data = explode(',', $this->attr1_data);
        }
        if ($this->attr2_data) {
            $this->attr2_data = explode(',', $this->attr2_data);
        }
        if ($this->attr3_data) {
            $this->attr3_data = explode(',', $this->attr3_data);
        }
    }

    public function getImageUrl() {
        return Yii::getAlias($this->image_url);
    }

    public function getCoverUrl() {
        return Yii::getAlias($this->image_url);
    }

    public function getThumbUrl() {
        return Yii::getAlias($this->thumb_url);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();

        if (isset($this->search['text'])) {
            $words = $this->search['text'];
            $converts = ArrayHelper::map(WordConvert::find()->all(), 'word_from', 'word_to');
            $words = str_replace(array_keys($converts), array_values($converts), $words);
            $query->andWhere(['LIKE', 'name', $words]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
