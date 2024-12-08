<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo_url
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_hide
 * @property int $is_recommended
 *
 * @property ProductAuthor[] $productAuthors
 * @property Product[] $products
 */
class Author extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_hide', 'is_recommended'], 'integer'],
            [['name', 'photo_url'], 'string', 'max' => 160],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAuthors()
    {
        return $this->hasMany(ProductAuthor::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_author', ['author_id' => 'id']);
    }
}
