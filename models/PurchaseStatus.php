<?php

namespace app\models;

use app\models\base\PurchaseStatus as BasePurchaseStatus;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class PurchaseStatus extends BasePurchaseStatus {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'status' => 'สถานะ',
            'is_sendmail' => 'แจ้งให้ลูกค้าทราบ',
            'description' => 'หมายเหตุ',
            'created_at' => 'วันที่บันทึก',
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

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            /* @var $purchase Purchase */
            $purchase = $this->purchase;
            switch ($this->status) {
                case Purchase::STATUS_PAID:
                    $purchase->doPaid($this->description, false, $this->is_sendmail);
                    break;
                case Purchase::STATUS_CANCELLED:
                    $purchase->doCancelPayment($this->description, false, $this->is_sendmail);
                    break;
                case Purchase::STATUS_DELIVERIED:
                    $purchase->doDelivery($this->description, false, $this->is_sendmail);
                    break;
                default:
                    $purchase->updateAttributes([
                        'status' => $this->status,
                        'status_comment' => $this->description,
                    ]);
                    break;
            }
        }
    }

    public function getPurchase() {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

    public function getPossibleStatusOptions() {
        $ret = [];
        $purchase = $this->purchase;
        switch ($purchase->status) {
            case Purchase::STATUS_NEW:
            case Purchase::STATUS_TRANSFER_CHECK:
                $ret[Purchase::STATUS_PAID] = Purchase::getStatusOptions(Purchase::STATUS_PAID);
                break;
            case Purchase::STATUS_PAID:
                $ret[Purchase::STATUS_DELIVERIED] = Purchase::getStatusOptions(Purchase::STATUS_DELIVERIED);
                break;
            case Purchase::STATUS_DELIVERIED:
                $ret[Purchase::STATUS_DELIVERIED] = Purchase::getStatusOptions(Purchase::STATUS_DELIVERIED);
                break;
        }
        $ret[Purchase::STATUS_CANCELLED] = Purchase::getStatusOptions(Purchase::STATUS_CANCELLED);
        return $ret;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
