<?php

namespace app\models;

use app\components\Helper;
use app\models\base\ReportSellProductMonthly as BaseReportSellProductMonthly;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ReportSellProductMonthly extends BaseReportSellProductMonthly {

    public $from_year;
    public $from_month;
    public $to_year;
    public $to_month;

    public static function find() {
        return Yii::createObject(ReportSellProductMonthlyQuery::className(), [get_called_class()]);
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'rec_count' => 'จำนวนสั่งซื้อ',
            'rec_amount' => 'ยอดขาย',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['from_year', 'from_month', 'to_month', 'to_year'], 'integer'];
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
        return implode('-', [$this->from_year, $this->from_month, '01']);
    }

    public function getToDate() {
        return implode('-', [$this->to_year, $this->to_month, '01']);
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

    public function getTextMonthRange() {
        return str_replace([
            '{start}',
            '{end}',
                ], [
            Helper::getMonthOptions($this->from_month) . ' ' . $this->from_year,
            Helper::getMonthOptions($this->to_month) . ' ' . $this->to_year,
                ], 'ตั้งแต่ {start} ถึง {end}');
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
            'report_sell_product_monthly.product_id',
            'info_publish',
            'folder_data.folder_type',
            'folder_data.folder_name',
            'product.sku as sku',
            'product.name as name',
            'SUM(report_sell_product_monthly.rec_count) as rec_count',
            'SUM(report_sell_product_monthly.rec_amount) as rec_amount',
        ])
        ->join('INNER JOIN', 'product', 'product.id = report_sell_product_monthly.product_id')
        ->join('INNER JOIN', '('. $subQuery->createCommand()->getRawSql() .') as folder_data', 'product.id = folder_data.product_id')
        ->groupBy(['product_id', 'folder_type', 'folder_data.folder_name', 'folder_data.folder_type'])
        ->where(['>=', 'rec_date', $from_date])
        ->andWhere(['<=', 'rec_date', $to_date])
        ->orderBy(['rec_date' => SORT_ASC, 'product_id' => SORT_ASC]);
        
        switch ($periodType) {
            case 'daily':
                $query->addSelect([
                    'report_sell_product_monthly.rec_date',
                    'date_format(report_sell_product_monthly.rec_date, "%Y-%m") as period_sold',
                ])
                ->addGroupBy([
                    'report_sell_product_monthly.rec_date',
                ]);
                break;

            case 'weekly';
                $query->addSelect([
                    'date_format(report_sell_product_monthly.rec_date, "%Y สัปดาห์ที่ %U") as period_sold',
                    'report_sell_product_monthly.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_monthly.rec_date, "%Y-%U")',
                    'report_sell_product_monthly.product_id'
                ]);
                break;

            case 'monthly';
                $query->addSelect([
                    'date_format(report_sell_product_monthly.rec_date, "%Y-%m") as period_sold',
                    'report_sell_product_monthly.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_monthly.rec_date, "%Y-%m")',
                    'report_sell_product_monthly.product_id'
                ]);
                break;

            case 'yearly';
                $query->addSelect([
                    'date_format(report_sell_product_monthly.rec_date, "%Y") as period_sold',
                    'report_sell_product_monthly.product_id'
                ])
                ->addGroupBy([
                    'date_format(report_sell_product_monthly.rec_date, "%Y")',
                    'report_sell_product_monthly.product_id'
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

class ReportSellProductMonthlyQuery extends ActiveQuery {

    public function andWhereDateRange($from, $to) {
        $this->andFilterWhere(['>=', 'rec_date', $from]);
        $this->andFilterWhere(['<=', 'rec_date', $to]);
        return $this;
    }

    public function selectSummary($columns = []) {
        $this->select([
            'report_sell_product_monthly.rec_date',
            'report_sell_product_monthly.product_id',
            'SUM(report_sell_product_monthly.rec_amount) as rec_amount',
            'SUM(report_sell_product_monthly.rec_count) as rec_count',
        ]);
        $this->addSelect($columns);
        return $this;
    }

}
