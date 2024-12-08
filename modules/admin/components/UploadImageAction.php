<?php

namespace app\modules\admin\components;

use app\modules\admin\components\UploadFileAction;
use codesk\components\Html;
use yii\imagine\Image;
use yii\web\UploadedFile;

class UploadImageAction extends UploadFileAction {

    public $sizes = [];
    public $imgOptions = [];

    public function processFile(UploadedFile $file) {
        $url = false;
        $tmpname = uniqid();
        foreach ($this->sizes as $name => $options) {
            $filename = $tmpname . ($name === 'default' ? '' : '_' . trim($name)) . '.' . ($options['extension'] ? $options['extension'] : $file->extension);
            if (isset($options['width']) && isset($options['width'])) {
                Image::thumbnail($file->tempName, $options['width'], $options['height'])
                        ->save($this->uploadPath . DIRECTORY_SEPARATOR . $filename);
            } else {
                Image::autorotate($file->tempName)
                        ->save($this->uploadPath . DIRECTORY_SEPARATOR . $filename);
            }
            if (!$url || $name === 'default') {
                $url = $this->uploadUrl . DIRECTORY_SEPARATOR . $filename;
            }
        }
        return [
            'html' => Html::img($url, $this->imgOptions),
            'name' => $tmpname,
            'url' => $url,
        ];
    }

}
