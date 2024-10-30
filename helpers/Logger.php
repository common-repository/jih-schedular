<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 19-Oct-14
 * Time: 08:12
 */
namespace helpers;

//TODO thought process ^_^
use Singleton;

class Logger extends Singleton {

    public static function Log($text){
        var_dump($text);
        die();
    }

}