<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gift_image".
 *
 * @property int $gift_id
 * @property int $image_id
 * @property string $thumb_url
 * @property string $img_url
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_no
 *
 * @property Product $gift
 */
class GiftImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gift_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gift_id', 'image_id'], 'required'],
            [['gift_id', 'image_id', 'order_no'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['thumb_url', 'img_url'], 'string', 'max' => 200],
            [['gift_id', 'image_id'], 'unique', 'targetAttribute' => ['gift_id', 'image_id']],
            [['gift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['gift_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gift_id' => 'Gift ID',
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
    public function getGift()
    {
        return $this->hasOne(Product::className(), ['id' => 'gift_id']);
    }
}
