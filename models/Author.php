<?php

namespace app\models;

use app\models\base\Author as BaseAuthor;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Author extends BaseAuthor {

    const PHOTO_WIDTH = 200;
    const PHOTO_HEIGHT = 200;

    public $photo_file;
    public $search;

    public static function getUploadUrl() {
        return Yii::getAlias('@web/uploads/authors');
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot/uploads/authors');
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อ-นามสกุล',
            'photo_url' => 'รูป',
            'photo_file' => 'รูป',
            'is_recommended' => 'แนะนำ',
            'is_hide' => 'ซ่อน',
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
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('name', $this->name);
        $query->andFilterCompare('is_recommended', $this->is_recommended);
        $query->andFilterWhere(['LIKE', 'name', ArrayHelper::getValue($this->search, 'text')]);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getPhotoUrl() {
        return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->photo_url . '.jpg';
    }

    public function getThumbUrl() {
        return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->photo_url . '_thumb.jpg';
    }

}
