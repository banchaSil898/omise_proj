<?php

namespace app\models;

use app\components\Helper;
use app\models\base\ReportSellProductDaily as BaseReportSellProductDaily;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ReportSellProductDaily extends BaseReportSellProductDaily {

    public $from_date;
    public $to_date;

    public static function find() {
        return Yii::createObject(ReportSellProductDailyQuery::className(), [get_called_class()]);
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
    public function getProductFolder() {
        return $this->hasOne(ProductFolder::className(), ['product_id' => 'product_id']);
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

    public function getExcelDataProvider($periodType='daily'){
        $from_date = $this->getFromDate();
        $to_date = $this->getToDate();
        
        $subQuery = $this->find();
        $subQuery->select([
            'product_folder.product_id',
            'GROUP_CONCAT(folder.name SEPARATOR " ") as folder_name',
            'case when 478 in (folder.id, folder.folder_id) then "นิตยสาร" else "pocket book" end as folder_type'
        ])
        ->from('product_folder')
        ->join('INNER JOIN', 'folder', 'product_folder.folder_id = folder.id')
        ->groupBy(['product_folder.product_id', 'folder_type']);

        $query = $this->find();
        $query->select([
            'report_sell_product_daily.product_id',
            'info_publish',
            'folder_data.folder_type',
            'folder_data.folder_name',
            'product.sku as sku',
            'product.name as name',
            'SUM(report_sell_product_daily.rec_count) as rec_count',
            'SUM(report_sell_product_daily.rec_amount) as rec_amount',
        ])
        ->join('INNER JOIN', 'product', 'product.id = report_sell_product_daily.product_id')
        ->join('INNER JOIN', '('. $subQuery->createCommand()->getRawSql() .') as folder_data', 'product.id = folder_data.product_id')
        ->groupBy(['product_id', 'folder_type', 'folder_data.folder_name', 'folder_data.folder_type'])
        ->where(['>=', 'rec_date', $from_date])
        ->andWhere(['<=', 'rec_date', $to_date])
        ->orderBy(['rec_date' => SORT_ASC, 'product_id' => SORT_ASC]);
        
        switch ($periodType) {
            case 'daily':
                $query->addSelect([
                    'report_sell_product_daily.rec_date',
                    'date_format(report_sell_product_daily.rec_date, "%Y-%m") as period_sold',
                ])
                ->addGroupBy([
                    'report_sell_product_daily.rec_date',
                ]);
                break;

            case 'weekly';
                $query->addSelect([
                    'date_format(report_sell_product_daily.rec_date, "%Y สัปดาห์ที่ %U") as period_sold',
                    'report_sell_product_daily.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_daily.rec_date, "%Y-%U")',
                    'report_sell_product_daily.product_id'
                ]);
                break;

            case 'monthly';
                $query->addSelect([
                    'date_format(report_sell_product_daily.rec_date, "%Y-%m") as period_sold',
                    'report_sell_product_daily.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_daily.rec_date, "%Y-%m")',
                    'report_sell_product_daily.product_id'
                ]);
                break;

            case 'yearly';
                $query->addSelect([
                    'date_format(report_sell_product_daily.rec_date, "%Y") as period_sold',
                    'report_sell_product_daily.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_daily.rec_date, "%Y")',
                    'report_sell_product_daily.product_id'
                ]);
                break;
        }
        // return new ActiveDataProvider([
        //     'query' => $query,
        //     'sort' => [
        //         'defaultOrder' => [
        //             'rec_count' => SORT_DESC,
        //         ],
        //     ],
        // ]);
        return $query->asArray()->all();
    }
}

class ReportSellProductDailyQuery extends ActiveQuery {

    public function andWhereDateRange($from, $to) {
        $this->andFilterWhere(['>=', 'rec_date', $from]);
        $this->andFilterWhere(['<=', 'rec_date', $to]);
        return $this;
    }

    public function selectSummary($columns = []) {
        $this->select([
            'report_sell_product_daily.rec_date',
            'report_sell_product_daily.product_id',
            'SUM(report_sell_product_daily.rec_amount) as rec_amount',
            'SUM(report_sell_product_daily.rec_count) as rec_count',
        ]);
        $this->addSelect($columns);
        return $this;
    }

}
