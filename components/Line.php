<?php

namespace app\components;

use yii\authclient\ClientInterface;
use yii\authclient\OAuth2;

class Line extends OAuth2 implements ClientInterface {

    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://access.line.me/oauth2/v2.1/authorize';

    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'https://api.line.me/oauth2/v2.1/token';

    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://api.line.me/v2';

    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'userId',
        'displayName',
        'pictureUrl',
        'email',
    ];

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'profile',
                'openid',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'id' => 'userId',
            'first_name' => 'displayName',
            'last_name' => 'displayName',
            'email' => 'userId',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes() {
        return $this->api('profile', 'GET');
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken) {
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName() {
        return 'line';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle() {
        return 'Line';
    }

}
