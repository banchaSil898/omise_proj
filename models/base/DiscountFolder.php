<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "discount_folder".
 *
 * @property int $discount_id
 * @property int $folder_id
 *
 * @property Discount $discount
 * @property Folder $folder
 */
class DiscountFolder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount_folder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discount_id', 'folder_id'], 'required'],
            [['discount_id', 'folder_id'], 'integer'],
            [['discount_id', 'folder_id'], 'unique', 'targetAttribute' => ['discount_id', 'folder_id']],
            [['discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discount_id' => 'id']],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::className(), 'targetAttribute' => ['folder_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'discount_id' => 'Discount ID',
            'folder_id' => 'Folder ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount()
    {
        return $this->hasOne(Discount::className(), ['id' => 'discount_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolder()
    {
        return $this->hasOne(Folder::className(), ['id' => 'folder_id']);
    }
}
