<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 25-Jan-15
 * Time: 20:38
 */

namespace helpers;


class Ajax {
    public static function Error($number,$message = 'error'){
        $json = array(
            'success'=>false,
            'error' => $number,
            'message' => $message );
        self::Json($json);
    }

    public static function Success($data,$message = 'success'){
        $json = array(
            'success'=>true,
            'data' => $data,
            'message' => $message
        );
        self::Json($json);
    }

    public static function Json($data){
        echo json_encode($data);
        die();
    }
}