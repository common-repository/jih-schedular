<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 21-Aug-15
 * Time: 22:20
 */

namespace helpers;


class Settings {
    public $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix){
        $this->prefix = $prefix;
    }

    public function get($name){
        return get_option($this->prefix . $name);
    }

    public function set($name,$value){
        return update_option($this->prefix . $name,$value);
    }
}