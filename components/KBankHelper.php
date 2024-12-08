<?php

namespace app\components;

class KBankHelper {

    public static function parseResponse($res) {
        $ret = [];
        $positions = [
            'TransCode' => 4,
            'MerchantID' => 15,
            'TerminalID' => 8,
            'ShopNo' => 2,
            'CurrencyCode' => 3,
            'InvoiceNo' => 12,
            'Date' => 8,
            'Time' => 6,
            'CardNo' => 19,
            'ExpiredDate' => 4,
            'CVV' => 4,
            'TransAmount' => 12,
            'ResponseCode' => 2,
            'ApprovalCode' => 6,
            'CardType' => 3,
            'Reference1' => 20,
            'PlanID' => 3,
            'PayMonth' => 2,
            'InterestType' => 1,
            'InterestRate' => 6,
            'AmountPerMonth' => 9,
            'TotalAmount' => 12,
            'ManagementFee' => 5,
            'InterestMode' => 2,
            'FXRate' => 20,
            'THBAmount' => 20,
            'CustomerEmail' => 100,
            'Description' => 184,
            'PayerIPAddress' => 18,
            'WarningLight' => 1,
            'SelectedBank' => 60,
            'IssuerBank' => 60,
            'SelectedCountry' => 45,
            'IPCountry' => 45,
            'IssuerCountry' => 45,
            'ECI' => 4,
            'XID' => 40,
            'CAVV' => 40,
        ];
        $currentLength = 0;
        foreach ($positions as $position => $length) {
            $ret[$position] = substr($res, $currentLength, $length);
            $currentLength = $currentLength + $length;
        }
        return $ret;
    }

}
