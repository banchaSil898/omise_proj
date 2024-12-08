<?php

namespace app\modules\admin\controllers;

use app\models\Member;
use app\models\MemberAddress;
use app\models\Purchase;
use kartik\form\ActiveForm;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class MemberController extends Controller {

    public function actionIndex() {
        $model = new Member;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
        ];
        Url::remember('', 'member');
        switch (Yii::$app->request->get('mode')) {
            case 'xls':
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

                $sheet->setCellValueByColumnAndRow(1, $row, '#');
                $sheet->setCellValueByColumnAndRow(2, $row, 'รหัสสมาชิก');
                $sheet->setCellValueByColumnAndRow(3, $row, 'รหัสสมาชิกเดิม');
                $sheet->setCellValueByColumnAndRow(4, $row, 'ชื่อ');
                $sheet->setCellValueByColumnAndRow(5, $row, 'นามสกุล');
                $sheet->setCellValueByColumnAndRow(6, $row, 'อีเมล์');
                $sheet->setCellValueByColumnAndRow(7, $row, 'วันเกิด');
                $sheet->setCellValueByColumnAndRow(8, $row, 'Facebook');
                $sheet->setCellValueByColumnAndRow(9, $row, 'Google');
                $sheet->setCellValueByColumnAndRow(10, $row, 'Line');
                $sheet->setCellValueByColumnAndRow(11, $row, 'วันที่สมัครสมาชิก');
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

                foreach ($dataProvider->models as $count => $item) {

                    $sheet->setCellValueExplicitByColumnAndRow(1, $row, $count + 1, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(2, $row, ArrayHelper::getValue($item, 'id'), DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(3, $row, ArrayHelper::getValue($item, 'magento_id'), DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicitByColumnAndRow(4, $row, ArrayHelper::getValue($item, 'firstname'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(5, $row, ArrayHelper::getValue($item, 'lastname'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(6, $row, ArrayHelper::getValue($item, 'username'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(7, $row, ArrayHelper::getValue($item, 'birth_date'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(8, $row, ArrayHelper::getValue($item, 'facebook_id'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(9, $row, ArrayHelper::getValue($item, 'google_id'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(10, $row, ArrayHelper::getValue($item, 'line_id'), DataType::TYPE_STRING);
                    $sheet->setCellValueExplicitByColumnAndRow(11, $row, ArrayHelper::getValue($item, 'created_at'), DataType::TYPE_STRING);
                    for ($i = 1; $i <= 11; $i++) {
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

    public function actionCreate() {
        $model = new Member;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Member::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateAddress($id) {
        $model = Member::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-address', 'id' => $model->id]);
            }
        }

        $address = new MemberAddress;
        $address->scenario = 'search';
        $address->member_id = $model->id;
        $dataProvider = $address->search();

        return $this->render('form-address', [
                    'model' => $model,
                    'address' => $address,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdatePurchase($id) {
        $model = Member::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-address', 'id' => $model->id]);
            }
        }

        $purchase = new Purchase;
        $purchase->scenario = 'search';
        $purchase->member_id = $model->id;
        $dataProvider = $purchase->search();

        return $this->render('form-purchase', [
                    'model' => $model,
                    'purchase' => $purchase,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id) {
        $model = Member::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionToggleStatus($id, $attribute) {
        $model = Member::findOne($id);
        if ($model->{$attribute}) {
            $model->{$attribute} = 0;
        } else {
            $model->{$attribute} = 1;
        }
        $model->save();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionAddressCreate($id) {
        $model = Member::findOne($id);
        $address = new MemberAddress;
        $address->scenario = 'user-update';
        $address->firstname = $model->firstname;
        $address->lastname = $model->lastname;
        $address->country_id = 'TH';
        $address->member_id = $model->id;
        if ($address->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($address);
                default:
                    if ($address->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('address/form', [
                    'model' => $model,
                    'address' => $address,
        ]);
    }

    public function actionAddressUpdate($member_id, $address_id) {

        $address = MemberAddress::findOne([
                    'member_id' => $member_id,
                    'address_id' => $address_id,
        ]);
        $model = Member::findOne($address->member_id);
        if ($address->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($address);
                default:
                    if ($address->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('address/form', [
                    'model' => $model,
                    'address' => $address,
        ]);
    }

    public function actionAddressDelete($member_id, $address_id) {
        $address = MemberAddress::findOne([
                    'member_id' => $member_id,
                    'address_id' => $address_id,
        ]);
        $address->delete();
    }

    public function actionAddressPrimary($member_id, $address_id) {
        $address = MemberAddress::findOne([
                    'member_id' => $member_id,
                    'address_id' => $address_id,
        ]);
        $address->doPrimary();
    }

}
