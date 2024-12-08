<?php

namespace app\models;

use app\components\MicMailer;
use app\models\base\Contact as BaseContact;
use codesk\components\Html;
use Exception;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;


class Contact extends BaseContact {

    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETED = 2;

    public $reCaptcha;
    public $search;

    public static function find() {
        return Yii::createObject(ContactQuery::className(), [get_called_class()]);
    }

    public static function getStatusOptions($code = null) {
        $ret = [
            self::STATUS_NEW => 'ข้อความใหม่',
            self::STATUS_PROCESSING => 'กำลังดำเนินการ',
            self::STATUS_COMPLETED => 'เรียบร้อย',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getStatusCssOptions($code = null) {
        $ret = [
            self::STATUS_NEW => 'label label-default',
            self::STATUS_PROCESSING => 'label label-info',
            self::STATUS_COMPLETED => 'label label-success',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อ-นามสกุล',
            'email' => 'อีเมล์',
            'contact_type_id' => 'หัวข้อคำถาม',
            'purchase_no' => 'หมายเลขใบสั่งซื้อ',
            'description' => 'ข้อความ',
            'reCaptcha' => 'การตรวจสอบโปรแกรมอัตโนมัติ',
            'created_at' => 'วันที่ได้รับข้อความ',
            'updated_at' => 'วันที่แก้ไข',
            'record_remark' => 'หมายเหตุ',
            'record_status' => 'สถานะ',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['name', 'email', 'contact_type_id', 'description', 'record_status'], 'required'];
        $rules[] = ['email', 'email'];
        $rules[] = [['reCaptcha'], ReCaptchaValidator::className(), 'on' => 'create'];
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterWhere(['LIKE', 'name', ArrayHelper::getValue($this->search, 'text')]);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getRecordStatus() {
        return self::getStatusOptions($this->record_status);
    }

    public function getHtmlRecordStatus() {
        return Html::tag('span', self::getStatusOptions($this->record_status), ['class' => self::getStatusCssOptions($this->record_status)]);
    }

    public function doSend() {
        if ($this->save()) {
            try {
                $mail = new MicMailer;
                $mail->setView('contact', [
                    '{{doc_no}}' => $this->purchase_no,
                    '{{name}}' => $this->name,
                    '{{email}}' => $this->email,
                    '{{contact_type}}' => $this->contactType->name,
                    '{{description}}' => $this->description,
                ]);
                $mail->setFrom($this->email);
                $mail->send(Configuration::getValue('web_mail'), false);
            } catch (Exception $e) {

            }
            return true;
        }
    }

}

class ContactQuery extends ActiveQuery {

    public function scopeDefault() {
        return $this;
    }

}
