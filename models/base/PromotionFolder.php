<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "promotion_folder".
 *
 * @property int $promotion_id
 * @property int $folder_id
 *
 * @property Folder $folder
 * @property Promotion $promotion
 */
class PromotionFolder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_folder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'folder_id'], 'required'],
            [['promotion_id', 'folder_id'], 'integer'],
            [['promotion_id', 'folder_id'], 'unique', 'targetAttribute' => ['promotion_id', 'folder_id']],
            [['folder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::className(), 'targetAttribute' => ['folder_id' => 'id']],
            [['promotion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promotion::className(), 'targetAttribute' => ['promotion_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'promotion_id' => 'Promotion ID',
            'folder_id' => 'Folder ID',
        ];
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
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }
}
