<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_exception".
 *
 * @property int $promotion_id
 * @property int $product_id
 * @property int $is_exclude
 */
class PromotionException extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_exception';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'product_id'], 'required'],
            [['promotion_id', 'product_id', 'is_exclude'], 'integer'],
            [['promotion_id', 'product_id'], 'unique', 'targetAttribute' => ['promotion_id', 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'promotion_id' => 'Promotion ID',
            'product_id' => 'Product ID',
            'is_exclude' => 'Is Exclude',
        ];
    }
}
