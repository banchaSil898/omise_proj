<?php

namespace app\models;

use app\models\base\ReportSellProductToday as BaseReportSellProductToday;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ReportSellProductToday extends BaseReportSellProductToday {

    public $from_date;
    public $to_date;

    public static function find() {
        return Yii::createObject(ReportSellProductTodayQuery::className(), [get_called_class()]);
    }

    public static function primaryKey() {
        return [
            'rec_date',
            'product_id',
        ];
    }
    
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'rec_count' => 'จำนวนสั่งซื้อ',
            'rec_amount' => 'ยอดขาย',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['from_date', 'to_date'], 'date'];
        return $rules;
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * 
     * @return ReportSellProductMonthlyQuery
     */
    public function getQuery() {
        $query = $this->find();
        $query->selectSummary();
        $from_date = $this->getFromDate();
        $to_date = $this->getToDate();
        $query->andWhereDateRange($from_date, $to_date);
        return $query;
    }

    public function getFromDate() {
        return $this->from_date;
    }

    public function getToDate() {
        return $this->to_date;
    }

    public function summaryByProduct() {
        $query = $this->getQuery();

        $query->groupBy([
            'product_id',
        ]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'rec_count' => SORT_DESC,
                ],
            ],
        ]);
    }

}

class ReportSellProductTodayQuery extends ActiveQuery {

    public function andWhereDateRange($from, $to) {
        $this->andFilterWhere(['>=', 'rec_date', $from]);
        $this->andFilterWhere(['<=', 'rec_date', $to]);
        return $this;
    }

    public function selectSummary($columns = []) {
        $this->select([
            'report_sell_product_today.rec_date',
            'report_sell_product_today.product_id',
            'SUM(report_sell_product_today.rec_amount) as rec_amount',
            'SUM(report_sell_product_today.rec_count) as rec_count',
        ]);
        $this->addSelect($columns);
        return $this;
    }

}
