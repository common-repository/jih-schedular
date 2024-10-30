<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 11-Oct-14
 * Time: 14:32
 */
namespace helpers;

use DateInterval;
use DateTime;

class Date extends DateTime{

//    public function  __construct($dateString){
//        parent::__construct(strtotime($dateString));
//    }

    public function __toString(){
        return $this->format('Y-m-d');
    }

    public function toDb(){
        return $this->format("Y-m-d H:i:s");
    }

    public function addDay($days=1){
        if($days<0)
            return $this->sub(new DateInterval('P'.($days*-1).'D'));
        return $this->add(new DateInterval('P'.$days.'D'));
    }

    public function CloneAddDays($days=1){
        $clone = clone $this;
        return $clone->addDay($days);
    }

    public function DbEndOfDay(){
        return $this->format("Y-m-d 23:59:59");
    }

    public function DbStartOfDay(){
        return $this->format("Y-m-d 00:00:00");
    }

    function FirstDayOfWeek($firstDay = 7) {
        $offset = 7 - $firstDay;
        $firstDayDate = clone $this;
        $firstDayDate->modify(-(($this->format('w') + $offset) % 7) . ' days');
        return $firstDayDate;
    }
} 