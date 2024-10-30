<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 13:43
 */

namespace models;

use db\AWPModel;

class Calendar extends AWPModel {

    public static $_Table = 'jih_calendar';
    public static $_PrimaryKey='id';

    protected $id;
    /* @var string name */
    protected $name;
    protected $description;


    public static function CreateTableQuery(){
        return parent::CreateTableQuery("
            id int NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) DEFAULT 'No Name' NOT NULL,
            description text NOT NULL,
            PRIMARY KEY id (id)
		");
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @param mixed $description
     */
    public function setDescription( $description ) {
        $this->description = $description;
    }


}
