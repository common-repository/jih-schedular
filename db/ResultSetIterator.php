<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 16:30
 */

namespace db;


use ArrayIterator;

class ResultSetIterator extends ArrayIterator{

    public static function ClassName(){
        return get_called_class();
    }

    public function __get($name){
        $cur = $this->current();
        return $cur[$name];
    }
}
