<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $facebook_id
 * @property string $google_id
 * @property string $line_id
 * @property string $username
 * @property string $secret
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property int $magento_id
 * @property string $firstname
 * @property string $lastname
 * @property string $forgot_code
 * @property string $forgot_at
 * @property string $active_code
 * @property string $active_at
 * @property int $status
 * @property string $account_key
 * @property string $phone
 * @property string $birth_date
 * @property int $default_addr_billing
 * @property int $default_addr_shipping
 * @property int $is_need_register
 * @property int $is_survey_2019
 * @property int $survey_2019_allow
 * @property int $survey_2019_age
 * @property string $survey_2019_graduate
 * @property string $survey_2019_comment
 *
 * @property MemberAddress[] $memberAddresses
 * @property Purchase[] $purchases
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['facebook_id', 'magento_id', 'status', 'default_addr_billing', 'default_addr_shipping', 'is_need_register', 'is_survey_2019', 'survey_2019_allow', 'survey_2019_age'], 'integer'],
            [['username'], 'required'],
            [['created_at', 'updated_at', 'forgot_at', 'active_at', 'birth_date'], 'safe'],
            [['survey_2019_comment'], 'string'],
            [['google_id', 'line_id', 'forgot_code', 'active_code', 'phone'], 'string', 'max' => 60],
            [['username', 'survey_2019_graduate'], 'string', 'max' => 64],
            [['secret'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 200],
            [['firstname', 'lastname'], 'string', 'max' => 160],
            [['account_key'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'facebook_id' => 'Facebook ID',
            'google_id' => 'Google ID',
            'line_id' => 'Line ID',
            'username' => 'Username',
            'secret' => 'Secret',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
            'magento_id' => 'Magento ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'forgot_code' => 'Forgot Code',
            'forgot_at' => 'Forgot At',
            'active_code' => 'Active Code',
            'active_at' => 'Active At',
            'status' => 'Status',
            'account_key' => 'Account Key',
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
            'default_addr_billing' => 'Default Addr Billing',
            'default_addr_shipping' => 'Default Addr Shipping',
            'is_need_register' => 'Is Need Register',
            'is_survey_2019' => 'Is Survey 2019',
            'survey_2019_allow' => 'Survey 2019 Allow',
            'survey_2019_age' => 'Survey 2019 Age',
            'survey_2019_graduate' => 'Survey 2019 Graduate',
            'survey_2019_comment' => 'Survey 2019 Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberAddresses()
    {
        return $this->hasMany(MemberAddress::className(), ['member_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::className(), ['member_id' => 'id']);
    }
}
