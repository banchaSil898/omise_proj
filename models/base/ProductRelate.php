<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "product_relate".
 *
 * @property int $product_id
 * @property int $relate_id
 *
 * @property Product $product
 * @property Product $relate
 */
class ProductRelate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_relate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'relate_id'], 'required'],
            [['product_id', 'relate_id'], 'integer'],
            [['product_id', 'relate_id'], 'unique', 'targetAttribute' => ['product_id', 'relate_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['relate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['relate_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'relate_id' => 'Relate ID',
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
    public function getRelate()
    {
        return $this->hasOne(Product::className(), ['id' => 'relate_id']);
    }
}
