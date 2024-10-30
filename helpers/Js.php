<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 01-Aug-15
 * Time: 22:29
 */

namespace helpers;


class Js extends Css {

    public function enqueue(){
        wp_enqueue_script($this->name, $this->file, $this->dependencies);
    }
}