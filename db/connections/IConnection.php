<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 18-Oct-14
 * Time: 09:29
 */

namespace db\connections;


interface IConnection {

    public function Execute($sql);
    public function ExecutePrepared($sql,$values);

    public function Command($sql);
    public function CommandPrepared($sql,$values);
}