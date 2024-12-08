<?php

namespace app\modules\admin\controllers;

use app\components\Helper;
use app\models\ReportSellProductDaily;
use app\models\ReportSellProductMonthly;
use app\models\ReportSellProductToday;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ReportSellController extends Controller {

    public function export($model, $dataProvider, $summary, $title = null) {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $dataProvider->pagination = false;

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Cordia New',
                'size' => 14,
            ],
        ]);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');
        $row = 1;

        $sheet->setCellValueByColumnAndRow(1, $row, $title);
        $sheet->mergeCellsByColumnAndRow(1, $row, 4, $row);
        $sheet->getStyleByColumnAndRow(1, $row, 4, $row)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'C4D79B'),
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('rgb' => '333333'),
                ],
            ],
        ]);
        $row++;

        $sheet->setCellValueByColumnAndRow(1, $row, 'SKU');
        $sheet->setCellValueByColumnAndRow(2, $row, 'ชื่อสินค้า');
        $sheet->setCellValueByColumnAndRow(3, $row, 'ยอดขาย');
        $sheet->setCellValueByColumnAndRow(4, $row, 'จำนวนสั่งซื้อ');
        for ($i = 1; $i <= 4; $i++) {
            $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D8E4BC'),
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '333333'),
                    ],
                ],
            ]);
        }
        $row++;

        foreach ($dataProvider->models as $item) {
            $sheet->setCellValueExplicitByColumnAndRow(1, $row, ArrayHelper::getValue($item, 'product.sku'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(2, $row, ArrayHelper::getValue($item, 'product.name'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(3, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($item, 'rec_amount'), 2), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(4, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($item, 'rec_count'), 0), DataType::TYPE_STRING);
            for ($i = 1; $i <= 2; $i++) {
                $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb' => '333333'),
                        ],
                    ],
                ]);
            }
            for ($i = 3; $i <= 4; $i++) {
                $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb' => '333333'),
                        ],
                    ],
                ]);
            }
            $row++;
        }

        $sheet->setCellValueByColumnAndRow(1, $row, 'รวม');
        $sheet->mergeCellsByColumnAndRow(1, $row, 2, $row);
        $sheet->getStyleByColumnAndRow(1, $row, 2, $row)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('rgb' => '333333'),
                ],
            ],
        ]);
        $sheet->setCellValueExplicitByColumnAndRow(3, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($summary, 'rec_amount'), 2), DataType::TYPE_STRING);
        $sheet->setCellValueExplicitByColumnAndRow(4, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($summary, 'rec_count'), 0), DataType::TYPE_STRING);
        for ($i = 3; $i <= 4; $i++) {
            $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '333333'),
                    ],
                ],
            ]);
        }
        $row++;

        for ($col = 1; $col <= Coordinate::columnIndexFromString($sheet->getHighestDataColumn()); $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');
        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $content = ob_get_clean();
        return $content;
    }

    public function actionToday() {

        $model = new ReportSellProductToday;
        $model->from_date = date('Y-m-d');
        $model->to_date = date('Y-m-d');
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->summaryByProduct();

        $coreQuery = $model->getQuery();
        $summary = $coreQuery->one();

        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                return $this->export($model, $dataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));
            default:
                $monthlyItems = $model->getQuery()
                        ->groupBy([
                            'rec_date',
                        ])->orderBy([
                            'rec_date' => SORT_ASC,
                        ])
                        ->indexBy('rec_date')
                        ->asArray()
                        ->all();

                $folderItems = $model->getQuery()
                        ->addSelect([
                            'folder.name as folder_name',
                        ])
                        ->join('INNER JOIN', 'product_folder', 'product_folder.product_id = report_sell_product_today.product_id')
                        ->join('INNER JOIN', 'folder', 'folder.id = product_folder.folder_id')
                        ->groupBy([
                            'product_folder.folder_id',
                        ])
                        ->orderBy([
                            'rec_amount' => SORT_DESC
                        ])
                        ->asArray()
                        ->all();


                $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, Helper::getDateArray($model->fromDate, $model->toDate), 'ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));
                $pieChartOptions = $this->getPieChartOptions($model, $folderItems);

                return $this->render('today', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                            'areaChartOptions' => $areaChartOptions,
                            'pieChartOptions' => $pieChartOptions,
                ]);
        }
    }

    public function actionDaily() {

        $model = new ReportSellProductDaily;
        $model->from_date = date('Y-m-01');
        $model->to_date = date('Y-m-t');
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->summaryByProduct();

        $coreQuery = $model->getQuery();
        $summary = $coreQuery->one();

        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                return $this->export($model, $dataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));
            case 'test';
                $excelDataProvider = $model->getExcelDataProvider('daily');
                return $this->export_new($model, $excelDataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));
            default:
                $monthlyItems = $model->getQuery()
                        ->groupBy([
                            'rec_date',
                        ])->orderBy([
                            'rec_date' => SORT_ASC,
                        ])
                        ->indexBy('rec_date')
                        ->asArray()
                        ->all();

                $folderItems = $model->getQuery()
                        ->addSelect([
                            'folder.name as folder_name',
                        ])
                        ->join('INNER JOIN', 'product_folder', 'product_folder.product_id = report_sell_product_daily.product_id')
                        ->join('INNER JOIN', 'folder', 'folder.id = product_folder.folder_id')
                        ->groupBy([
                            'product_folder.folder_id',
                        ])
                        ->orderBy([
                            'rec_amount' => SORT_DESC
                        ])
                        ->asArray()
                        ->all();


                $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, Helper::getDateArray($model->fromDate, $model->toDate), 'ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));
                $pieChartOptions = $this->getPieChartOptions($model, $folderItems);

                return $this->render('daily', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                            'areaChartOptions' => $areaChartOptions,
                            'pieChartOptions' => $pieChartOptions,
                ]);
        }
    }

    public function actionWeekly() {

        $model = new ReportSellProductDaily;
        $model->from_date = date('Y-m-01');
        $model->to_date = date('Y-m-t');
        $model->load(Yii::$app->request->get());

        $dataProvider = $model->summaryByProduct();

        $coreQuery = $model->getQuery();
        $summary = $coreQuery->one();

        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                return $this->export($model, $dataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));

                // $excelDataProvider = $model->getExcelDataProvider('weekly');
                // return $this->export($model, $excelDataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date), 'สัปดาห์');
            default:
                $monthlyItems = $model->getQuery()
                        ->addSelect([
                            'DATE_FORMAT(DATE(rec_date),"%Y-%u") as rec_date'
                        ])
                        ->groupBy([
                            'DATE_FORMAT(DATE(rec_date),"%Y-%u")',
                        ])->orderBy([
                            'DATE_FORMAT(DATE(rec_date),"%Y-%u")' => SORT_DESC,
                        ])
                        ->indexBy('rec_date')
                        ->asArray()
                        ->all();

                $folderItems = $model->getQuery()
                        ->addSelect([
                            'folder.name as folder_name',
                        ])
                        ->join('INNER JOIN', 'product_folder', 'product_folder.product_id = report_sell_product_daily.product_id')
                        ->join('INNER JOIN', 'folder', 'folder.id = product_folder.folder_id')
                        ->groupBy([
                            'product_folder.folder_id',
                        ])
                        ->orderBy([
                            'rec_amount' => SORT_DESC
                        ])
                        ->asArray()
                        ->all();


                $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, Helper::getWeekArray($model->fromDate, $model->toDate), 'รายสัปดาห์');
                $pieChartOptions = $this->getPieChartOptions($model, $folderItems);

                return $this->render('weekly', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                            'areaChartOptions' => $areaChartOptions,
                            'pieChartOptions' => $pieChartOptions,
                ]);
        }
    }

    public function actionMonthly() {

        $years = [];
        $min_year = ReportSellProductMonthly::find()->select(['COALESCE(MIN(YEAR(rec_date)),YEAR(NOW()))'])->scalar();
        for ($y = date('Y'); $y >= $min_year; $y--) {
            $years[$y] = $y;
        }

        $model = new ReportSellProductMonthly;
        $model->from_year = date('Y');
        $model->from_month = 1;
        $model->to_year = date('Y');
        $model->to_month = date('n');
        $model->load(Yii::$app->request->get());

        $dataProvider = $model->summaryByProduct();

        $coreQuery = $model->getQuery();
        $summary = $coreQuery->one();

        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                return $this->export($model, $dataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));

                // $excelDataProvider = $model->getExcelDataProvider('monthly');
                // return $this->export($model, $excelDataProvider, $summary, 'ข้อมูลการสั่งซื้อ ' . $model->textMonthRange);
            default:
                $monthlyItems = $model->getQuery()
                        ->groupBy([
                            'rec_date',
                        ])->orderBy([
                            'rec_date' => SORT_DESC,
                        ])
                        ->indexBy('rec_date')
                        ->asArray()
                        ->all();

                $folderItems = $model->getQuery()
                        ->addSelect([
                            'folder.name as folder_name',
                        ])
                        ->join('INNER JOIN', 'product_folder', 'product_folder.product_id = report_sell_product_monthly.product_id')
                        ->join('INNER JOIN', 'folder', 'folder.id = product_folder.folder_id')
                        ->groupBy([
                            'product_folder.folder_id',
                        ])
                        ->orderBy([
                            'rec_amount' => SORT_DESC
                        ])
                        ->asArray()
                        ->all();


                $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, Helper::getMonthArray($model->fromDate, $model->toDate), $model->textMonthRange);
                $pieChartOptions = $this->getPieChartOptions($model, $folderItems);

                return $this->render('monthly', [
                            'model' => $model,
                            'years' => $years,
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                            'areaChartOptions' => $areaChartOptions,
                            'pieChartOptions' => $pieChartOptions,
                ]);
        }
    }

    public function actionYearly() {

        $years = [];
        $min_year = ReportSellProductMonthly::find()->select(['COALESCE(MIN(YEAR(rec_date)),YEAR(NOW()))'])->scalar();
        for ($y = date('Y'); $y >= $min_year; $y--) {
            $years[$y] = $y;
        }

        $model = new ReportSellProductMonthly;
        $model->from_year = $min_year;
        $model->from_month = 1;
        $model->to_year = date('Y');
        $model->to_month = 12;
        $model->load(Yii::$app->request->get());

        $dataProvider = $model->summaryByProduct();

        $coreQuery = $model->getQuery();
        $summary = $coreQuery->one();

        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                return $this->export($model, $dataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่วันที่ ' . ($model->from_date) . ' ถึง ' . ($model->to_date));

                // $excelDataProvider = $model->getExcelDataProvider('yearly');
                // return $this->export($model, $excelDataProvider, $summary, 'ข้อมูลการสั่งซื้อ ตั้งแต่ ' . ($model->from_year) . ' ถึง ' . ($model->to_year), 'ปีั');
            default:
                $monthlyItems = $model->getQuery()
                        ->addSelect([
                            'YEAR(rec_date) as rec_date'
                        ])
                        ->groupBy([
                            'YEAR(rec_date)',
                        ])->orderBy([
                            'YEAR(rec_date)' => SORT_DESC,
                        ])
                        ->indexBy('rec_date')
                        ->asArray()
                        ->all();

                $folderItems = $model->getQuery()
                        ->addSelect([
                            'folder.name as folder_name',
                        ])
                        ->join('INNER JOIN', 'product_folder', 'product_folder.product_id = report_sell_product_monthly.product_id')
                        ->join('INNER JOIN', 'folder', 'folder.id = product_folder.folder_id')
                        ->groupBy([
                            'product_folder.folder_id',
                        ])
                        ->orderBy([
                            'rec_amount' => SORT_DESC
                        ])
                        ->asArray()
                        ->all();


                $range = [];
                for ($y = $model->from_year; $y <= $model->to_year; $y++) {
                    $range[$y] = $y;
                }

                $areaChartOptions = $this->getAreaChartOptions($model, $monthlyItems, $range, $model->textMonthRange);
                $pieChartOptions = $this->getPieChartOptions($model, $folderItems);

                return $this->render('yearly', [
                            'model' => $model,
                            'years' => $years,
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                            'areaChartOptions' => $areaChartOptions,
                            'pieChartOptions' => $pieChartOptions,
                ]);
        }
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

    public function getPieChartOptions($model, $items) {
        $areaPlotData = [];
        $categories = [];

        foreach ($items as $count => $item) {
            $areaPlotData[] = (int) $item['rec_amount'];
            $categories[] = $item['folder_name'];
            if ($count > 10) {
                break;
            }
        }

        return [
            'chart' => [
                'type' => 'bar'
            ],
            'title' => [
                'text' => 'สินค้าขายดี 10 อันดับแรก'
            ],
            'subtitle' => [
                'text' => 'จำแนกตามประเภท'
            ],
            'yAxis' => [
                'title' => [
                    'text' => 'จำนวนเงิน (บาท)',
                ],
            ],
            'xAxis' => [
                'title' => [
                    'text' => 'ประเภทสินค้า',
                ],
                'categories' => $categories,
            ],
            'series' => [
                [
                    'name' => 'ยอดขายสินค้า',
                    'data' => $areaPlotData,
                ],
            ],
        ];
    }
    public function export_new($model, $dataProvider, $summary, $title = null, $period_header = 'เดือน-ปี') {
        Yii::$app->response->format = Response::FORMAT_RAW;
        if(gettype($dataProvider) =='object'){
            $dataProvider->pagination = false;
            $excelData = $dataProvider->models;
        }else{
            $excelData = $dataProvider;
        }

        $spreadsheet = new Spreadsheet;
        $spreadsheet->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Cordia New',
                'size' => 14,
            ],
        ]);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report');
        $row = 1;

        $sheet->setCellValueByColumnAndRow(1, $row, $title);
        $sheet->mergeCellsByColumnAndRow(1, $row, 9, $row);
        $sheet->getStyleByColumnAndRow(1, $row, 9, $row)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => array('rgb' => 'C4D79B'),
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('rgb' => '333333'),
                ],
            ],
        ]);
        $row++;

        $sheet->setCellValueByColumnAndRow(1, $row, 'วันที่ออเดอร์');
        $sheet->setCellValueByColumnAndRow(2, $row, $period_header);
        $sheet->setCellValueByColumnAndRow(3, $row, 'ปีที่พิมพ์');
        $sheet->setCellValueByColumnAndRow(4, $row, 'กลุ่มหนังสือ');
        $sheet->setCellValueByColumnAndRow(5, $row, 'ประเภทเนื้อหา');
        $sheet->setCellValueByColumnAndRow(6, $row, 'SKU');
        $sheet->setCellValueByColumnAndRow(7, $row, 'ชื่อสินค้า');
        $sheet->setCellValueByColumnAndRow(8, $row, 'ยอดขาย');
        $sheet->setCellValueByColumnAndRow(9, $row, 'จำนวนสั่งซื้อ');
        for ($i = 1; $i <= 9; $i++) {
            $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D8E4BC'),
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '333333'),
                    ],
                ],
            ]);
        }
        $row++;
        foreach ($excelData as $item) {
            $sheet->setCellValueExplicitByColumnAndRow(1, $row, ArrayHelper::getValue($item, 'rec_date'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(2, $row, ArrayHelper::getValue($item, 'period_sold'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(3, $row, ArrayHelper::getValue($item, 'info_publish'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(4, $row, ArrayHelper::getValue($item, 'folder_type'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(5, $row, ArrayHelper::getValue($item, 'folder_name'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(6, $row, ArrayHelper::getValue($item, 'sku'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(7, $row, ArrayHelper::getValue($item, 'name'), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(8, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($item, 'rec_amount'), 2), DataType::TYPE_STRING);
            $sheet->setCellValueExplicitByColumnAndRow(9, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($item, 'rec_count'), 0), DataType::TYPE_STRING);
            for ($i = 6; $i <= 7; $i++) {
                $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb' => '333333'),
                        ],
                    ],
                ]);
            }
            for ($i = 8; $i <= 9; $i++) {
                $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb' => '333333'),
                        ],
                    ],
                ]);
            }
            $row++;
        }
        $sheet->setCellValueByColumnAndRow(1, $row, 'รวม');
        $sheet->mergeCellsByColumnAndRow(1, $row, 2, $row);
        $sheet->getStyleByColumnAndRow(1, $row, 2, $row)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => array('rgb' => '333333'),
                ],
            ],
        ]);
        $sheet->setCellValueExplicitByColumnAndRow(8, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($summary, 'rec_amount'), 2), DataType::TYPE_STRING);
        $sheet->setCellValueExplicitByColumnAndRow(9, $row, Yii::$app->formatter->asDecimal(ArrayHelper::getValue($summary, 'rec_count'), 0), DataType::TYPE_STRING);
        for ($i = 8; $i <= 9; $i++) {
            $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => array('rgb' => '333333'),
                    ],
                ],
            ]);
        }
        $row++;

        for ($col = 1; $col <= Coordinate::columnIndexFromString($sheet->getHighestDataColumn()); $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');
        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        $content = ob_get_clean();
        return $content;
    }
}
