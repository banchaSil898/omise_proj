<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "word_convert".
 *
 * @property int $id
 * @property string $word_from
 * @property string $word_to
 * @property string $created_at
 * @property string $updated_at
 */
class WordConvert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'word_convert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['word_from', 'word_to'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['word_from', 'word_to'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word_from' => 'Word From',
            'word_to' => 'Word To',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
