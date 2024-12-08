<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $account_id
 * @property string $purchase_no
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property int $record_status
 * @property string $record_remark
 * @property int $contact_type_id
 * @property string $answer
 * @property string $answer_at
 * @property int $answer_id
 * @property string $answer_name
 *
 * @property ContactType $contactType
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'record_status', 'contact_type_id', 'answer_id'], 'integer'],
            [['description', 'record_remark', 'answer'], 'string'],
            [['created_at', 'updated_at', 'answer_at'], 'safe'],
            [['contact_type_id'], 'required'],
            [['name'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 150],
            [['purchase_no'], 'string', 'max' => 60],
            [['answer_name'], 'string', 'max' => 80],
            [['contact_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactType::className(), 'targetAttribute' => ['contact_type_id' => 'id']],
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
            'email' => 'Email',
            'account_id' => 'Account ID',
            'purchase_no' => 'Purchase No',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'record_status' => 'Record Status',
            'record_remark' => 'Record Remark',
            'contact_type_id' => 'Contact Type ID',
            'answer' => 'Answer',
            'answer_at' => 'Answer At',
            'answer_id' => 'Answer ID',
            'answer_name' => 'Answer Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactType()
    {
        return $this->hasOne(ContactType::className(), ['id' => 'contact_type_id']);
    }
}
