<?php

namespace app\models;

use app\models\base\PromotionCoupon as BasePromotionCoupon;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class PromotionCoupon extends BasePromotionCoupon {

    public $code_prefix;
    public $code_suffix;
    public $coupon_count;

    public static function find() {
        return Yii::createObject(PromotionCouponQuery::className(), [get_called_class()]);
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'code_prefix' => 'Prefix',
            'code_suffix' => 'Suffix',
            'coupon_count' => 'จำนวนคูปอง',
            'code' => 'รหัสส่วนลด',
            'valid_date' => 'วันที่ใช้งานได้',
            'expire_date' => 'วันหมดอายุ',
            'usage_max' => 'จำนวนที่ใช้งานได้',
            'usage_current' => 'ใช้ไปแล้ว',
            'is_single_use' => 'การใช้สิทธิ',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['promotion_id', 'code', 'valid_date', 'expire_date', 'usage_max', 'usage_current', 'is_single_use'], 'required', 'on' => 'create'];
        $rules[] = [['promotion_id', 'valid_date', 'expire_date', 'usage_max', 'usage_current', 'coupon_count', 'is_single_use'], 'required', 'on' => 'create-multiple'];
        $rules[] = [['coupon_count'], 'number', 'min' => 1, 'max' => 10000, 'on' => 'create-multiple'];
        $rules[] = [['code_prefix', 'code_suffix'], 'safe', 'on' => 'create-multiple'];

        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('promotion_id', $this->promotion_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getPromotion() {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }

    public function getMember() {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    public function getIsUsable() {
        if ($this->promotion) {
            if (isset($this->promotion->promotionProducts)) {
                $items = ArrayHelper::map($this->promotion->promotionProducts, 'product_id', 'product_id');
                $cart = Yii::$app->session->get('cart');
                $count = 0;
                foreach ($cart->items as $id => $item) {
                    if (in_array($id, $items)) {
                        $count += $item['amount'];
                    }
                }
                $manager = $this->promotion->promotionManager;
                if ($count < $manager->buy_amount) {
                    return false;
                }
            }
        }
        if ($this->valid_date && $this->valid_date >= date('Y-m-d H:i:s')) {
            return false;
        }
        if ($this->expire_date && $this->expire_date <= date('Y-m-d H:i:s')) {
            return false;
        }
        if ($this->usage_current >= $this->usage_max) {
            return false;
        }
        if ($this->is_used) {
            return false;
        }
        if (boolval($this->is_single_use)) {
            $used = PromotionCouponUsage::findOne([
                        'promotion_coupon_id' => $this->id,
                        'member_id' => Yii::$app->user->id,
            ]);
            if (isset($used)) {
                return false;
            }
        }
        return true;
    }

    public function createMultiple() {
        if ($this->validate()) {
            $items = [];
            for ($i = 0; $i < $this->coupon_count; $i++) {
                $items[] = [
                    $this->promotion_id,
                    $this->code_prefix . strtoupper(Yii::$app->security->generateRandomString(6)) . $this->code_suffix,
                    $this->valid_date,
                    $this->expire_date,
                    $this->usage_max,
                    $this->usage_current,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    0,
                    $this->is_single_use,
                ];
            }

            return $this->getDb()->createCommand()->batchInsert('promotion_coupon', [
                        'promotion_id',
                        'code',
                        'valid_date',
                        'expire_date',
                        'usage_max',
                        'usage_current',
                        'created_at',
                        'updated_at',
                        'is_used',
                        'is_single_use',
                            ], $items)->execute();
        }
    }

    public function getCouponDetail() {
        $p = $this->promotion->promotionManager;
        $html = [];
        $html[] = ArrayHelper::getValue($p, 'htmlCondition');
        return implode('', $html);
    }

}

class PromotionCouponQuery extends ActiveQuery {
    
}
