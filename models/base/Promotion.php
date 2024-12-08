<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order_no
 * @property int $promotion_type
 * @property string $promotion_value
 * @property string $data
 * @property int $is_once
 * @property int $is_final
 *
 * @property PromotionBuffet $promotionBuffet
 * @property PromotionCoupon[] $promotionCoupons
 * @property PromotionGift[] $promotionGifts
 * @property Gift[] $gifts
 * @property PromotionItem[] $promotionItems
 * @property Product[] $products
 * @property PromotionProduct[] $promotionProducts
 * @property Product[] $products0
 */
class Promotion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'order_no', 'promotion_type', 'is_once', 'is_final'], 'integer'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 200],
            [['promotion_value'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'order_no' => 'Order No',
            'promotion_type' => 'Promotion Type',
            'promotion_value' => 'Promotion Value',
            'data' => 'Data',
            'is_once' => 'Is Once',
            'is_final' => 'Is Final',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionBuffet()
    {
        return $this->hasOne(PromotionBuffet::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionCoupons()
    {
        return $this->hasMany(PromotionCoupon::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionGifts()
    {
        return $this->hasMany(PromotionGift::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGifts()
    {
        return $this->hasMany(Gift::className(), ['id' => 'gift_id'])->viaTable('promotion_gift', ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionItems()
    {
        return $this->hasMany(PromotionItem::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('promotion_item', ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionProducts()
    {
        return $this->hasMany(PromotionProduct::className(), ['promotion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts0()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('promotion_product', ['promotion_id' => 'id']);
    }
}
