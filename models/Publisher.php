<?php

namespace app\models;

use app\components\Html;
use app\models\base\Publisher as BasePublisher;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Publisher extends BasePublisher {

    const PHOTO_WIDTH = 200;
    const PHOTO_HEIGHT = 200;

    public $photo_file;
    public $search;

    public static function getUploadUrl() {
        return Yii::getAlias('@web/uploads/publishers');
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot/uploads/publishers');
    }

    public static function find() {
        return Yii::createObject(PublisherQuery::className(), [get_called_class()]);
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อสำนักพิมพ์',
            'photo_url' => 'รูป',
            'photo_file' => 'รูป',
            'is_recommended' => 'แนะนำ',
            'is_hide' => 'ซ่อน',
            'is_own' => 'เครือมติชน',
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
            Product::updateAll([
                'publisher_id' => null,
                'info_publisher' => null,
                'publisher_name' => null,
                    ], [
                'publisher_id' => $this->id,
            ]);
            return true;
        }
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (isset($changedAttributes['is_own'])) {
            Product::updateAll([
                'is_own' => $this->is_own,
                'publisher_name' => $this->name,
                'info_publisher' => $this->name,
                    ], [
                'publisher_id' => $this->id,
            ]);
        }
    }

    public function search() {
        $query = $this->find();
        $query->andFilterWhere(['LIKE', 'name', ArrayHelper::getValue($this->search, 'text')]);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getPhotoUrl() {
        if ($this->photo_url) {
            return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->photo_url . '.jpg';
        } else {
            return Html::placeholder(self::PHOTO_WIDTH, self::PHOTO_HEIGHT);
        }
    }

    public function getThumbUrl() {
        if ($this->photo_url) {
            return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->photo_url . '_thumb.jpg';
        } else {
            return Html::placeholder(48, 48, 'png');
        }
    }

    public function getProducts() {
        return $this->hasMany(Product::className(), ['publisher_id' => 'id']);
    }

}

class PublisherQuery extends ActiveQuery {

    public function scopeDefault() {
        $this->andWhere(['is_hide' => false]);
        return $this;
    }

}
