<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

use app\components\Helper;
use app\models\LoginForm;
use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\authclient\widgets\AuthChoice;
use yii\web\View;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <?=
    Breadcrumbs::widget([
        'enableSearch' => false,
        'items' => [
            [
                'label' => 'เข้าสู่ระบบ',
                'url' => ['index'],
            ],
        ],
    ]);
    ?>
    <div class="site-login container">
        <div class="row">
            <div class="col-sm-6 col-sm-push-6">
                <?php
                Page::begin([
                    'title' => 'เข้าสู่ระบบสมาชิก',
                    'subtitle' => 'กรุณาเข้าสู่ระบบสมาชิก',
                ])
                ?>
                <?php
                $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'type' => 'horizontal',
                            'enableClientValidation' => false,
                            'options' => [
                                'style' => [
                                    'margin-bottom' => '50px',
                                ],
                            ],
                ]);
                ?>
                <?=
                $form->field($model, 'username', [
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('envelope'),
                        ],
                    ],
                ])->textInput([
                    'autofocus' => true,
                    'placeholder' => 'กรุณาระบุอีเมล์',
                ])
                ?>
                <?=
                $form->field($model, 'password', [
                    'addon' => [
                        'prepend' => [
                            'content' => Html::icon('lock'),
                        ],
                    ],
                ])->passwordInput([
                    'placeholder' => 'กรุณาระบุรหัสผ่าน',
                ])
                ?>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <?= Html::submitButton('เข้าสู่ระบบ', ['class' => 'btn btn-block btn-primary', 'name' => 'login-button']) ?>
                        <h4 class="text-center">หรือ<br/><small>เชื่อมต่อกับบัญชีออนไลน์</small></h4>
                        <div class="text-center">
                            <?php
                            $authChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['site/auth']
                            ]);
                            ?>
                            <?php foreach ($authChoice->getClients() as $client): ?>
                                <?= $authChoice->clientLink($client, Html::awesome(Helper::getAuthClientOptions($client->getId())) . ' ' . $client->getTitle(), ['class' => 'btn btn-block btn-' . $client->getName()]); ?>
                            <?php endforeach; ?>
                            <?php AuthChoice::end(); ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <?php Page::end() ?>
            </div>
            <div class="col-sm-6 col-sm-pull-6">
                <?php
                Page::begin([
                    'title' => 'สมัครสมาชิก',
                    'subtitle' => 'ลงทะเบียนเพื่อประหยัดเวลาของคุณ!<br/><small>ชำระเงินอย่างสะดวกและรวดเร็ว เข้าถึงข้อมูลประวัติคำสั่งซื้อและสถานะคำสั่งซื้อต่างๆ ได้ง่าย</small>',
                ])
                ?>
                <div class="text-right">
                    <?= Html::a('สร้างบัญชี', ['register/index'], ['class' => 'btn btn-primary']); ?>
                </div>
                <?php Page::end() ?>
                <hr/>
                <?php
                Page::begin([
                    'title' => 'ลืมรหัสผ่าน ?',
                    'subtitle' => '<small>คุณสามารถกู้คืนบัญชีของคุณได้โดยการยืนยันตัวตนจากอีเมล์ที่เคยลงทะเบียนไว้กับเรา</small>',
                ])
                ?>
                <div class="text-right">
                    <?= Html::a('ลืมรหัสผ่าน...', ['forgot/index'], ['class' => 'btn btn-default']) ?>
                </div>
                <?php Page::end() ?>
            </div>

        </div>
    </div>
</div>