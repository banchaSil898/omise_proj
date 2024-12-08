<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string $name
 * @property string $brief
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_hide
 * @property int $is_pin
 * @property string $background_color
 * @property string $background_url
 * @property string $content_type
 * @property string $url_key
 * @property string $icon
 * @property int $order_no
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brief', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_hide', 'is_pin', 'order_no'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['background_color'], 'string', 'max' => 16],
            [['background_url', 'content_type', 'icon'], 'string', 'max' => 60],
            [['url_key'], 'string', 'max' => 150],
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
            'brief' => 'Brief',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_hide' => 'Is Hide',
            'is_pin' => 'Is Pin',
            'background_color' => 'Background Color',
            'background_url' => 'Background Url',
            'content_type' => 'Content Type',
            'url_key' => 'Url Key',
            'icon' => 'Icon',
            'order_no' => 'Order No',
        ];
    }
}
