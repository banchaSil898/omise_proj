<?php

namespace app\models;

use app\components\Html;
use app\models\base\Folder as BaseFolder;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class Folder extends BaseFolder {

    public $search;

    public static function find() {
        return Yii::createObject(FolderQuery::className(), [get_called_class()]);
    }

    public static function getListItems() {
        $ret = [];
        $items = self::find()->andWhere(['level' => 0])->orderBy(['position' => SORT_ASC])->all();
        foreach ($items as $item) {
            $ret[$item->id] = Html::encode($item->name);
            $sItems = $item->getCategoryItems()->orderBy(['position' => SORT_ASC])->all();
            foreach ($sItems as $sItem) {
                $ret[$sItem->id] = '-- -- ' . Html::encode($sItem->name);
            }
        }
        return $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อหมวด',
        ]);
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->position = Yii::$app->db->createCommand('SELECT COALESCE(MAX(position),0)+1 as position FROM folder WHERE folder_id = :folder_id')->bindValue(':folder_id', $this->folder_id)->queryScalar();
            }
            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PromotionFolder::deleteAll([
                'folder_id' => $this->id,
            ]);
            ProductFolder::deleteAll([
                'folder_id' => $this->id,
            ]);
            Folder::deleteAll([
                'folder_id' => $this->id,
            ]);
            return true;
        }
    }

    public function getCategoryItems() {
        return $this->hasMany(Folder::className(), ['folder_id' => 'id']);
    }

    public function getParent() {
        return $this->hasOne(Folder::className(), ['id' => 'folder_id']);
    }

    public function getIsDeletable() {
        return true;
    }

    public function getProductFolders() {
        return $this->hasMany(ProductFolder::className(), ['folder_id' => 'id']);
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function doMoveUp() {
        $target = Folder::find()
                ->andWhere(['level' => $this->level])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['<', 'position', $this->position])
                ->orderBy([
                    'ABS(position - :position)' => SORT_ASC
                ])
                ->params([
                    ':position' => $this->position,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->position;
            $target->updateAttributes([
                'position' => $this->position,
            ]);
            $this->updateAttributes([
                'position' => $targetPosition,
            ]);
        }
    }

    public function doMoveDown() {
        $target = Folder::find()
                ->andWhere(['level' => $this->level])
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['>', 'position', $this->position])
                ->orderBy([
                    'ABS(:position - position)' => SORT_ASC
                ])
                ->params([
                    ':position' => $this->position,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->position;
            $target->updateAttributes([
                'position' => $this->position,
            ]);
            $this->updateAttributes([
                'position' => $targetPosition,
            ]);
        }
    }

}

class FolderQuery extends ActiveQuery {

    public function defaultScope() {
        $this->andWhere(['level' => 0]);
        $this->orderBy(['position' => SORT_ASC]);
        return $this;
    }

}
