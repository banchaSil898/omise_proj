<?php

namespace app\controllers;

use app\models\Member;
use Yii;

class FacebookController extends Controller {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }

    public function oAuthSuccess($client) {
        // get user data from client
        $userAttributes = $client->getUserAttributes();

        if (empty($userAttributes['email'])) {
            Yii::$app->session->setFlash('error', 'กรุณากด Allow Access ใน Facebook เพื่อใช้งาน Facebook Login');
            return $this->redirect('/site/login');
        }
        $user = Member::findOne(['username' => $userAttributes['email']]);

        if ($user) {//ถ้ามี user ในระบบแล้ว
            //echo 'user email';
            if ($user->status != Member::STATUS_NORMAL) {//ถ้าสถานะไม่ active ให้ active
                $user->status = Member::STATUS_NORMAL;
                $user->save();
            }

            if (!$user->firstname) {// ถ้าไม่มี profile ให้สร้างใหม่
                $name = explode(" ", $userAttributes['name']); // แยกชื่อ นามสกุล
                $user->firstname = $name[0];
                $user->lastname = $name[1];
                $user->save();
            }

            Yii::$app->user->login($user);
        } else {//ถ้าไม่มี user ในระบบ
            //echo 'none user';
            //$generate = Yii::$app->security->generateRandomString(10);
            $uname = explode("@", $userAttributes['email']); // แยกอีเมลล์ด้วย @
            $getuser = Member::findOne(['username' => $userAttributes['email']]);
            if ($getuser) {//มี username จาก username ที่ได้จาก email
                //echo 'exit user from username';
                $rand = rand(10, 99);
                $username = $uname[0] . $rand;
            } else {
                //echo 'none user from username';
                $username = $uname[0];
            }
            //echo $username;
            $new_user = new User();
            $new_user->username = $username;
            $new_user->auth_key = Yii::$app->security->generateRandomString();
            $new_user->password_hash = Yii::$app->security->generatePasswordHash($username);
            $new_user->email = $userAttributes['email'];
            $new_user->status = User::STATUS_ACTIVE;

            if ($new_user->save()) {
                //echo 'save user';
                $name = explode(" ", $userAttributes['name']); // แยกชื่อ นามสกุล
                $new_profile = new Profile();
                $new_profile->user_id = $new_user->id;
                $new_profile->firstname = $name[0];
                $new_profile->lastname = $name[1];
                $new_profile->save();
                Yii::$app->getUser()->login($new_user);
            } else {
                //echo 'not save user';
            }
        }
        //exit();
        // do some thing with user data. for example with $userAttributes['email']
    }

}
