<?php

namespace app\modules\admin\components;

use codesk\components\Html;
use Yii;
use yii\base\Action;
use yii\imagine\Image;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadFileAction extends Action {

    public $name;
    public $model;
    public $attribute;
    public $uploadPath;
    public $uploadUrl;

    public function run() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($this->name)) {
            $file = UploadedFile::getInstanceByName($this->name);
        } else {
            $file = UploadedFile::getInstance(new $this->model, $this->attribute);
        }
        if ($file) {
            return $this->processFile($file);
        } else {
            return [
                'error' => 'Error on upload file.',
            ];
        }
    }

    public function processFile(UploadedFile $file) {
        $filename = uniqid() . '.' . $file->extension;
        $file->saveAs($this->uploadPath . DIRECTORY_SEPARATOR . $filename);
        return [
            'url' => $this->uploadUrl . DIRECTORY_SEPARATOR . $filename . '?t=' . time(),
            'filename' => $filename,
        ];
    }

}
