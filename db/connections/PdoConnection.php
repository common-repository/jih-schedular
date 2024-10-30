<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 18-Oct-14
 * Time: 09:38
 */

namespace db\connections;
use db\ResultSet;
use PDO;

class PdoConnection implements IConnection{
    protected $connection;

    public function __construct($dsn = null, $user=NULL, $pass=NULL, $driver_options=NULL) {
        $this->connection = new PDO($dsn ?: 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';', $user ?: DB_USER, $pass ?: DB_PASSWORD, $driver_options);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
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
        $prep = $this->connection->prepare($sql);
        $prep->execute($values);
        return new ResultSet($prep->fetchAll());
    }

    /**
     * @param string $sql
     * @return array
     */
    public function Execute($sql){
        return new ResultSet($this->connection->query($sql)->fetchAll());
    }

    public function Command($sql){
        return $this->connection->query($sql)->execute();
    }

    public function CommandPrepared($sql,$values){
        return $this->connection->prepare($sql)->execute($values);
    }
}