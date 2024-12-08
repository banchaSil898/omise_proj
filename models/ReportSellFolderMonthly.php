<?php

namespace app\models;

use app\models\base\ReportSellFolderMonthly as BaseReportSellFolderMonthly;

class ReportSellFolderMonthly extends BaseReportSellFolderMonthly {

    public $from_year;
    public $from_month;
    public $to_year;
    public $to_month;

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['from_year', 'from_month', 'to_month', 'to_year'], 'integer'];
        return $rules;
    }

}
