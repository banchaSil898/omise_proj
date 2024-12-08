<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order_no
 * @property int $promotion_type
 * @property string $promotion_value
 *
 * @property DiscountExclude[] $discountExcludes
 * @property Product[] $products
 * @property DiscountFolder[] $discountFolders
 * @property Folder[] $folders
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
            [['is_active', 'order_no', 'promotion_type'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['promotion_value'], 'string', 'max' => 60],
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
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'order_no' => 'Order No',
            'promotion_type' => 'Promotion Type',
            'promotion_value' => 'Promotion Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountExcludes()
    {
        return $this->hasMany(DiscountExclude::className(), ['discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('discount_exclude', ['discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountFolders()
    {
        return $this->hasMany(DiscountFolder::className(), ['discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::className(), ['id' => 'folder_id'])->viaTable('discount_folder', ['discount_id' => 'id']);
    }
}
