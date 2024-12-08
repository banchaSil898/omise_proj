<?php

namespace app\models;

use app\models\base\PromotionGift as BasePromotionGift;
use yii\data\ActiveDataProvider;

class PromotionGift extends BasePromotionGift {

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGift() {
        return $this->hasOne(Gift::className(), ['id' => 'gift_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion() {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('promotion_id', $this->promotion_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
