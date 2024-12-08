<?php

namespace app\modules\admin;

use app\models\Contact;
use app\models\Folder;
use app\models\Product;
use app\models\Promotion;
use app\models\Publisher;
use app\models\Purchase;
use app\modules\admin\assets\AppAsset;
use Yii;
use yii\base\Module as BaseModule;
use yii\web\View;

class Module extends BaseModule {

    public $defaultRoute = 'site/index';
    public $stat = [];

    public function init() {
        parent::init();
        Yii::setAlias('@module', dirname(__FILE__));

        Yii::$app->set('user', [
            'class' => 'app\modules\admin\components\User',
            'identityClass' => 'app\models\Account',
            'enableAutoLogin' => false,
            'loginUrl' => ['admin/site/login'],
            'identityCookie' => ['name' => 'micadmin', 'httpOnly' => true],
            'idParam' => 'micadmin_id', //this is important !
        ]);

        # Set error handling
        Yii::$app->errorHandler->errorView = '@module/views/site/error';
        Yii::$app->errorHandler->exceptionView = '@module/views/site/exception.php';
        Yii::$app->errorHandler->errorAction = 'admin/site/error';

        $this->stat['countCategory'] = Folder::find()->andWhere(['level' => 0])->count();
        $this->stat['countProduct'] = Product::find()->count();
        $this->stat['countPublisher'] = Publisher::find()->count();
        $this->stat['countPurchases'] = Purchase::find()->scopeWaitForAction()->count();
        $this->stat['countPromotions'] = Promotion::find()->count();
        $this->stat['countContact'] = Contact::find()->andWhere(['record_status' => [Contact::STATUS_NEW, Contact::STATUS_PROCESSING]])->count();
        $this->stat['countPromotion'] = Promotion::find()->where(['is_active' => 1])->count();
    }

    public function getAssetUrl() {
        $bundle = AppAsset::register(new View);
        return $bundle->baseUrl;
    }

}
