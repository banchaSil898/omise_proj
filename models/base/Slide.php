<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "slide".
 *
 * @property int $id
 * @property int $slide_type
 * @property int $product_id
 * @property string $image_url
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_no
 * @property string $html
 * @property string $layout
 * @property string $name
 *
 * @property SlideProduct[] $slideProducts
 * @property Product[] $products
 */
class Slide extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slide';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slide_type', 'product_id', 'order_no'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['html'], 'string'],
            [['image_url', 'name'], 'string', 'max' => 230],
            [['url'], 'string', 'max' => 200],
            [['layout'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slide_type' => 'Slide Type',
            'product_id' => 'Product ID',
            'image_url' => 'Image Url',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order_no' => 'Order No',
            'html' => 'Html',
            'layout' => 'Layout',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlideProducts()
    {
        return $this->hasMany(SlideProduct::className(), ['slide_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('slide_product', ['slide_id' => 'id']);
    }
}
