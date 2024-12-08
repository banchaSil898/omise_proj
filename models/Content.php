<?php

namespace app\models;

use app\models\base\Content as BaseContent;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class Content extends BaseContent {

    const TYPE_ARTICLE = 'article';
    const TYPE_NEWS = 'news';

    public $background_file;
    public $search;

    public static function getUploadUrl() {
        return Yii::getAlias('@web/uploads/contents');
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot/uploads/contents');
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'icon' => 'สัญลักษณ์',
            'name' => 'หัวเรื่อง',
            'description' => 'เนื้อหา',
            'brief' => 'เนื้อหา (ย่อ)',
            'is_hide' => 'ซ่อน',
            'url_key' => 'SEO Url',
            'background_color' => 'สีพื้นหลัง',
            'background_url' => 'รูปพื้นหลัง',
            'background_file' => 'รูปพื้นหลัง',
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

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if (!$this->url_key) {
                $this->url_key = $this->name;
            }
            if ($this->isNewRecord) {
                $this->order_no = Yii::$app->db->createCommand('SELECT COALESCE(MAX(order_no),0)+1 as position FROM content WHERE content_type = :content_type')->bindValue(':content_type', $this->content_type)->queryScalar();
            }
            return true;
        }
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['name', 'brief', 'description'], 'required'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterWhere(['content_type' => $this->content_type]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'order_no' => SORT_ASC,
                ],
            ],
        ]);
    }

    public function getBackgroundUrl() {
        if ($this->background_url) {
            return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->background_url . '.jpg';
        }
        return false;
    }

    public function doMoveUp() {
        $target = self::find()
                ->andWhere(['content_type' => $this->content_type])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['<', 'order_no', $this->order_no])
                ->orderBy([
                    'ABS(order_no - :order_no)' => SORT_ASC
                ])
                ->params([
                    ':order_no' => $this->order_no,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->order_no;
            $target->updateAttributes([
                'order_no' => $this->order_no,
            ]);
            $this->updateAttributes([
                'order_no' => $targetPosition,
            ]);
        }
    }

    public function doMoveDown() {
        $target = self::find()
                ->andWhere(['content_type' => $this->content_type])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['>', 'order_no', $this->order_no])
                ->orderBy([
                    'ABS(:order_no - order_no)' => SORT_ASC
                ])
                ->params([
                    ':order_no' => $this->order_no,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->order_no;
            $target->updateAttributes([
                'order_no' => $this->order_no,
            ]);
            $this->updateAttributes([
                'order_no' => $targetPosition,
            ]);
        }
    }

}
