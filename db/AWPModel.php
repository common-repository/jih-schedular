<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 17:04
 */

namespace db;


abstract class AWPModel extends AModel {

    public static $_TablePrefix=null;

    public static function CreateTableQuery($columnSql)
    {
        global $wpdb;
        self::$_TablePrefix = $wpdb->prefix;
        $charset_collate = '';
        if ( ! empty( $wpdb->charset ) )
            $charset_collate = " DEFAULT CHARACTER SET {$wpdb->charset}";
        if ( ! empty( $wpdb->collate ) )
            $charset_collate .= " COLLATE {$wpdb->collate}";
        return parent::CreateTableQuery($columnSql).$charset_collate;
    }

    public static function GetPrefixedTable(){
        if(self::$_TablePrefix===null){
            global $wpdb;
            AWPModel::$_TablePrefix = $wpdb->prefix;
        }
        return AWPModel::$_TablePrefix.parent::GetPrefixedTable();
    }
}

