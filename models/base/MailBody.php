<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "mail_body".
 *
 * @property int $id
 * @property string $name
 * @property string $mail_type
 * @property string $mail_title
 * @property string $mail_body
 * @property string $created_at
 * @property string $updated_at
 */
class MailBody extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_body';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mail_body'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['mail_type'], 'string', 'max' => 64],
            [['mail_title'], 'string', 'max' => 250],
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
            'mail_type' => 'Mail Type',
            'mail_title' => 'Mail Title',
            'mail_body' => 'Mail Body',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
