<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "account_wishlist".
 *
 * @property int $account_id
 * @property int $product_id
 * @property string $created_at
 * @property string $updated_at
 */
class AccountWishlist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account_wishlist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'product_id'], 'required'],
            [['account_id', 'product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['account_id', 'product_id'], 'unique', 'targetAttribute' => ['account_id', 'product_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
