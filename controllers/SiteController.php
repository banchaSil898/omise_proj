<?php

namespace app\controllers;

use app\components\Intro;
use app\models\Contact;
use app\models\ContactForm;
use app\models\Content;
use app\models\Faq;
use app\models\LoginForm;
use app\models\Member;
use app\models\Product;
use app\models\Slide;
use codesk\components\Html;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => '@app/views/layouts/main',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Yii::$app->homeUrl,
                'cancelCallback' => [$this, 'onAuthFailed'],
            ],
        ];
    }

    public function beforeAction($action) {
        if ($action->id === 'login') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        Yii::$app->session->set('survey_2019', null);
        $this->title = 'หน้าแรก';
        $this->showSubFooter = true;

        $nModel = new Product;
        $nModel->is_new = true;
        $nModel->is_hide = 0;
        $newProvider = $nModel->search();
        $newProvider->sort = [
            'defaultOrder' => [
                'is_pin' => SORT_DESC,
                'created_at' => SORT_DESC,
            ],
        ];

        $sModel = new Product;
        $sModel->is_bestseller = true;
        $sModel->is_hide = 0;
        $sellProvider = $sModel->search();
        $sellProvider->sort = [
            'defaultOrder' => [
                'is_pin' => SORT_DESC,
                'created_at' => SORT_DESC,
            ],
        ];

        $rModel = new Product;
        $rModel->is_recommended = true;
        $rModel->is_hide = 0;
        $recommendProvider = $rModel->search();
        $recommendProvider->sort = [
            'defaultOrder' => [
                'is_pin' => SORT_DESC,
                'created_at' => SORT_DESC,
            ],
        ];

        $publisherProvider = new ActiveDataProvider([
            'query' => Product::find()->active()->isNotOwner(),
            'sort' => [
                'defaultOrder' => [
                    'is_pin' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $newsModel = new Content;
        $newsModel->content_type = 'news';
        $newsProvider = $newsModel->search();

        $slides = Slide::find()->orderBy(['order_no' => SORT_ASC])->all();

        $intro = new Intro;

        return $this->render('index', [
                    'newProvider' => $newProvider,
                    'sellProvider' => $sellProvider,
                    'recommendProvider' => $recommendProvider,
                    'publisherProvider' => $publisherProvider,
                    //'authorProvider' => $authorProvider,
                    'newsProvider' => $newsProvider,
                    'slides' => $slides,
                    'intro' => $intro,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin($url = null) {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (isset($url)) {
            Yii::$app->user->setReturnUrl($url);
        }


        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->set('login_type', 'E-mail');
            return $this->redirect(['/site/index']);
        }

        return $this->render('login', [
                    'model' => $model,
        ]);
        //$helper = $fb->getRedirectLoginHelper();
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionPrivacy() {
        return $this->render('privacy');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    public function actionTransfer() {
        return $this->render('transfer');
    }

    public function actionStatus() {
        return $this->render('status');
    }

    public function actionDelivery() {
        return $this->render('delivery');
    }

    public function actionCareer() {
        return $this->render('career');
    }

    public function actionMap() {
        return $this->renderAjax('map');
    }

    public function actionTerm() {
        return $this->render('term');
    }

    public function actionTermCredit() {
        return $this->render('term-credit');
    }

    public function actionTermTransfer() {
        return $this->render('term-transfer');
    }

    public function actionCheckStatus() {

        $response = Yii::$app->request->post('response');

        switch ($response['status']) {
            case 'connected':
                $fb = new Facebook([
                    'app_id' => Yii::$app->params['facebook']['appId'],
                    'app_secret' => Yii::$app->params['facebook']['appSecret'],
                    'default_graph_version' => 'v2.10',
                ]);

                // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
                //   $helper = $fb->getRedirectLoginHelper();
                //   $helper = $fb->getJavaScriptHelper();
                //   $helper = $fb->getCanvasHelper();
                //   $helper = $fb->getPageTabHelper();

                try {
                    // Get the \Facebook\GraphNodes\GraphUser object for the current user.
                    // If you provided a 'default_access_token', the '{access-token}' is optional.
                    $response = $fb->get('/me?fields=name,email', $response['authResponse']['accessToken']);
                } catch (FacebookResponseException $e) {
                    // When Graph returns an error
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                } catch (FacebookSDKException $e) {
                    // When validation fails or other local issues
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }

                $me = $response->getGraphUser();
                $name = $me->getName();
                $username = $me->getEmail();

                $member = Member::findOne([
                            'username' => $username,
                ]);
                if (!isset($member)) {
                    $member = new Member;
                    $member->name = $me->getName();
                    $member->username = $me->getEmail();
                    $member->facebook_id;
                } else {
                    
                }

                break;
            case 'not_authorized':
                break;
            case 'unknown':
                break;
        }
        $this->redirect(['index']);
    }

    public function actionHelp() {
        $faqs = Faq::find()->all();
        $faqItems = [];
        foreach ($faqs as $count => $item) {
            $faqItems[] = [
                'title' => ($count + 1) . '.) ' . Html::encode($item->name),
                'content' => $item->answer,
            ];
        }

        $contact = new Contact;
        $contact->scenario = 'create';
        $contact->record_status = Contact::STATUS_NEW;
        if ($contact->load(Yii::$app->request->post())) {
            if ($contact->doSend()) {
                $this->success('ส่งข้อมูลเรียบร้อย');
                return $this->redirect(['help']);
            }
        }
        return $this->render('help', [
                    'faqItems' => $faqItems,
                    'contact' => $contact,
        ]);
    }

    public function onAuthSuccess($client) {
        $attributes = $client->getUserAttributes();

        switch ($client->id) {
            case 'line':
                $member = Member::findOne([
                            'username' => ArrayHelper::getValue($attributes, ['email']),
                ]);
                if (!isset($member)) {
                    $member = new Member;
                }
                if ($member->doRegisterLine($attributes)) {
                    Yii::$app->user->login($member);
                    Yii::$app->session->set('login_type', 'Line');
                }
                break;
            case 'facebook':
                $member = Member::findOne([
                            'username' => ArrayHelper::getValue($attributes, ['email']),
                ]);
                if (!isset($member)) {
                    $member = new Member;
                }
                if ($member->doRegisterFacebook($attributes)) {
                    Yii::$app->user->login($member);
                    Yii::$app->session->set('login_type', 'Facebook');
                }
                break;
            case 'google-plus':
                $member = Member::findOne([
                            'username' => ArrayHelper::getValue($attributes, ['email']),
                ]);
                if (!isset($member)) {
                    $member = new Member;
                }
                if ($member->doRegisterGoogle($attributes)) {
                    Yii::$app->user->login($member);
                    Yii::$app->session->set('login_type', 'Google');
                }
                break;
        }
    }

    public function onAuthFailed($client) {
        var_dump($client);
        exit;
    }

    public function actionPrivacyPolicy() {
        
        return $this->render('privacy-policy', [
        ]);
    }

}
