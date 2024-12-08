<?php

namespace app\models;

use app\components\Html;
use app\models\base\Slide as BaseSlide;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Slide extends BaseSlide {

    const TYPE_LINK = 0;
    const TYPE_HTML = 1;
    const TYPE_PRODUCT = 2;
    const TYPE_BUNDLE = 3;

    public $image_file;
    public $search;

    public static function getSlideTypeOptions($code = null) {
        $ret = [
            self::TYPE_LINK => 'ลิงค์เชื่อมโยง',
            self::TYPE_HTML => 'แสดงหน้า HTML',
            self::TYPE_PRODUCT => 'แสดงสินค้า',
            self::TYPE_BUNDLE => 'แสดงชุดสินค้า',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'url' => 'URL',
            'image_url' => 'รูปพื้นหลัง',
            'image_file' => 'รูปพื้นหลัง',
            'slide_type' => 'ประเภท',
            'slideType' => 'ประเภท',
            'description' => 'รายละเอียด',
            'html' => 'HTML',
            'product_id' => 'สินค้า',
            'name' => 'หัวข้อ',
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
            if ($this->isNewRecord) {
                $this->order_no = Yii::$app->db->createCommand('SELECT COALESCE(MAX(order_no),0)+1 as position FROM slide')->queryScalar();
            }
            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            SlideProduct::deleteAll([
                'slide_id' => $this->id,
            ]);
            return true;
        }
    }

    public function getImageUrl() {
        if ($this->image_url) {
            return Yii::getAlias('@web/uploads/slides') . DIRECTORY_SEPARATOR . $this->image_url . '.jpg';
        }
        return 'https://placehold.it/1920x450.jpg';
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['html', 'name'], 'required', 'on' => 'case-bundle'];
        $rules[] = [['product_id'], 'required', 'on' => 'case-product'];
        $rules[] = [['html'], 'required', 'on' => 'case-html'];
        $rules[] = [['url'], 'required', 'on' => 'case-link'];
        $rules[] = [['search'], 'safe', 'on' => 'search'];
        $rules[] = [['image_file', 'image_url'], 'safe', 'on' => ['case-link']];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'order_no' => SORT_ASC,
                ],
            ],
        ]);
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getSlideType() {
        return self::getSlideTypeOptions($this->slide_type);
    }

    public function getDescription() {
        switch ($this->slide_type) {
            case self::TYPE_BUNDLE:
                return implode(', ', ArrayHelper::map($this->getSlideProducts()->all(), 'product_id', 'product.name'));
            case self::TYPE_PRODUCT:
                return Html::encode(ArrayHelper::getValue($this->product, 'name'));
            case self::TYPE_HTML:
                return 'เอกสาร HTML';
            default:
                return Html::encode($this->url);
        }
    }

    public function productAdd($items) {
        $keys = array_keys($this->getSlideProducts()->asArray()->indexBy('product_id')->all());
        $items = array_diff($items, $keys);

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [$this->id, $item];
        }
        $count = $this->getDb()->createCommand()->batchInsert('slide_product', ['slide_id', 'product_id'], $rows)->execute();
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function productRemove($items) {
        $count = SlideProduct::deleteAll([
                    'product_id' => $items,
        ]);
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function doMoveUp() {
        $target = Slide::find()
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
        $target = Slide::find()
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
