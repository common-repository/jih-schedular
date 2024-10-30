<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 29-Nov-14
 * Time: 08:23
 */

namespace models;


use db\AWPModel;

class EventViewModel extends AWPModel {

	public static $_Table = 'jih_eventviewmodel';
	public static $_PrimaryKey='id';

	protected $id;
	protected $calendarId;
	/** @var Date $datetime */
	protected $datetime;
	protected $name;
	protected $email;
	protected $description;
	protected $userId;

	public static function CreateTableQuery()
	{
		$viewName = static::GetPrefixedTable();
		$eventTable = Event::GetPrefixedTable();
		return "CREATE OR REPLACE VIEW $viewName AS SELECT id,calendarId,datetime,name,email,description,userId FROM $eventTable";
	}


}