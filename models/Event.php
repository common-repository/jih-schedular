<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 12-Oct-14
 * Time: 19:06
 */

namespace models;


use db\AWPModel;
use helpers\Date;

class Event extends AWPModel {
    public static $_Table = 'jih_event';
    public static $_PrimaryKey='id';

    protected $id;
    protected $calendarId;
    /** @var Date $datetime */
    protected $datetime;
    protected $name;
    protected $email;
    protected $pin;
    protected $description;
    protected $userId;


    public static function CreateTableQuery()
    {
        return parent::CreateTableQuery("
            id int NOT NULL AUTO_INCREMENT,
            calendarId int NOT NULL,
            datetime datetime NOT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR (100) NULL,
            pin VARCHAR(4) NULL,
            description text  NULL,
            userId int NULL,
            PRIMARY KEY (id),
            UNIQUE KEY `calendar_date` (`calendarId`,`datetime`)
		");
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        if($this->datetime == null)
            $this->setDatetime();
        return $this->datetime->toDb();
    }

    /**
     * @param string $datetime
     */
    public function setDatetime($datetime='')
    {
        $this->datetime = new Date($datetime);
    }

    //GENERATED
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCalendarId() {
        return $this->calendarId;
    }

    /**
     * @param int $calendarId
     */
    public function setCalendarId( $calendarId ) {
        $this->calendarId = $calendarId;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name ) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail( $email ) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * @param string $pin
     */
    public function setPin( $pin ) {
        $this->pin = $pin;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription( $description ) {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


} 