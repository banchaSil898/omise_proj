<?php

namespace app\models;

use app\models\base\MemberAddress as BaseMemberAddress;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class MemberAddress extends BaseMemberAddress {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'firstname' => 'ชื่อ',
            'lastname' => 'นามสกุล',
            'company_name' => 'ชื่อหมู่บ้าน/บริษัท/หน่วยงาน',
            'tax_code' => 'เลขประจำตัวผู้เสียภาษี',
            'home_no' => 'เลขที่',
            'soi' => 'ซอย',
            'street' => 'ถนน',
            'province' => 'จังหวัด',
            'amphur' => 'เขต/อำเภอ',
            'tambon' => 'แขวง/ตำบล',
            'postcode' => 'รหัสไปรษณีย์',
            'additional' => 'หมายเหตุ',
            'address' => 'ที่อยู่',
            'phone' => 'โทรศัพท์',
            'is_primary' => 'ที่อยู่หลัก',
            'country_id' => 'ประเทศ',
            'isBillingDefault' => 'ที่อยู่หลักสำหรับออกใบเสร็จ',
            'isShippingDefault' => 'ที่อยู่หลักสำหรับจัดส่งสินค้า',
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

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if (!$this->address_id) {
                    $this->address_id = $this->getDb()->createCommand('SELECT COALESCE(MAX(address_id),0) + 1 FROM member_address WHERE member_id = :member_id')->bindValue(':member_id', $this->member_id)->queryScalar();
                }
            }
            return true;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        switch ($this->scenario) {
            case 'user-update':
                if (!isset($this->member->default_addr_billing)) {
                    $this->doBillingPrimary();
                }
                if (!isset($this->member->default_addr_shipping)) {
                    $this->doShippingPrimary();
                }
                break;
        }
    }

    public function afterDelete() {
        parent::afterDelete();

        if ($this->getIsBillingDefault()) {
            $this->member->updateAttributes([
                'default_addr_billing' => null,
            ]);
        }

        if ($this->getIsShippingDefault()) {
            $this->member->updateAttributes([
                'default_addr_billing' => null,
            ]);
        }
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['firstname', 'lastname', 'home_no', 'postcode', 'country_id', 'province', 'amphur', 'tambon', 'phone'], 'required', 'on' => 'user-update'];
        $rules[] = [['is_primary'], 'safe', 'on' => 'user-update'];
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('member_id', $this->member_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getIsBillingDefault() {
        return $this->address_id == $this->member->default_addr_billing;
    }

    public function getIsShippingDefault() {
        return $this->address_id == $this->member->default_addr_shipping;
    }

    public function doBillingPrimary() {
        $this->member->updateAttributes([
            'default_addr_billing' => $this->address_id,
        ]);
    }

    public function doShippingPrimary() {
        $this->member->updateAttributes([
            'default_addr_shipping' => $this->address_id,
        ]);
    }

    public function getShortAddress() {
        $ret = [];

        if ($this->home_no) {
            $ret[] = $this->home_no;
        }
        if ($this->soi) {
            $ret[] = 'ซ.' . $this->soi;
        }
        if ($this->street) {
            $ret[] = $this->street;
        }
        if ($this->tambon) {
            if (strpos($this->province, 'กรุงเทพ') || strpos($this->province, 'กทม')) {
                $ret[] = 'แขวง' . $this->tambon;
            } else {
                $ret[] = 'ต.' . $this->tambon;
            }
        }
        if ($this->amphur) {
            if (strpos($this->province, 'กรุงเทพ') || strpos($this->province, 'กทม')) {
                $ret[] = 'เขต' . $this->amphur;
            } else {
                $ret[] = 'อ.' . $this->amphur;
            }
        }
        return implode(' ', $ret);
    }

    public function getNormalAddress() {
        $ret = [];

        if ($this->home_no) {
            $ret[] = $this->home_no;
        }
        if ($this->soi) {
            $ret[] = 'ซ.' . $this->soi;
        }
        if ($this->street) {
            $ret[] = $this->street;
        }
        return implode(' ', $ret);
    }

    public function getAddress() {
        $ret = [];
        if ($this->province) {
            $ret[] = 'จ.' . $this->province;
        }
        if ($this->postcode) {
            $ret[] = $this->postcode;
        }
        return $this->getShortAddress() . ' ' . implode(' ', $ret);
    }

    public function getIsPrimary() {
        return $this->is_primary ? true : false;
    }

}
