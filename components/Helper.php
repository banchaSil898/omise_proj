<?php

namespace app\components;

class Helper {

    public static function getAuthClientOptions($code = null) {
        $ret = [
            'facebook' => 'facebook',
            'google-plus' => 'google-plus',
            'line' => 'comment-o',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function slug($string) {
        $string = preg_replace("`\[.*\]`U", "", $string);
        $string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $string);
        $string = str_replace('%', '-percent', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string);
        $string = preg_replace(array("`[^a-z0-9ก-๙เ-า]`i", "`[-]+`"), "-", $string);
        return strtolower(trim($string, '-'));
    }

    public static function exportXls($filename = null) {
        $filename = isset($filename) ? $filename : time();
        header('Content-Disposition: attachment; filename=' . $filename . '.xls');
        header('Content-Type:   application/vnd.ms-excel; charset=utf-8');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
    }

    public static function getMonthOptions($code = null) {
        $ret = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฏาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getMonthArray($to, $from) {
        $ret = [];
        $current = strtotime($to);
        $end = strtotime($from);
        while ($current <= $end) {
            $ret[date('Y-m-01', $current)] = date('M Y', $current);
            $current = strtotime('next month', $current);
        }
        return $ret;
    }

    public static function getWeekArray($to, $from) {
        $ret = [];
        $current = strtotime($to);
        $end = strtotime($from);
        while ($current <= $end) {
            $ret[date('Y-W', $current)] = date('j/n/y',strtotime(date('Y\WW', $current)));
            $current = strtotime('next week', $current);
        }
        return $ret;
    }

    public static function getDateArray($to, $from) {
        $ret = [];
        $current = strtotime($to);
        $end = strtotime($from);
        while ($current <= $end) {
            $ret[date('Y-m-d', $current)] = date('j/n/y', $current);
            $current = strtotime('next day', $current);
        }
        return $ret;
    }

}
