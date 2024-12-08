<?php

namespace app\models;

use app\models\base\Account as BaseAccount;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class Account extends BaseAccount implements IdentityInterface {

    const ROLE_ADMIN = 9;
    const ROLE_SALE = 5;

    public $search;
    public $password;
    public $password_confirm;
    private $_permission;

    public static function getRoleOptions($code = null) {
        $ret = [
            self::ROLE_ADMIN => 'ผู้ดูแลระบบ',
            self::ROLE_SALE => 'ฝ่ายขาย',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function encrypt($str) {
        return Yii::$app->security->generatePasswordHash($str);
    }

    public function getAuthKey() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey) {
        
    }

    public function can($permission) {
        $keys = $this->getPermissionKeys();

        if (in_array('admin', $keys)) {
            return true;
        }
        return in_array($permission, $keys);
    }

    public function getPermissions() {
        if (isset($this->role)) {
            return $this->role->permissions;
        }
        return [];
    }

    public function getPermissionKeys() {
        if (!isset($this->_permissions)) {
            $this->_permission = array_values(ArrayHelper::map($this->getPermissions(), 'name', 'name'));
        }
        return $this->_permission;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['email'], 'email'];
        $rules[] = [['password', 'password_confirm'], 'string', 'length' => [4, 128]];

        $rules[] = [['username', 'password'], 'required', 'on' => 'login'];
        $rules[] = [['username'], 'exist', 'on' => 'login'];
        $rules[] = [['password'], 'authenticate', 'on' => 'login'];

        $rules[] = [['password', 'password_confirm'], 'required', 'on' => 'create'];
        $rules[] = [['name', 'email', 'role_id'], 'required', 'on' => ['create', 'update']];
        $rules[] = [['password_confirm'], 'compare', 'compareAttribute' => 'password', 'on' => ['create', 'update']];

        $rules[] = ['search', 'safe', 'on' => 'search'];


        return $rules;
    }

    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            switch ($this->scenario) {
                case 'create':
                case 'update':
                    if ($this->password) {
                        $this->secret = self::encrypt($this->password);
                    }
                    break;
            }
            return true;
        }
    }

    public function getRoleName() {
        $role = self::getRoleOptions($this->role_id);
        return is_array($role) ? 'ไม่ได้กำหนด' : $role;
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = [
            'username',
            'password',
            'password_confirm',
            'name',
            'email',
            'description',
            'role_id',
            '!secret',
        ];
        $scenarios['update'] = [
            'username',
            'password',
            'password_confirm',
            'name',
            'email',
            'description',
            'role_id',
            '!secret',
        ];
        return $scenarios;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อ-นามสกุล',
            'email' => 'อีเมล์',
            'description' => 'รายละเอียด',
            'role_id' => 'สิทธิในการใช้งาน',
            'roleName' => 'สิทธิในการใช้งาน',
            'username' => 'ชื่อบัญชี',
            'password' => 'รหัสผ่าน',
            'password_confirm' => 'ยืนยันรหัสผ่าน',
        ]);
    }

    public function authenticate($attribute) {
        $user = $this->findByUsername($this->username);
        if (!isset($user) || !Yii::$app->security->validatePassword($this->{$attribute}, $user->secret)) {
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

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function findByUsername($username) {
        return $this->findOne([
                    'username' => $username,
        ]);
    }

    public function search() {
        $query = $this->find();

        switch ($this->scenario) {
            case 'search':
                $query->andFilterWhere(['LIKE', 'username', $this->search['text']]);
                break;
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
