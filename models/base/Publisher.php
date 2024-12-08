<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "publisher".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo_url
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_hide
 * @property int $is_recommended
 * @property int $is_own เป็นเจ้าของเอง
 *
 * @property Product[] $products
 */
class Publisher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'publisher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_hide', 'is_recommended', 'is_own'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['photo_url'], 'string', 'max' => 160],
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
            'description' => 'Description',
            'photo_url' => 'Photo Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_hide' => 'Is Hide',
            'is_recommended' => 'Is Recommended',
            'is_own' => 'Is Own',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['publisher_id' => 'id']);
    }
}
