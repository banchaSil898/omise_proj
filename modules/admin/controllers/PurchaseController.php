<?php

namespace app\modules\admin\controllers;

use app\models\Purchase;
use app\models\PurchaseStatus;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class PurchaseController extends Controller {

    public function actionIndex() {
        $model = new Purchase;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
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

                $sheet->setCellValueByColumnAndRow(1, $row, 'เลขที่ใบสั่งซื้อ');
                $sheet->setCellValueByColumnAndRow(2, $row, 'ชื่อผู้สั่งซื้อ');
                $sheet->setCellValueByColumnAndRow(3, $row, 'สถานะ');
                $sheet->setCellValueByColumnAndRow(4, $row, 'หมายเหตุ');
                $sheet->setCellValueByColumnAndRow(5, $row, 'ประเภทล็อคอิน');
                $sheet->setCellValueByColumnAndRow(6, $row, 'อีเมล์');
                $sheet->setCellValueByColumnAndRow(7, $row, 'จำนวนเงินที่ต้องชำระ');
                $sheet->setCellValueByColumnAndRow(8, $row, 'วันที่สั่งซื้อ');
                for ($i = 1; $i <= 8; $i++) {
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

                    $sheet->setCellValueByColumnAndRow(1, $row, ArrayHelper::getValue($purchase, 'purchase_no'));
                    $sheet->setCellValueByColumnAndRow(2, $row, ArrayHelper::getValue($purchase, 'buyerFullname'));
                    $sheet->setCellValueByColumnAndRow(3, $row, Purchase::getStatusOptions(ArrayHelper::getValue($purchase, 'status')));
                    $sheet->setCellValueExplicitByColumnAndRow(4, $row, implode(', ', [ArrayHelper::getValue($purchase, 'status_comment'), ArrayHelper::getValue($purchase, 'order_note')]), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(5, $row, ArrayHelper::getValue($purchase, 'login_type'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(6, $row, ArrayHelper::getValue($purchase, 'buyer_email'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(7, $row, ArrayHelper::getValue($purchase, 'price_grand'), DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(8, $row, ArrayHelper::getValue($purchase, 'created_at'), DataType::TYPE_STRING);
                    for ($i = 1; $i <= 8; $i++) {
                        $sheet->getStyleByColumnAndRow($i, $row)->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => array('rgb' => '333333'),
                                ],
                            ],
                        ]);
                    }
                    $row++;
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

    public function actionViewTransfer($id) {
        $model = Purchase::findOne($id);

        $ext = strtolower(pathinfo($model->transfer_file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
            $content = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents(Yii::getAlias('@app/uploads/transfer/' . $model->transfer_file)));
        } else {
            $content = null;
        }
        return $this->renderAjax('view-transfer', [
                    'model' => $model,
                    'content' => $content,
        ]);
    }

    public function actionDownloadTransfer($id) {
        $model = Purchase::findOne($id);
        return Yii::$app->response->sendFile(Yii::getAlias('@app/uploads/transfer/' . $model->transfer_file))->send();
    }

    public function actionCreate() {
        $model = new Purchase;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Purchase::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateStatus($id) {
        $model = Purchase::findOne($id);

        $state = new PurchaseStatus;
        $state->purchase_id = $model->id;
        $state->is_sendmail = 1;
        if ($state->load(Yii::$app->request->post())) {
            if ($state->save()) {
                return $this->redirect(['update-status', 'id' => $model->id]);
            }
        }

        $query = PurchaseStatus::find();
        $query->andWhere([
            'purchase_id' => $model->id,
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('form-status', [
                    'model' => $model,
                    'state' => $state,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateLog($id) {
        $model = Purchase::findOne($id);

        return $this->render('form-log', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateCart($id) {
        $model = Purchase::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update-cart', 'id' => $model->id]);
            }
        }
        return $this->render('form-cart', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateCustomer($id) {
        $model = Purchase::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update-customer', 'id' => $model->id]);
            }
        }
        return $this->render('form-customer', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Purchase::findOne($id);
        $model->delete();
    }

}
