<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 16:31
 */

namespace db\connections;


use db\ResultSet;
use mysqli_result;
use mysqli_stmt;

class MysqliConnection implements IConnection{
    protected $connection;

    public function __construct($mysqli=null){
        if($mysqli!=null){
            $this->connection = $mysqli;
        } else {
            global $wpdb;
            if(method_exists($wpdb,'getDbConnection')){
                $this->connection = $wpdb->getDbConnection();
            } else {
                $this->connection = mysqli_init();
                mysqli_real_connect( $this->connection, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            }
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed|mysqli_stmt|mysqli_result
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->connection,$name),$arguments);
    }

    /**
     * @param string $sql
     * @param array $values
     * @return array
     */
    public function ExecutePrepared($sql,$values){
        $stmt = $this->prepare($sql,$values);
        $stmt->execute();
        return new ResultSet($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    }

    /**
     * @param string $sql
     * @return array
     */
    public function Execute($sql){
        return new ResultSet($this->connection->query($sql)->fetch_all(MYSQLI_ASSOC));
    }

    public function Command($sql){
        return $this->connection->query($sql);
    }

    public function CommandPrepared($sql,$values){
        return $this->prepare($sql,$values)->execute();
    }

    private function prepare($sql,$values){
        throw new \Exception('Not correctly implemented Yes');
        $stmt = $this->connection->prepare($sql);
        foreach ($values as $value) {
            $stmt->bind_param('s',$value);
        }
        return $stmt;
    }
}