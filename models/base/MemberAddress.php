<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "member_address".
 *
 * @property int $member_id
 * @property int $address_id
 * @property string $company_name
 * @property string $firstname
 * @property string $lastname
 * @property string $tax_code
 * @property string $home_no
 * @property string $soi
 * @property string $street
 * @property string $postcode
 * @property string $additional
 * @property string $province
 * @property string $amphur
 * @property string $tambon
 * @property string $created_at
 * @property string $updated_at
 * @property string $phone
 * @property string $address
 * @property string $country_id
 * @property int $magento_id
 *
 * @property Member $member
 */
class MemberAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'address_id'], 'required'],
            [['member_id', 'address_id', 'magento_id'], 'integer'],
            [['street', 'additional', 'address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_name', 'firstname', 'lastname'], 'string', 'max' => 160],
            [['tax_code'], 'string', 'max' => 13],
            [['home_no', 'soi', 'postcode'], 'string', 'max' => 60],
            [['province', 'amphur', 'tambon'], 'string', 'max' => 230],
            [['phone'], 'string', 'max' => 45],
            [['country_id'], 'string', 'max' => 5],
            [['member_id', 'address_id'], 'unique', 'targetAttribute' => ['member_id', 'address_id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'address_id' => 'Address ID',
            'company_name' => 'Company Name',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'tax_code' => 'Tax Code',
            'home_no' => 'Home No',
            'soi' => 'Soi',
            'street' => 'Street',
            'postcode' => 'Postcode',
            'additional' => 'Additional',
            'province' => 'Province',
            'amphur' => 'Amphur',
            'tambon' => 'Tambon',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'phone' => 'Phone',
            'address' => 'Address',
            'country_id' => 'Country ID',
            'magento_id' => 'Magento ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
}
