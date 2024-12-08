<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $secret
 * @property string $created_at
 * @property string $updated_at
 * @property int $role_id
 * @property string $description
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['role_id'], 'integer'],
            [['description'], 'string'],
            [['username'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 160],
            [['email'], 'string', 'max' => 150],
            [['secret'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'email' => 'Email',
            'secret' => 'Secret',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role_id' => 'Role ID',
            'description' => 'Description',
        ];
    }
}
