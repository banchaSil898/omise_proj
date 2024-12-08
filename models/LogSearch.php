<?php

namespace app\models;

use app\models\base\LogSearch as BaseLogSearch;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class LogSearch extends BaseLogSearch {

    public $search;

    public static function doSaveLog($text, $count = 0, $user_id = null) {
        $text = trim($text);
        if ($text) {
            $log = new LogSearch;
            $log->keyword = $text;
            $log->result_count = $count;
            $log->member_id = $user_id;

            $stat = LogSearchKeyword::findOne([
                        'keyword' => $text,
            ]);
            if (!isset($stat)) {
                $stat = new LogSearchKeyword;
                $stat->keyword = $text;
                $stat->result_time = 0;
            }
            $stat->result_count = $count;
            $stat->result_time++;
            if ($stat->save()) {
                $log->result_time = $stat->result_time;
                return $log->save();
            }
        }
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'keyword' => 'คำค้น',
            'result_count' => 'ผลลัพธ์',
            'result_time' => 'จำนวนที่ใช้',
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

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['search'], 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
