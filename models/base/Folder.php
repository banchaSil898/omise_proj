<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "folder".
 *
 * @property int $id
 * @property int $folder_id
 * @property string $name
 * @property string $url_key
 * @property int $position
 * @property int $level
 *
 * @property DiscountFolder[] $discountFolders
 * @property Discount[] $discounts
 * @property Folder $folder
 * @property Folder[] $folders
 * @property ProductFolder[] $productFolders
 * @property Product[] $products
 */
class Folder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'folder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['folder_id', 'position', 'level'], 'integer'],
            [['position', 'level'], 'required'],
            [['name', 'url_key'], 'string', 'max' => 160],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::className(), 'targetAttribute' => ['folder_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'folder_id' => 'Folder ID',
            'name' => 'Name',
            'url_key' => 'Url Key',
            'position' => 'Position',
            'level' => 'Level',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountFolders()
    {
        return $this->hasMany(DiscountFolder::className(), ['folder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::className(), ['id' => 'discount_id'])->viaTable('discount_folder', ['folder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolder()
    {
        return $this->hasOne(Folder::className(), ['id' => 'folder_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::className(), ['folder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductFolders()
    {
        return $this->hasMany(ProductFolder::className(), ['folder_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_folder', ['folder_id' => 'id']);
    }
}
