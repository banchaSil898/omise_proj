<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "mail_layout".
 *
 * @property int $id
 * @property string $name
 * @property string $mail_header
 * @property string $mail_footer
 * @property string $created_at
 * @property string $updated_at
 */
class MailLayout extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_layout';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mail_header', 'mail_footer'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 200],
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
            'mail_header' => 'Mail Header',
            'mail_footer' => 'Mail Footer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
