<?php

namespace app\components;

use app\models\Configuration;
use app\models\MailBody;
use codesk\components\Html;
use Exception;
use Swift_RfcComplianceException;
use Swift_SmtpTransport;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\swiftmailer\Mailer;

class MicMailer extends Component {

    public $viewPath = '@app/mail/layouts/html';
    private $_mailer;
    private $_compose;

    public function init() {
        parent::init();
        $this->setup();
    }

    public function setup() {
        $this->_mailer = new Mailer;
        $config = Configuration::getValue([
                    'mail_host',
                    'mail_username',
                    'mail_password',
                    'mail_port',
                    'mail_host',
                    'mail_header',
                    'mail_footer',
                    'mail_encryption',
                    'mail_from',
                    'mail_from_alias',
        ]);
        $transport = new Swift_SmtpTransport;
        $transport->setHost(ArrayHelper::getValue($config, 'mail_host'));
        $transport->setUsername(ArrayHelper::getValue($config, 'mail_username'));
        $transport->setPassword(ArrayHelper::getValue($config, 'mail_password'));
        $transport->setPort(ArrayHelper::getValue($config, 'mail_port'));
        $transport->setEncryption(ArrayHelper::getValue($config, 'mail_encryption'));
        $this->_mailer->setTransport($transport);
        $this->_compose = $this->_mailer->compose();
        $this->_compose->setFrom([ArrayHelper::getValue($config, 'mail_from') => ArrayHelper::getValue($config, 'mail_from_alias')]);
    }

    public function setSubject($str) {
        $this->_compose->setSubject($str);
    }

    public function setFrom($from) {
        $this->_compose->setFrom($from);
        $this->_compose->setReplyTo($from);
    }

    public function setSender($from) {
        $this->_compose->setHeader('Sender', $from);
    }

    public function setReply($from) {
        $this->_compose->setReplyTo($from);
    }

    public function setReturnPath($from) {
        $this->_compose->setReturnPath($from);
    }

    public function addAttachment($path) {
        $this->_compose->attach($path);
    }

    public function setBody($html = '') {
        $this->_compose->setHtmlBody($html);
    }

    public function setView($name, $params = []) {

        $defaultParams = [];
        $items = Configuration::getValuesByGroup('param');
        foreach ($items as $key => $data) {
            $defaultParams['{{' . $key . '}}'] = $data;
        }
        $params = array_merge($defaultParams, $params);

        $config = Configuration::getValue([
                    'mail_header',
                    'mail_footer',
                    'mail_header_receipt',
                    'mail_footer_receipt',
                    'mail_from',
                    'mail_from_alias',
        ]);
        $body = MailBody::findOne([
                    'mail_type' => $name,
        ]);
        if ($name == 'receipt') {
            $html = ArrayHelper::getValue($config, 'mail_header_receipt') . strtr($body->mail_body, $params) . ArrayHelper::getValue($config, 'mail_footer_receipt');
        } else {
            $html = ArrayHelper::getValue($config, 'mail_header') . strtr($body->mail_body, $params) . ArrayHelper::getValue($config, 'mail_footer');
        }
        $this->_compose = $this->_mailer->compose('@app/views/mail/base', [
            'content' => $html,
        ]);
        $this->_compose->setFrom([ArrayHelper::getValue($config, 'mail_from') => ArrayHelper::getValue($config, 'mail_from_alias')]);
        $this->_compose->setSubject(Html::encode(strtr($body->mail_title, $params)));
    }

    public function send($mails = [], $cc = true) {
        $valid = true;
        if (is_array($mails) && count($mails)) {
            foreach ($mails as $mail) {
                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $valid = false;
                }
            }
        } else {
            if (!$mails) {
                $valid = false;
            }
        }
        try {
            if ($valid) {
                $this->_compose->setTo($mails);
                if ($cc) {
                    $this->_compose->setCc(Configuration::getValue('web_mail'));
                }
                return $this->_compose->send();
            } else {
                $this->_compose->setTo(Configuration::getValue('web_mail'));
                return $this->_compose->send();
            }
        } catch (Swift_RfcComplianceException $e) {
            Yii::error($e->getMessage(), 'mail');
            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'mail');
            return true;
        }
    }

}
