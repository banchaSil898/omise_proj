<?php

namespace app\components\promotion;

interface PromotionManagerInterface {

    public function processPrice($price);

    public function isValid($cart = null, $afterPrice = null);

    public function attributeInputs();
}
