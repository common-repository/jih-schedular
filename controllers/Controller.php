<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 22-Nov-14
 * Time: 08:57
 */

namespace controllers;


use DbContext;
use Exception;

class Controller {

    /** @var DbContext $dbContext */
    protected $dbContext;

    public function __construct(){
        $this->dbContext = new DbContext();
    }

    public function route($action){
        $actionMethod = $action.'Action';
        if(method_exists($this,$actionMethod))
            return $this->$actionMethod();
        else
            throw new Exception("Method $actionMethod was not yet implemented.");

    }
}