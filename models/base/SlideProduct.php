<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "slide_product".
 *
 * @property int $slide_id
 * @property int $product_id
 *
 * @property Product $product
 * @property Slide $slide
 */
class SlideProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slide_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slide_id', 'product_id'], 'required'],
            [['slide_id', 'product_id'], 'integer'],
            [['slide_id', 'product_id'], 'unique', 'targetAttribute' => ['slide_id', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['slide_id'], 'exist', 'skipOnError' => true, 'targetClass' => Slide::className(), 'targetAttribute' => ['slide_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'slide_id' => 'Slide ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlide()
    {
        return $this->hasOne(Slide::className(), ['id' => 'slide_id']);
    }
}
