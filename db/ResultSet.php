<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 16:30
 */

namespace db;

use ArrayObject;
use BadMethodCallException;

class ResultSet extends ArrayObject {

    protected $currentItem;

    public function __construct($result)
    {
        parent::__construct($result,0, ResultSetIterator::ClassName());
        $this->currentItem = $this->getIterator();

    }

    public function GetCurrentIterator(){
        return $this->currentItem;
    }

    public function __call($func, $argv)
    {
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_')
        {
            throw new BadMethodCallException(__CLASS__.'->'.$func);
        }
        return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
    }

    public function __get($name){
        return $this->currentItem->$name;
//        $this->currentItem->current()[$name];
    }

    //For Twig -> Get Item
    public function First(){
        return $this->currentItem->current();
    }

    public function Any(){
        return $this->count() > 0;
    }

}