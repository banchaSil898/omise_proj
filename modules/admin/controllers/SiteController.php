<?php

namespace app\modules\admin\controllers;

use app\components\Helper;
use app\models\Account;
use app\models\LogSearch;
use app\models\LogSearchKeyword;
use app\models\Member;
use app\models\Product;
use app\models\Purchase;
use app\models\ReportSellProductDaily;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class SiteController extends Controller {

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => Yii::$app->user->isGuest ? '@module/views/layouts/error' : null,
            ],
        ];
    }

    public function beforeAction($action) {
        if ($action->id === 'login') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex() {

        $stat = [];
        $stat['member-all'] = Member::find()->count();

        $stat['all-sell'] = Purchase::find()->select(['SUM(price_total) as price_total'])
                        ->andWhere(['is_paid' => 1])->scalar();

        $stat['prev-month-sell'] = (float) Purchase::find()->select(['SUM(price_total) as price_total'])->where([
                            'MONTH(created_at)' => date('n') - 1 == 0 ? 12 : date('n') - 1,
                            'YEAR(created_at)' => date('n') - 1 == 0 ? date('Y') - 1 : date('Y'),
                        ])
                        ->andWhere(['is_paid' => 1])->scalar();

        $stat['this-month-sell'] = (float) Purchase::find()->select(['SUM(price_total) as price_total'])->where([
                            'MONTH(created_at)' => date('n'),
                            'YEAR(created_at)' => date('Y'),
                        ])
                        ->andWhere(['is_paid' => 1])->scalar();

        $stat['sell-rate'] = $stat['prev-month-sell'] > 0 ? $stat['this-month-sell'] * 100 / ($stat['prev-month-sell']) : 100;

        $countMemberThisMonth = Member::find()->where([
                    'MONTH(created_at)' => date('n'),
                    'YEAR(created_at)' => date('Y'),
                ])->count();

        $countMemberLastMonth = Member::find()->where([
                    'MONTH(created_at)' => date('n') - 1 == 0 ? 12 : date('n') - 1,
                    'YEAR(created_at)' => date('n') - 1 == 0 ? date('Y') - 1 : date('Y'),
                ])->count();

        $stat['member-rate'] = $countMemberLastMonth > 0 ? $countMemberThisMonth * 100 / ($countMemberLastMonth * 2) : 100;

        $stat['purchase-wait'] = Purchase::find()->where([
                    'status' => [
                        Purchase::STATUS_PAID,
                        Purchase::STATUS_TRANSFER_CHECK,
                    ]
                ])->count();

        $topPurchaseDataProvider = new ActiveDataProvider([
            'query' => Purchase::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $lowStockDataProvider = new ActiveDataProvider([
            'query' => Product::find()->active()->andWhere(['<=', 'stock', 5])->andWhere(['>', 'stock', 0]),
            'sort' => [
                'defaultOrder' => [
                    'stock' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);


        $lastSearchDataProvider = new ActiveDataProvider([
            'query' => LogSearch::find()->addSelect(['id', 'keyword', 'MAX(result_count) as result_count', 'MAX(result_time) as result_time'])->groupBy(['keyword'])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $topSearchDataProvider = new ActiveDataProvider([
            'query' => LogSearchKeyword::find()->addSelect(['keyword', 'result_count', 'result_time'])->orderBy(['result_time' => SORT_DESC]),
            'key' => 'keyword',
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $lastMemberDataProvider = new ActiveDataProvider([
            'query' => Member::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $model = new ReportSellProductDaily;
        $model->from_date = date('Y-m-01');
        $model->to_date = date('Y-m-t');
        $model->load(Yii::$app->request->get());


        $monthlyItems = $model->getQuery()
                ->groupBy([
                    'rec_date',
                ])->orderBy([
                    'rec_date' => SORT_ASC,
                ])
                ->indexBy('rec_date')
                ->asArray()
                ->all();

        $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, Helper::getDateArray($model->fromDate, $model->toDate), 'ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));

        return $this->render('index', [
                    'stat' => $stat,
                    'topPurchaseDataProvider' => $topPurchaseDataProvider,
                    'lastSearchDataProvider' => $lastSearchDataProvider,
                    'topSearchDataProvider' => $topSearchDataProvider,
                    'lastMemberDataProvider' => $lastMemberDataProvider,
                    'lowStockDataProvider' => $lowStockDataProvider,
                    'areaChartOptions' => $areaChartOptions,
        ]);
    }

    public function getAreaChartOptions($model, $items, $range, $text) {
        $areaPlotData = [];


        foreach ($range as $key => $text) {
            if (isset($items[$key])) {
                $areaPlotData[] = (int) $items[$key]['rec_amount'];
            } else {
                $areaPlotData[] = 0;
            }
        }

        return [
            'chart' => [
                'type' => 'area'
            ],
            'title' => [
                'text' => 'กราฟแสดงยอดขายสินค้า'
            ],
            'subtitle' => [
                'text' => $text,
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'จำนวนเงิน (บาท)',
                ],
            ],
            'xAxis' => [
                'categories' => array_values($range),
            ],
            'series' => [
                [
                    'connectNulls' => true,
                    'name' => 'ยอดขายสินค้า',
                    'data' => $areaPlotData,
                ],
            ],
        ];
    }

    public function actionLogin() {
        $this->layout = '@module/views/layouts/login';
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $model = new Account();
        $model->scenario = 'login';
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        }
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

}
