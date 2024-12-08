<?php

namespace app\components;

use app\components\promotion\PromotionCartCouponManager;
use app\models\Configuration;
use app\models\Country;
use app\models\DeliveryRate;
use app\models\Product;
use app\models\ProductAddon;
use app\models\Promotion;
use app\models\PromotionCoupon;
use app\models\Purchase;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Cart extends Model {

    public $delivery_country;
    public $delivery_method;
    public $coupon;
    private $_items = [];
    private $_total = 0;
    private $_weight = 0;
    private $_deliveryWeight = 0;
    private $_promotionSummary;
    private $_gifts = [];
    private $_products = [];

    public function hasProducts($ids) {
        $items = $this->getItems();
        foreach ($items as $id => $item) {
            if (in_array($id, $ids)) {
                return true;
            }
        }
        return false;
    }

    public function getPromotionSummary(Purchase $purchase = null) {
        $this->_gifts = [];
        $this->_promotionSummary = [
            'info' => [],
            'discount' => 0,
            'gifts' => [],
            'products' => [],
        ];
        $initPrice = $this->getTotal();
        $once = [];
        $final = false;
        $promotions = Promotion::find()->active()->afterCart()->orderBy(['order_no' => SORT_ASC])->all();
        foreach ($promotions as $promotion) {
            $manager = $promotion->getPromotionManager();

            if ($final) {
                continue;
            }

            if (in_array(get_class($manager), $once)) {
                continue;
            }

            $manager->loadCart($this);
            if ($manager->isValid($this, $initPrice)) {
                $price = $manager->getDiscount($initPrice);
                $info = [
                    'id' => $promotion->id,
                    'name' => $manager->getPromotionName(),
                    'detail' => $manager->getPromotionDetail(),
                    'discount' => $price,
                    'showOnCart' => $manager->showOnCart,
                    'attributes' => $manager->attributes,
                ];

                if (method_exists($manager, 'getAvailableProducts')) {
                    $products = $manager->getAvailableProducts();
                    if (isset($products)) {
                        $info['products'] = $products;
                    }
                }

                $this->_promotionSummary['info'][] = $info;
                $this->_promotionSummary['discount'] += $price;

                if (method_exists($manager, 'getAvailableGifts')) {
                    $gifts = $manager->getAvailableGifts();
                    if (isset($gifts)) {
                        $this->_promotionSummary['gifts'] = $gifts;
                    }
                }



                $initPrice = $initPrice - $price;
                if (isset($purchase)) {
                    $manager->afterCheckout($purchase, $price);
                }

                if ($promotion->is_once) {
                    $once[] = get_class($manager);
                }

                if ($promotion->is_final) {
                    $final = true;
                }
            }
        }
        return $this->_promotionSummary;
    }

    public function getDiscountTotal() {
        if (!isset($this->_promotionSummary)) {
            $this->getPromotionSummary();
        }
        return ArrayHelper::getValue($this->_promotionSummary, 'discount');
    }

    public function getItems() {
        return $this->_items;
    }

    public function getProducts() {
        return $this->_products;
    }

    public function getGifts() {
        return $this->_gifts;
    }

    public function getDeliveryOptions() {
        $ret = [];

        $free = Configuration::getValue('delivery_free');
        if ($this->total >= $free) {
            $ret[Purchase::DELIVERY_FREE] = Purchase::getDeliveryOptions(Purchase::DELIVERY_FREE);
        } else {
            $ret[Purchase::DELIVERY_REGISTER] = Purchase::getDeliveryOptions(Purchase::DELIVERY_REGISTER);
            $ret[Purchase::DELIVERY_ALPHA] = Purchase::getDeliveryOptions(Purchase::DELIVERY_ALPHA);
        }
        if ($this->getDeliveryWeight()) {
            $ret[Purchase::DELIVERY_EMS] = Purchase::getDeliveryOptions(Purchase::DELIVERY_EMS);
            $ret[Purchase::DELIVERY_AIRMAIL] = Purchase::getDeliveryOptions(Purchase::DELIVERY_AIRMAIL);
        }
        return $ret;
    }

    public function getDeliveryWithFeeOptions() {
        $ret = [];

        $free = Configuration::getValue('delivery_free');
        if ($this->total >= $free) {
            $ret[Purchase::DELIVERY_FREE] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_FREE);
        } else {
            if ($this->isDeliveryFree) {
                $ret[Purchase::DELIVERY_FREE] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_FREE);
            } else {
                $ret[Purchase::DELIVERY_REGISTER] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_REGISTER);
            }
            #$ret[Purchase::DELIVERY_ALPHA] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_ALPHA);
        }
        if ($this->getDeliveryWeight()) {
            $ret[Purchase::DELIVERY_EMS] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_EMS);
            $ret[Purchase::DELIVERY_CUSTOM] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_CUSTOM);
            $ret[Purchase::DELIVERY_AIRMAIL] = Purchase::getDeliveryWithFeeOptions(Purchase::DELIVERY_AIRMAIL);
        }
        return $ret;
    }

    public function getIsDeliveryFree() {
        $items = $this->getItems();
        foreach ($items as $item) {
            if (!boolval($item['model']->free_shipping)) {
                return false;
            }
        }
        return true;
    }

    public function getHasDeliveryStd() {
        $items = $this->getItems();
        foreach ($items as $item) {
            if ($item['model']->isDeliveryStd) {
                return true;
            }
        }
        return false;
    }

    public function getIsOnlyDeliveryStd() {
        $count = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            if (!$item['model']->isDeliveryStd) {
                return false;
            } else {
                $count++;
            }
        }
        return $count > 0;
    }

    public function getDeliveryOptionsByPurchase($purchase) {
        $ret = $this->getDeliveryWithFeeOptions();
        if ($purchase->delivery_country <> 'TH') {
            return [Purchase::DELIVERY_AIRMAIL => $ret[Purchase::DELIVERY_AIRMAIL]];
        } else {
            if (isset($ret[Purchase::DELIVERY_AIRMAIL])) {
                unset($ret[Purchase::DELIVERY_AIRMAIL]);
            }
        }

        if (!boolval(Configuration::getValue('delivery_airmail_enabled', false))) {
            if (isset($ret[Purchase::DELIVERY_AIRMAIL])) {
                unset($ret[Purchase::DELIVERY_AIRMAIL]);
            }
        }

        if (!boolval(Configuration::getValue('delivery_alpha_enabled', false))) {
            if (isset($ret[Purchase::DELIVERY_ALPHA])) {
                unset($ret[Purchase::DELIVERY_ALPHA]);
            }
        }

        if (!boolval(Configuration::getValue('delivery_register_enabled', false))) {
            if (isset($ret[Purchase::DELIVERY_REGISTER])) {
                unset($ret[Purchase::DELIVERY_REGISTER]);
            }
        }

        $ems = intval(Configuration::getValue('delivery_ems_enabled', 0));
        if (!$ems) {
            if (isset($ret[Purchase::DELIVERY_EMS])) {
                unset($ret[Purchase::DELIVERY_EMS]);
            }
        }

        $custom = intval(Configuration::getValue('delivery_custom_enabled', 0));
        if (!$custom) {
            if (isset($ret[Purchase::DELIVERY_CUSTOM])) {
                unset($ret[Purchase::DELIVERY_CUSTOM]);
            }
        }
        return $ret;
    }

    public function getDeliveryTotal() {

        $isFree = true;
        foreach ($this->items as $item) {
            if (!$item['model']->free_shipping) {
                $isFree = false;
            }
        }
        if (!in_array($this->delivery_method, [Purchase::DELIVERY_EMS, Purchase::DELIVERY_CUSTOM])) {
            if ($isFree) {
                return 0;
            }
        }

        /* Check Promotion */
        $isPromotion = false;
        $promotion = Promotion::find()->active()->andWhere(['promotion_type' => Promotion::TYPE_FREE_DELIVERY])->one();
        if (isset($promotion)) {
            $isPromotion = true;
            $manager = $promotion->getPromotionManager();
            $manager->loadCart($this);
            if (!$manager->isValid()) {
                $isPromotion = false;
            }
        }

        switch ($this->delivery_method) {
            case Purchase::DELIVERY_FREE:
                return 0;
            case Purchase::DELIVERY_REGISTER:
                if ($isPromotion) {
                    return 0;
                }
                return Configuration::getValue('delivery_thaipost');
            case Purchase::DELIVERY_ALPHA:
                if ($isPromotion) {
                    return 0;
                }
                return Configuration::getValue('delivery_alpha');
            case Purchase::DELIVERY_EMS:
                if ($this->getDeliveryWeight() <= 0) {
                    return 0;
                }
                $rate = DeliveryRate::find()
                        ->where(['>=', 'weight', $this->getDeliveryWeight()])
                        ->andWhere(['delivery_id' => Purchase::DELIVERY_EMS])
                        ->orderBy([
                            'weight' => SORT_ASC
                        ])
                        ->limit(1)
                        ->one();
                if (isset($rate)) {
                    return $rate->fee;
                }
                break;
            case Purchase::DELIVERY_CUSTOM:
                if ($this->getDeliveryWeight() <= 0) {
                    return 0;
                }
                $rate = DeliveryRate::find()
                        ->where(['>=', 'weight', $this->getDeliveryWeight()])
                        ->andWhere(['delivery_id' => Purchase::DELIVERY_CUSTOM])
                        ->orderBy([
                            'weight' => SORT_ASC
                        ])
                        ->limit(1)
                        ->one();
                if (isset($rate)) {
                    return $rate->fee;
                }
                break;
            case Purchase::DELIVERY_AIRMAIL:
                if (isset($this->delivery_country)) {
                    $country = Country::findOne([
                                'country_code' => $this->delivery_country,
                    ]);
                    if ($this->getDeliveryWeight() <= 0) {
                        return 0;
                    }
                    $rate = DeliveryRate::find()
                            ->where(['>=', 'weight', $this->weight])
                            ->andWhere(['country_id' => $country->iso_alpha3])
                            ->andWhere(['delivery_id' => Purchase::DELIVERY_AIRMAIL])
                            ->orderBy([
                                'weight' => SORT_ASC
                            ])
                            ->limit(1)
                            ->one();
                    if (!isset($rate)) {
                        $rate = DeliveryRate::find()
                                ->where(['>=', 'weight', $this->weight])
                                ->andWhere(['country_id' => '*'])
                                ->andWhere(['delivery_id' => Purchase::DELIVERY_AIRMAIL])
                                ->orderBy([
                                    'weight' => SORT_ASC
                                ])
                                ->limit(1)
                                ->one();
                    }
                    if (isset($rate)) {
                        return $rate->fee;
                    }
                }
                break;
        }
        return 0;
    }

    public function getWeight() {
        return $this->_weight;
    }

    public function getDeliveryWeight() {
        return $this->_deliveryWeight;
    }

    public function getTotal() {
        return $this->_total;
    }

    public function getTotalAfterDiscount() {
        return $this->_total - $this->discountTotal;
    }

    public function getGrandTotal() {
        return ($this->totalAfterDiscount + $this->deliveryTotal);
    }

    public function getItemsCount() {
        return count($this->_items);
    }

    public function getProductsCountByPromotion(Promotion $promotion) {
        $count = 0;
        if (isset($this->_products[$promotion->id])) {
            foreach ($this->_products[$promotion->id] as $item) {
                $count += $item['amount'];
            }
        }
        return $count;
    }

    public function addProductByPromotion(Product $product, Promotion $promotion) {
        if (!isset($this->_products[$promotion->id][$product->id])) {
            $this->_products[$promotion->id][$product->id] = [
                'model' => $product,
                'price' => $product->currentPrice,
                'amount' => 1,
                'weight' => $product->info_weight,
                'total' => $product->currentPrice,
                'totalWeight' => $product->info_weight,
                'totalDeliveryWeight' => $product->info_weight,
            ];
        } else {
            $this->_products[$promotion->id][$product->id]['amount'] ++;
            $this->_products[$promotion->id][$product->id]['total'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['price'];
            $this->_products[$promotion->id][$product->id]['totalWeight'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['weight'];
            $this->_products[$promotion->id][$product->id]['totalDeliveryWeight'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['weight'];
        }
        return $this->updateCart();
    }

    public function removeProductByPromotion(Product $product, Promotion $promotion) {
        if (isset($this->_products[$promotion->id][$product->id])) {
            $this->_products[$promotion->id][$product->id]['amount'] --;
            $this->_products[$promotion->id][$product->id]['total'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['price'];
            $this->_products[$promotion->id][$product->id]['totalWeight'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['weight'];
            $this->_products[$promotion->id][$product->id]['totalDeliveryWeight'] = $this->_products[$promotion->id][$product->id]['amount'] * $this->_products[$promotion->id][$product->id]['weight'];
        }
        if ($this->_products[$promotion->id][$product->id]['amount'] <= 0) {
            unset($this->_products[$promotion->id][$product->id]);
        }
        return $this->updateCart();
    }

    public function clearProductByPromotion(Product $product, Promotion $promotion) {
        if (isset($this->_products[$promotion->id][$product->id])) {
            unset($this->_products[$promotion->id][$product->id]);
        }
        return $this->updateCart();
    }

    public function addItem(Product $product, $addon_id = null) {
        if (isset($addon_id)) {
            $addon = ProductAddon::findOne([
                        'id' => $addon_id,
                        'product_id' => $product->id,
            ]);
        } else {
            $addon = null;
        }
        if (!isset($this->_items[$product->id])) {
            $price = $product->currentPrice;
            if (isset($addon)) {
                $price += $addon->price;
            }
            $this->_items[$product->id] = [
                'model' => $product,
                'addon' => $addon,
                'price' => $price,
                'amount' => 1,
                'weight' => $product->info_weight,
                'total' => $price,
                'totalWeight' => $product->info_weight,
                #'totalDeliveryWeight' => $product->free_shipping ? 0 : $product->info_weight,
                'totalDeliveryWeight' => $product->info_weight,
            ];
        } else {

            $this->_items[$product->id]['addon'];

            $this->_items[$product->id]['amount'] ++;
            $this->_items[$product->id]['total'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['price'];
            $this->_items[$product->id]['totalWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            #$this->_items[$product->id]['totalDeliveryWeight'] = $product->free_shipping ? 0 : $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            $this->_items[$product->id]['totalDeliveryWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
        }
        return $this->updateCart();
    }

    public function setItem(Product $product, $qty = 0) {
        if (!isset($this->_items[$product->id])) {
            $this->_items[$product->id] = [
                'model' => $product,
                'price' => $product->currentPrice,
                'amount' => $qty,
                'weight' => $product->info_weight,
                'total' => $qty * $product->currentPrice,
                'totalWeight' => $qty * $product->info_weight,
#               $'totalDeliveryWeight' => $product->free_shipping ? 0 : $qty * $product->info_weight,
                'totalDeliveryWeight' => $qty * $product->info_weight,
            ];
        } else {
            $this->_items[$product->id]['amount'] = $qty;
            $this->_items[$product->id]['total'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['price'];
            $this->_items[$product->id]['totalWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            #$this->_items[$product->id]['totalDeliveryWeight'] = $product->free_shipping ? 0 : $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            $this->_items[$product->id]['totalDeliveryWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
        }
        return $this->updateCart();
    }

    public function clearItem(Product $product) {
        if (isset($this->_items[$product->id])) {
            unset($this->_items[$product->id]);
        }
        return $this->updateCart();
    }

    public function removeItem(Product $product) {
        if (isset($this->_items[$product->id])) {
            $this->_items[$product->id]['amount'] --;
            $this->_items[$product->id]['total'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['price'];
            $this->_items[$product->id]['totalWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            #$this->_items[$product->id]['totalDeliveryWeight'] = $product->free_shipping ? 0 : $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
            $this->_items[$product->id]['totalDeliveryWeight'] = $this->_items[$product->id]['amount'] * $this->_items[$product->id]['weight'];
        }
        if ($this->_items[$product->id]['amount'] <= 0) {
            unset($this->_items[$product->id]);
        }
        return $this->updateCart();
    }

    public function clear() {
        Yii::$app->session->set('cart', new Cart);
    }

    public function updateCart() {
        $this->_total = 0;
        $this->_weight = 0;
        $this->_deliveryWeight = 0;
        foreach ($this->_items as $item) {
            $this->_total += $item['total'];
            $this->_weight += $item['totalWeight'];
            $this->_deliveryWeight += $item['totalDeliveryWeight'];
        }
        Yii::$app->session->set('cart', $this);
    }

    public function applyCoupon($code) {
        $code = trim($code);

        /* Coupon สำหรับ ส่วนลด */
        $promotions = Promotion::find()->active()->afterCart()->orderBy(['order_no' => SORT_ASC])->all();
        /* @var $promotion Promotion */
        /* @var $manager PromotionCartCouponManager */
        foreach ($promotions as $promotion) {
            if (!in_array($promotion->promotion_type, [
                        Promotion::TYPE_PRICE_GET_COUPON_P,
                        Promotion::TYPE_PRICE_GET_COUPON,
                    ])) {
                continue;
            }
            $manager = $promotion->getPromotionManager();
            if ($manager->coupon_code === $code) {
                $coupon = new PromotionCoupon;
                $coupon->promotion_id = $promotion->id;
                $coupon->code = $manager->coupon_code;
                $coupon->valid_date = date('Y-m-d 00:00:00');
                $coupon->expire_date = date('Y-m-d 23:59:59');
                $coupon->usage_max = 1;
                $coupon->usage_current = 0;
                $coupon->member_id = ArrayHelper::getValue(Yii::$app->user, 'id');
                $coupon->created_at = date('Y-m-d H:i:s');
                $coupon->updated_at = date('Y-m-d H:i:s');
                $coupon->is_used = 0;
                $coupon->is_single_use = 1;
                $coupon->save();
                $this->coupon = $coupon;
                return true;
            }
        }

        /* Coupon ชุด สำหรับโปรโมชั่น */
        $coupon = PromotionCoupon::findOne([
                    'code' => $code,
        ]);
        if (isset($coupon) && $coupon->isUsable) {
            $this->coupon = $coupon;
            return true;
        } else {
            $this->coupon = null;
            return false;
        }
    }

    public function removeCoupon() {
        $this->coupon = null;
    }

}
