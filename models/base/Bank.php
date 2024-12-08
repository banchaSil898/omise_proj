<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property int $id
 * @property string $name
 * @property string $cover_url
 * @property string $created_at
 * @property string $updated_at
 * @property string $account_no
 * @property string $account_type
 * @property string $account_branch
 * @property int $order_no
 * @property int $is_enabled
 * @property string $account_name
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['order_no', 'is_enabled'], 'integer'],
            [['name', 'cover_url'], 'string', 'max' => 160],
            [['account_no', 'account_type', 'account_branch', 'account_name'], 'string', 'max' => 60],
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
            'cover_url' => 'Cover Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'account_no' => 'Account No',
            'account_type' => 'Account Type',
            'account_branch' => 'Account Branch',
            'order_no' => 'Order No',
            'is_enabled' => 'Is Enabled',
            'account_name' => 'Account Name',
        ];
    }
}
