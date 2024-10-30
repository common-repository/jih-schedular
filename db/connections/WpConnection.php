<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 18-Oct-14
 * Time: 09:26
 */

namespace db\connections;

use db\ResultSet;

class WpConnection {
    /** @var \wpdb $connection */
    protected $connection;

    public function __construct($debug = true){
        global $wpdb;
        $this->connection = $wpdb;
        $this->connection->show_errors($debug);
    }


    /**
     * Fallback if method does not exist
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->connection,$name),$arguments);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return ResultSet
     */
    public function ExecutePrepared($sql,$values){
        $sql = $this->connection->prepare($sql,$values);
        return $this->Execute($sql);
    }

    /**
     * @param string $sql
     * @return ResultSet
     */
    public function Execute($sql){
        return new ResultSet($this->connection->get_results($sql,ARRAY_A));
    }

    public function Command($sql){
        return $this->connection->query($sql);
    }

    public function CommandPrepared($sql,$values){
        $sql = $this->connection->prepare($sql,$values);
        $result = $this->Command($sql);
        return $result;
    }
}