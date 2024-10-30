<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 11-Oct-14
 * Time: 13:01
 */
namespace helpers;

class Input {
//Todo: Make object, remove static. Add flag static vars for input type. Rename to request o or requestdata
    public static function Post($item=null,$default=null){
        if($item==null)
            return  !empty($_POST) ? $_POST : $default;
        return  isset($_POST[$item]) ? $_POST[$item] : $default;
    }

    public static function Get($item=null,$default=null){
        if($item==null)
            return  !empty($_GET) ? $_GET : $default;
        return  isset($_GET[$item]) ? $_GET[$item] : $default;
    }

    public static function Cookie($item=null,$default=null){
        if($item==null)
            return  !empty($_COOKIE) ? $_COOKIE : $default;
        return  isset($_COOKIE[$item]) ? $_COOKIE[$item] : $default;
    }

    public static function Param($item=null,$default=null){
        if($item==null)
            return  !empty($_REQUEST) ? $_REQUEST : $default;
        return  isset($_REQUEST[$item]) ? $_REQUEST[$item] : $default;
    }

    public static function IsPost(){
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}