<?php

namespace app\commands;

use app\models\Member;
use app\models\MemberAddress;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\console\Controller;

class ExportController extends Controller {

    public function actionCustomer() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $query = Member::find();

        $row = 1;
        $sheet->setCellValueByColumnAndRow(1, $row, 'ID');
        $sheet->setCellValueByColumnAndRow(2, $row, 'MID');
        $sheet->setCellValueByColumnAndRow(3, $row, 'Username');
        $sheet->setCellValueByColumnAndRow(4, $row, 'Firstname');
        $sheet->setCellValueByColumnAndRow(5, $row, 'Lastname');
        $sheet->setCellValueByColumnAndRow(6, $row, 'BirthDay');
        $sheet->setCellValueByColumnAndRow(7, $row, 'Billing Address');
        $sheet->setCellValueByColumnAndRow(8, $row, 'Billing Phone');
        $sheet->setCellValueByColumnAndRow(9, $row, 'Shipping Address');
        $sheet->setCellValueByColumnAndRow(10, $row, 'Shipping Phone');
        $row++;
        foreach ($query->each(1000) as $count => $item) {
            $sheet->setCellValueByColumnAndRow(1, $row + $count, $item->id);
            $sheet->setCellValueByColumnAndRow(2, $row + $count, $item->magento_id);
            $sheet->setCellValueByColumnAndRow(3, $row + $count, $item->username);
            $sheet->setCellValueByColumnAndRow(4, $row + $count, $item->firstname);
            $sheet->setCellValueByColumnAndRow(5, $row + $count, $item->lastname);
            $sheet->setCellValueExplicitByColumnAndRow(6, $row + $count, $item->birth_date, DataType::TYPE_STRING);

            $addr1 = MemberAddress::findOne([
                        'magento_id' => $item->default_addr_billing,
            ]);
            if (isset($addr1)) {
                $sheet->setCellValueByColumnAndRow(7, $row + $count, $addr1->address);
                $sheet->setCellValueExplicitByColumnAndRow(8, $row + $count, $addr1->phone, DataType::TYPE_STRING);
            }

            $addr2 = MemberAddress::findOne([
                        'magento_id' => $item->default_addr_shipping,
            ]);
            if (isset($addr2)) {
                $sheet->setCellValueByColumnAndRow(9, $row + $count, $addr2->address);
                $sheet->setCellValueExplicitByColumnAndRow(10, $row + $count, $addr2->phone, DataType::TYPE_STRING);
            }
            echo ($count + 1) . ':' . $item->username . "\n";
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(Yii::getAlias('@app/web/assets') . '/customer-' . date('YmdHis') . '.xlsx');
    }

}
