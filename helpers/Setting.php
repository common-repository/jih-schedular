<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 13-Aug-15
 * Time: 16:40
 */

namespace helpers;


class Setting {

    public static $prefix = 'jih-schedular-';

    public static function get($name){
        return get_option(self::$prefix . $name);
    }

    public static function set($name,$value){
        return update_option(self::$prefix . $name,$value);
    }
}