<?php

namespace app\models;

use app\components\MicMailer;
use app\models\base\Member as BaseMember;
use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\IdentityInterface;

class Member extends BaseMember implements IdentityInterface {

    const STATUS_NEW = 0;
    const STATUS_NORMAL = 1;

    public $password;
    public $password_confirm;
    public $password_old;
    public $search;

    public static function encrypt($str) {
        return Yii::$app->security->generatePasswordHash($str);
    }

    public static function getKeyHash() {
        return sha1(time() . uniqid() . 'mic');
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'firstname' => 'ชื่อ',
            'lastname' => 'นามสกุล',
            'fullname' => 'ชื่อ-นามสกุล',
            'username' => 'อีเมล์',
            'birth_date' => 'วันเกิด',
            'password' => 'รหัสผ่าน',
            'password_confirm' => 'ยืนยันรหัสผ่าน',
            'password_old' => 'รหัสผ่านเดิม',
            'created_at' => 'เป็นลูกค้าตั้งแต่',
            'google_id' => 'Google ID',
            'facebook_id' => 'Facebook ID',
			'Phone' => 'เบอร์โทรศัพท์',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getAuthKey() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getMemberAddresses() {
        return $this->hasMany(MemberAddress::className(), ['member_id' => 'id']);
    }

    public function getDefaultBillingAddress() {
        return $this->hasOne(MemberAddress::className(), [
                    'member_id' => 'id',
                    'address_id' => 'default_addr_billing',
        ]);
    }

    public function getDefaultShippingAddress() {
        return $this->hasOne(MemberAddress::className(), [
                    'member_id' => 'id',
                    'address_id' => 'default_addr_shipping',
        ]);
    }

    /**
     * Get primary address of member.
     * @return MemberAddress
     */
    public function getPrimaryAddress() {
        return $this->getMemberAddresses()->where(['is_primary' => 1])->one();
    }

    public function validateAuthKey($authKey) {
        
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['firstname', 'lastname'], 'required'];
        $rules[] = [['password', 'password_confirm'], 'string', 'length' => [8, 128]];
        $rules[] = [['username'], 'email', 'on' => 'resend'];
        $rules[] = [['birth_date'], 'required', 'on' => 'updateProfile'];

        $rules[] = [['username', 'password'], 'required', 'on' => 'login'];
        $rules[] = [['username'], 'exist', 'on' => 'login'];
        $rules[] = [['password'], 'authenticate', 'on' => 'login'];

        $rules[] = [['password', 'password_confirm', 'birth_date'], 'required', 'on' => 'register'];
        $rules[] = [['password'], 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9]+)$/', 'on' => 'register', 'message' => 'รหัสผ่านต้องประกอบด้วยอักษรตัวพิมพ์ใหญ่หนึ่งตัว และตัวเลขหนึ่งตัว'];
        $rules[] = [['username'], 'email', 'on' => 'register'];
        $rules[] = [['username'], 'unique', 'on' => 'register'];
        $rules[] = [['password_confirm'], 'compare', 'compareAttribute' => 'password', 'on' => 'register'];

        $rules[] = [['password_old', 'password', 'password_confirm'], 'required', 'on' => 'change-password'];
        $rules[] = [['password_old'], 'authenticate', 'on' => 'change-password'];
        $rules[] = [['password_confirm'], 'compare', 'compareAttribute' => 'password', 'on' => 'change-password'];
        $rules[] = [['password'], 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9]+)$/', 'on' => 'change-password', 'message' => 'รหัสผ่านต้องประกอบด้วยอักษรตัวพิมพ์ใหญ่หนึ่งตัว และตัวเลขหนึ่งตัว'];

        $rules[] = [['password', 'password_confirm'], 'required', 'on' => 'reset'];
        $rules[] = [['password_confirm'], 'compare', 'compareAttribute' => 'password', 'on' => 'reset'];

        $rules[] = [['username'], 'email', 'on' => 'forgot'];
        $rules[] = [['username'], 'exist', 'on' => 'forgot'];

        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function authenticate($attribute) {
        $user = $this->findByUsername($this->username);
        if (!isset($user) || !$user->validatePassword($this->{$attribute})) {
            $this->addError($attribute, 'รหัสผ่านไม่ถูกต้อง');
        }
    }

    public function login() {
        if ($this->validate()) {
            $user = $this->findByUsername($this->username);
            return Yii::$app->user->login($user);
        }
        return false;
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->name = implode(' ', [$this->firstname, $this->lastname]);
            return true;
        }
    }

    public static function findByUsername($username) {
        return Member::findOne([
                    'username' => $username,
        ]);
    }

    public function search() {
        $query = $this->find();
        switch ($this->scenario) {
            case 'search':
                $query->andFilterWhere([
                    'or',
                    ['LIKE', 'username', $this->search['text']],
                    [
                        'or',
                        ['LIKE', 'firstname', $this->search['text']],
                        ['LIKE', 'lastname', $this->search['text']]
                    ]
                ]);

                if (isset($this->search['ids']) && $this->search['ids']) {
                    $ids = explode(',', $this->search['ids']);
                    if (count($ids)) {
                        $query->andWhere(['id' => $ids]);
                    }
                }

                if (isset($this->search['date_start']) && $this->search['date_start']) {
                    $query->andFilterWhere(['>=', 'DATE(created_at)', $this->search['date_start']]);
                }
                if (isset($this->search['date_end']) && $this->search['date_end']) {
                    $query->andFilterWhere(['<=', 'DATE(created_at)', $this->search['date_end']]);
                }
                break;
        }
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getFullname() {
        return implode(' ', [$this->firstname, $this->lastname]);
    }

    public function doActive() {
        $this->account_key = null;
        $this->active_code = null;
        $this->status = self::STATUS_NORMAL;
        if ($this->save()) {
            $mail = new MicMailer;
            $mail->setView('welcome', [
                '{{name}}' => $this->getFullname(),
            ]);
            $mail->send([$this->username]);
            return true;
        }
    }

    public function doResend() {
        $model = Member::findOne([
                    'username' => $this->username,
        ]);
        if (isset($model)) {
            $model->updateAttributes([
                'account_key' => self::getKeyHash(),
                'active_code' => self::getKeyHash(),
            ]);
            $mail = new MicMailer;
            $mail->setView('register', [
                '{{name}}' => $this->getFullname(),
                '{{link}}' => Html::a(Url::to(['register/activate', 'code' => $model->active_code, 'key' => $model->account_key], true), Url::to(['register/activate', 'code' => $model->active_code, 'key' => $model->account_key], true)),
            ]);
            $mail->send([$model->username]);
            return true;
        } else {
            $this->addError('email', 'ไม่พบบัญชี ' . Html::encode($this->username) . ' ในระบบ');
            return false;
        }
    }

    public function doRegister() {
        if ($this->save()) {
            $this->updateAttributes([
                'secret' => self::encrypt($this->password),
                'account_key' => self::getKeyHash(),
                'active_code' => self::getKeyHash(),
                'status' => self::STATUS_NEW,
            ]);
            $mail = new MicMailer;
            $mail->setView('register', [
                '{{name}}' => $this->getFullname(),
                '{{link}}' => Html::a(Url::to(['register/activate', 'code' => $this->active_code, 'key' => $this->account_key], true), Url::to(['register/activate', 'code' => $this->active_code, 'key' => $this->account_key], true)),
            ]);
            $mail->send([$this->username]);
            return true;
        }
    }

    public function doForgot() {
        $member = Member::findOne([
                    'username' => $this->username,
        ]);
        if (!isset($member)) {
            $this->addError('username', 'ไม่พบบัญชีของคุณในระบบ กรุณาติดต่อเจ้าหน้าที่');
            return false;
        }

        $member->updateAttributes([
            'account_key' => self::getKeyHash(),
            'forgot_code' => self::getKeyHash(),
            'forgot_at' => new Expression('NOW()'),
        ]);

        $mail = new MicMailer;
        $mail->setView('forgot', [
            '{{name}}' => $member->username,
            '{{link}}' => Html::a(Url::to(['forgot/reset', 'code' => $member->forgot_code, 'key' => $member->account_key], true), Url::to(['forgot/reset', 'code' => $member->forgot_code, 'key' => $member->account_key], true)),
        ]);
        $mail->send([$member->username]);
        return true;
    }

    public function doReset() {
        $this->secret = self::encrypt($this->password);
        if ($this->save()) {
            $this->updateAttributes([
                'account_key' => null,
                'forgot_code' => null,
                'forgot_at' => null,
            ]);
            return true;
        }
    }

    public function doChangePassword() {
        $this->secret = self::encrypt($this->password);
        if ($this->save()) {
            return true;
        }
    }

    public function validatePassword($password) {
        try {
            return Yii::$app->security->validatePassword($password, $this->secret);
        } catch (InvalidArgumentException $e) {
            return false;
        }
        return false;
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['firstname', 'lastname', 'username', 'password', 'password_confirm', 'birth_date'];
        $scenarios['register-facebook'] = ['firstname', 'lastname', 'username', 'name', 'status', 'facebook_id', 'secret'];
        $scenarios['register-google'] = ['firstname', 'lastname', 'username', 'name', 'status', 'google_id', 'secret'];
        $scenarios['register-line'] = ['firstname', 'lastname', 'username', 'name', 'status', 'line_id', 'secret'];
        $scenarios['active'] = ['account_key', 'active_code', 'status'];
        $scenarios['resend'] = ['username'];
        return $scenarios;
    }

    public function doRegisterLine($attributes) {
        $this->scenario = 'register-line';
        $this->username = $this->username ? $this->username : ArrayHelper::getValue($attributes, 'email');
        $this->firstname = $this->firstname ? $this->firstname : ArrayHelper::getValue($attributes, 'first_name');
        $this->lastname = $this->lastname ? $this->lastname : ArrayHelper::getValue($attributes, 'last_name');
        $this->name = $this->name ? $this->name : ArrayHelper::getValue($attributes, 'displayName');
        $this->is_need_register = 1;

        if ($this->isNewRecord) {
            $this->status = self::STATUS_NORMAL;
        }
        if ($this->line_id <> ArrayHelper::getValue($attributes, 'id')) {
            $this->line_id = ArrayHelper::getValue($attributes, 'id');
        }
        if (!$this->secret) {
            $this->secret = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomKey());
        }
        return $this->save();
    }

    public function doRegisterFacebook($attributes) {
        $this->scenario = 'register-facebook';
        $this->username = $this->username ? $this->username : ArrayHelper::getValue($attributes, 'email');
        $this->firstname = $this->firstname ? $this->firstname : ArrayHelper::getValue($attributes, 'first_name');
        $this->lastname = $this->lastname ? $this->lastname : ArrayHelper::getValue($attributes, 'last_name');
        $this->name = $this->name ? $this->name : ArrayHelper::getValue($attributes, 'name');

        if ($this->isNewRecord) {
            $this->status = self::STATUS_NORMAL;
        }
        if ($this->facebook_id <> ArrayHelper::getValue($attributes, 'id')) {
            $this->facebook_id = ArrayHelper::getValue($attributes, 'id');
        }
        if (!$this->secret) {
            $this->secret = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomKey());
        }
        return $this->save();
    }

    public function doRegisterGoogle($attributes) {
        $this->scenario = 'register-google';
        $this->username = $this->username ? $this->username : ArrayHelper::getValue($attributes, 'email');
        $this->firstname = $this->firstname ? $this->firstname : ArrayHelper::getValue($attributes, 'given_name');
        $this->lastname = $this->lastname ? $this->lastname : ArrayHelper::getValue($attributes, 'family_name');
        $this->name = $this->name ? $this->name : ArrayHelper::getValue($attributes, 'name');

        if ($this->isNewRecord) {
            $this->status = self::STATUS_NORMAL;
        }
        if ($this->google_id <> ArrayHelper::getValue($attributes, 'id')) {
            $this->google_id = ArrayHelper::getValue($attributes, 'id');
        }
        if (!$this->secret) {
            $this->secret = Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomKey());
        }
        return $this->save();
    }

}
