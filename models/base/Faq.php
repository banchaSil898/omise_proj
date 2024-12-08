<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $id
 * @property string $name
 * @property string $answer
 * @property string $created_at
 * @property string $updated_at
 * @property int $order_no
 * @property int $is_hide
 */
class Faq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_no', 'is_hide'], 'integer'],
            [['name'], 'string', 'max' => 250],
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
            'answer' => 'Answer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order_no' => 'Order No',
            'is_hide' => 'Is Hide',
        ];
    }
}
