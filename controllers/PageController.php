<?php

namespace app\controllers;

class PageController extends Controller {

    public $title = 'นโยบายคุ้มครองข้อมูลส่วนบุคคล';

    public function actionPrivacy() {
        return $this->render('privacy');
    }

}
