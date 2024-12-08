<?php

namespace app\modules\admin\controllers;

use app\components\Html;
use app\models\Purchase;
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

class ReportPurchaseController extends Controller {

    public function actionIndex() {
        $model = new Purchase;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
            'attributes' => [
                'purchase_no',
                'status',
                'price_total',
                'price_grand',
                'payment_method',
                'created_at',
                'transfer_datetime' => [
                    'asc' => [
                        'transfer_date' => SORT_ASC,
                        'transfer_time' => SORT_ASC,
                    ],
                    'desc' => [
                        'transfer_date' => SORT_DESC,
                        'transfer_time' => SORT_DESC,
                    ],
                ],
            ],
        ];
        switch (Yii::$app->request->get('mode')) {
            case 'xls':
                set_time_limit(0);
                ini_set('memory_limit', '2G');
                Yii::$app->response->format = Response::FORMAT_RAW;
                $dataProvider->pagination = false;

                $spreadsheet = new Spreadsheet();
                $spreadsheet->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' => 'Cordia New',
                        'size' => 14,
                    ],
                ]);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Report');
                $row = 1;

                $sheet->setCellValueByColumnAndRow(1, $row, 'สรุปรายงานการสั่งซื้อ');
                $sheet->mergeCellsByColumnAndRow(1, $row, 11, $row);
                $sheet->getStyleByColumnAndRow(1, $row, 11, $row)->applyFromArray([
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

                $sheet->setCellValueByColumnAndRow(1, $row, 'เลขที่ใบสั่งซื้อ');
                $sheet->setCellValueByColumnAndRow(2, $row, 'ที่อยู่ออกใบเสร็จ');
                $sheet->setCellValueByColumnAndRow(3, $row, 'ที่อยู่ส่งสินค้า');
                $sheet->setCellValueByColumnAndRow(4, $row, 'รายการ');
                $sheet->setCellValueByColumnAndRow(5, $row, 'ราคาปก');
                $sheet->setCellValueByColumnAndRow(6, $row, 'Price');
                $sheet->setCellValueByColumnAndRow(7, $row, 'QTY');
                $sheet->setCellValueByColumnAndRow(8, $row, 'รวม');
                $sheet->setCellValueByColumnAndRow(9, $row, 'Total');
                $sheet->setCellValueByColumnAndRow(10, $row, 'ส่วนลด');
                $sheet->setCellValueByColumnAndRow(11, $row, 'วิธีส่ง');
                for ($i = 1; $i <= 11; $i++) {
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

                foreach ($dataProvider->models as $purchase) {
                    $products = $purchase->purchaseProducts;

                    /* Clear data */
                    $purchase->invoice_idcard = trim(strtr($purchase->invoice_idcard, ['-' => '']));
                    $purchase->invoice_company = trim(strtr($purchase->invoice_company, ['-' => '']));
                    $purchase->invoice_tax = trim(strtr($purchase->invoice_tax, ['-' => '']));

                    $purchase->delivery_idcard = trim(strtr($purchase->delivery_idcard, ['-' => '']));
                    $purchase->delivery_company = trim(strtr($purchase->delivery_company, ['-' => '']));
                    $purchase->delivery_tax = trim(strtr($purchase->delivery_tax, ['-' => '']));

                    $r = 1;

                    $sheet->setCellValueByColumnAndRow(1, $row, ArrayHelper::getValue($purchase, 'purchase_no'));
                    $sheet->setCellValueExplicitByColumnAndRow(2, $row, ArrayHelper::getValue($purchase, 'invoice_firstname') . ' ' . ArrayHelper::getValue($purchase, 'invoice_lastname'), DataType::TYPE_STRING);
                    if ($purchase->invoice_idcard) {
                        $sheet->setCellValueExplicitByColumnAndRow(2, $row + $r, 'บัตรประชาชน : ' . ArrayHelper::getValue($purchase, 'invoice_idcard'), DataType::TYPE_STRING);
                        $r++;
                    }
                    if ($purchase->invoice_company) {
                        $sheet->setCellValueExplicitByColumnAndRow(2, $row + $r, ArrayHelper::getValue($purchase, 'invoice_company') . ($purchase->invoice_tax <> '' ? ' (' . $purchase->invoice_tax . ')' : ''), DataType::TYPE_STRING);
                        $r++;
                        if ($purchase->invoice_tax) {
                            $sheet->setCellValueExplicitByColumnAndRow(2, $row + $r, 'หมายเลขผู้เสียภาษี : ' . ArrayHelper::getValue($purchase, 'invoice_tax'), DataType::TYPE_STRING);
                            $r++;
                        }
                    }

                    $sheet->setCellValueByColumnAndRow(2, $row + $r, ArrayHelper::getValue($purchase, 'invoice_address'));
                    $r++;
                    if (ArrayHelper::getValue($purchase, 'invoice_tambon') || ArrayHelper::getValue($purchase, 'invoice_amphur')) {
                        $sheet->setCellValueByColumnAndRow(2, $row + $r, implode('', [
                            ArrayHelper::getValue($purchase, 'invoice_tambon') ? 'แขวง/ตำบล ' . ArrayHelper::getValue($purchase, 'invoice_tambon') : '',
                            ArrayHelper::getValue($purchase, 'invoice_amphur') ? 'เขต/อำเภอ ' . ArrayHelper::getValue($purchase, 'invoice_amphur') : '',
                        ]));
                        $r++;
                    }
                    $sheet->setCellValueByColumnAndRow(2, $row + $r, ArrayHelper::getValue($purchase, 'invoice_province') . ' ' . ArrayHelper::getValue($purchase, 'invoice_postcode') . Html::encode(ArrayHelper::getValue($purchase, 'invoiceCountry.locName', $purchase->invoice_country)));
                    $r++;
                    $sheet->setCellValueByColumnAndRow(2, $row + $r, 'โทร.' . ArrayHelper::getValue($purchase, 'invoice_phone'));
                    $r++;

                    $sheet->setCellValueByColumnAndRow(2, $row + $r, ArrayHelper::getValue($purchase, 'invoice_comment'));
                    $r++;

                    $usedRows = count($products) > ($r) ? count($products) : ($r);

                    # Delivery
                    $r = 1;
                    $sheet->setCellValueByColumnAndRow(3, $row, ArrayHelper::getValue($purchase, 'delivery_firstname') . ' ' . ArrayHelper::getValue($purchase, 'delivery_lastname'));
                    if ($purchase->delivery_company) {
                        $sheet->setCellValueByColumnAndRow(3, $row + $r, ArrayHelper::getValue($purchase, 'delivery_company'));
                        $r++;
                    }
                    $sheet->setCellValueByColumnAndRow(3, $row + $r, ArrayHelper::getValue($purchase, 'delivery_address'));
                    $r++;
                    if (ArrayHelper::getValue($purchase, 'delivery_tambon') || ArrayHelper::getValue($purchase, 'delivery_tambon')) {
                        $sheet->setCellValueByColumnAndRow(3, $row + $r, implode('', [
                            ArrayHelper::getValue($purchase, 'delivery_tambon') ? ' แขวง/ตำบล ' . ArrayHelper::getValue($purchase, 'delivery_tambon') : '',
                            ArrayHelper::getValue($purchase, 'delivery_amphur') ? ' เขต/อำเภอ ' . ArrayHelper::getValue($purchase, 'delivery_amphur') : '',
                        ]));
                        $r++;
                    }
                    $sheet->setCellValueByColumnAndRow(3, $row + $r, ArrayHelper::getValue($purchase, 'delivery_province') . ' ' . ArrayHelper::getValue($purchase, 'delivery_postcode') . Html::encode(ArrayHelper::getValue($purchase, 'deliveryCountry.locName', $purchase->delivery_country)));
                    $r++;
                    $sheet->setCellValueByColumnAndRow(3, $row + $r, 'โทร.' . ArrayHelper::getValue($purchase, 'delivery_phone'));
                    $r++;
                    $sheet->setCellValueByColumnAndRow(3, $row + $r, ArrayHelper::getValue($purchase, 'delivery_comment'));
                    $r++;

                    foreach ($products as $cRow => $product) {
                        $sheet->setCellValueExplicitByColumnAndRow(4, $row + $cRow, ArrayHelper::getValue($product, 'product.name'), DataType::TYPE_STRING);
                        $sheet->setCellValueExplicitByColumnAndRow(5, $row + $cRow, ArrayHelper::getValue($product, 'product.price'), DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicitByColumnAndRow(6, $row + $cRow, ArrayHelper::getValue($product, 'price_total'), DataType::TYPE_NUMERIC);
                        $sheet->setCellValueExplicitByColumnAndRow(7, $row + $cRow, ArrayHelper::getValue($product, 'amount'), DataType::TYPE_NUMERIC);
                    }

                    $sheet->setCellValueExplicitByColumnAndRow(8, $row, ArrayHelper::getValue($purchase, 'price_total'), DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(9, $row, ArrayHelper::getValue($purchase, 'price_grand'), DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(10, $row, ArrayHelper::getValue($purchase, 'order_note'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(11, $row, ArrayHelper::getValue($purchase, 'deliveryText'), DataType::TYPE_STRING);

                    for ($i = 1; $i <= 11; $i++) {
                        $sheet->getStyleByColumnAndRow($i, $row, $i, $row + $usedRows - 1)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => array('rgb' => '333333'),
                                ],
                            ],
                        ]);
                    }

                    $row = $row + $usedRows;
                }

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
            default:
                return $this->render('index', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                ]);
        }
    }

}
