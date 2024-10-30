<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 25-Jan-15
 * Time: 15:08
 */

namespace controllers;


use helpers\Ajax;
use models\Event;
use models\User;

class InstallController extends  Controller {
    /**
     * Installs necessary database structure
     */
    public function InstallAction(){
        $db = new \db\connections\WpConnection();

        $db->Execute(\models\Calendar::CreateTableQuery());
        $db->Execute(\models\Event::CreateTableQuery());
        $db->Execute(\models\EventViewModel::CreateTableQuery());
    }

    public static function DropEverythingAction(){
        $db = new \db\connections\WpConnection();
        foreach(array(\models\Calendar::GetPrefixedTable(),\models\Event::GetPrefixedTable()) as $table){
            $db->Execute("DROP TABLE IF EXISTS $table;");
        }
        foreach(array(\models\EventViewModel::GetPrefixedTable()) as $table){
            $db->Execute("DROP VIEW IF EXISTS $table;");
        }
    }
}