<?php

namespace app\commands;

use app\models\Purchase;
use Yii;
use yii\console\Controller;

class CronController extends Controller {

    public function actionDaily() {
        $this->doSummaryReport();
        $this->doWarnPurchases();
        $this->doCancelPurchases();
    }

    public function actionCheck() {
        $this->doWarnPurchases();
    }

    protected function doWarnPurchases() {
        /* @var $purchase Purchase */
        $purchases = Purchase::find()->nearlyExpire(2)->all();
        Yii::info('found ' . count($purchases) . ' must warn orders.', 'cron-purchase');
        foreach ($purchases as $purchase) {
            Yii::info($purchase->purchase_no . ' -- warned.', 'cron-purchase');
            $purchase->doWarnPayment();
        }
    }

    protected function doCancelPurchases() {
        /* @var $purchase Purchase */
        $purchases = Purchase::find()->expire()->all();
        Yii::info('found ' . count($purchases) . ' expired orders.', 'cron-purchase');
        foreach ($purchases as $purchase) {
            Yii::info($purchase->purchase_no . ' -- cancelled.', 'cron-purchase');
            $purchase->doCancelPayment('ยกเลิกอัตโนมัติ');
        }
    }

    protected function doSummaryReport() {
        Yii::$app->db->createCommand()->truncateTable('report_sell_product_daily')->execute();
        Yii::info('clear report_sell_product_daily.', 'cron-purchase');
        Yii::$app->db->createCommand('
            INSERT INTO report_sell_product_daily (rec_date, product_id, rec_amount, rec_count)
            SELECT 
                    DATE(p.created_at) as rec_date,
                pp.product_id,
                COALESCE(SUM(pp.price_total),0) as rec_amount,
                COALESCE(SUM(pp.amount),0) as rec_count
            FROM 
                    purchase_product pp
            INNER JOIN
                    purchase p ON p.id = pp.purchase_id
            WHERE
                    p.is_paid = 1
            GROUP BY
                    rec_date,
                pp.product_id
        ')->execute();
        Yii::info('regenerate report_sell_product_daily.', 'cron-purchase');
        Yii::$app->db->createCommand()->truncateTable('report_sell_product_monthly')->execute();
        Yii::info('clear report_sell_product_monthly.', 'cron-purchase');
        Yii::$app->db->createCommand('
            INSERT INTO report_sell_product_monthly (rec_date, product_id, rec_amount, rec_count)
            SELECT 
                    DATE_FORMAT(DATE(p.created_at),"%Y-%m-01") as rec_date,
                    pp.product_id,
                    COALESCE(SUM(pp.price_total),0) as rec_amount,
                    COALESCE(SUM(pp.amount),0) as rec_count
            FROM 
                    purchase_product pp
            INNER JOIN
                    purchase p ON p.id = pp.purchase_id
            WHERE
                    p.is_paid = 1
            GROUP BY
                    rec_date,
                pp.product_id
        ')->execute();
        Yii::info('regenerate report_sell_product_monthly.', 'cron-purchase');
    }

}
