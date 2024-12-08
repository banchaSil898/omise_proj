<?php

namespace app\modules\admin\controllers;

class CategoryController extends Controller {

    public function actionIndex() {
        $items = [
            'วรรณกรรมไทย',
            'วรรณกรรมแปล',
            'วรรณกรรมเยาวชน',
            'สารคดี',
            'สาระบันเทิง',
            'จิตวิทยา',
            'สุขภาพ',
            'ธุรกิจ',
            'บุคคล',
            'วิชาการ',
            'ศาสนา',
            'โหราศาสตร์',
            'อาชีพ',
            'ศิลปวัฒนธรรม',
            'การเมือง',
            'วิทยาศาสตร์',
            'เศรษฐศาสตร์',
            'ประวัติศาสตร์',
            'สิ่งแวดล้อม',
            'นิตยสารในเครือมติชน',
        ];
        return $this->render('index', [
                    'items' => $items,
        ]);
    }

}
