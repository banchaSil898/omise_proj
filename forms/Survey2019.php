<?php

namespace app\forms;

use app\models\Member;
use Yii;
use yii\base\Model;

class Survey2019 extends Model {

    public $enabled = false;
    public $flag;
    public $age;
    public $graduate;
    public $comment;

    public static function getAgeOptions($code = null) {
        $ret = [
            '15' => 'ต่ำกว่า 20 ปี',
            '20' => '20-25 ปี',
            '25' => '26-30 ปี',
            '31' => '31-35 ปี',
            '36' => '35 ปีขึ้นไป',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getGraduateOptions($code = null) {
        $ret = [
            'early' => 'ต่ำกว่าปริญญาตรี',
            'bachelor' => 'ปริญญาตรี',
            'master' => 'ปริญญาโท',
            'phd' => 'สูงกว่าปริญญาโท',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function attributeLabels(): array {
        return [
            'age' => 'ช่วงอายุ',
            'graduate' => 'การศึกษา',
            'comment' => 'คำถาม',
            'flag' => 'การอนุญาตสำรวจข้อมูล',
        ];
    }

    public function doMarkSession() {
        $m = Yii::$app->session->get('survey_2019');
        if (is_array($m)) {
            $m['flag'] = true;
        } else {
            $m = [
                'flag' => true,
            ];
        }
        Yii::$app->session->set('survey_2019', $m);
    }

    public function rules(): array {
        $rules = parent::rules();
        $rules[] = [['age', 'graduate'], 'required'];
        $rules[] = [['age', 'comment', 'graduate'], 'safe'];
        return $rules;
    }

    public function save() {
        $user = Yii::$app->user;
        if (!$user->isGuest) {
            /* @var $model Member */
            $model = $user->identity;
            $model->updateAttributes([
                'survey_2019_allow' => 1,
                'survey_2019_age' => $this->age,
                'survey_2019_graduate' => $this->graduate,
                'survey_2019_comment' => $this->comment,
            ]);
        }
        Yii::$app->session->set('survey_2019', [
            'flag' => true,
            'allow' => true,
            'age' => $this->age,
            'graduate' => $this->graduate,
            'comment' => $this->comment,
        ]);
        return true;
    }

}
