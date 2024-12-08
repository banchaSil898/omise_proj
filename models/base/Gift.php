<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gift".
 *
 * @property int $id
 * @property string $name
 * @property string $thumb_url
 * @property string $image_url
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property string $attr1_name
 * @property string $attr1_data
 * @property string $attr2_name
 * @property string $attr2_data
 * @property string $attr3_name
 * @property string $attr3_data
 * @property int $stock
 * @property int $stock_est
 *
 * @property PromotionGift[] $promotionGifts
 * @property Promotion[] $promotions
 */
class Gift extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gift';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['description', 'attr1_data', 'attr2_data', 'attr3_data'], 'string'],
            [['stock', 'stock_est'], 'integer'],
            [['name', 'thumb_url', 'image_url'], 'string', 'max' => 150],
            [['attr1_name', 'attr2_name', 'attr3_name'], 'string', 'max' => 60],
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
            'thumb_url' => 'Thumb Url',
            'image_url' => 'Image Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'description' => 'Description',
            'attr1_name' => 'Attr1 Name',
            'attr1_data' => 'Attr1 Data',
            'attr2_name' => 'Attr2 Name',
            'attr2_data' => 'Attr2 Data',
            'attr3_name' => 'Attr3 Name',
            'attr3_data' => 'Attr3 Data',
            'stock' => 'Stock',
            'stock_est' => 'Stock Est',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotionGifts()
    {
        return $this->hasMany(PromotionGift::className(), ['gift_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotions()
    {
        return $this->hasMany(Promotion::className(), ['id' => 'promotion_id'])->viaTable('promotion_gift', ['gift_id' => 'id']);
    }
}
