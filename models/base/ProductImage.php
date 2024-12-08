<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property int $product_id
 * @property int $image_id
 * @property string $thumb_url
 * @property string $img_url
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_no
 *
 * @property Product $product
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'image_id'], 'required'],
            [['product_id', 'image_id', 'order_no'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['thumb_url', 'img_url'], 'string', 'max' => 200],
            [['product_id', 'image_id'], 'unique', 'targetAttribute' => ['product_id', 'image_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'image_id' => 'Image ID',
            'thumb_url' => 'Thumb Url',
            'img_url' => 'Img Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order_no' => 'Order No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
