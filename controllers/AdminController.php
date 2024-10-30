<?php
/**
 * Created by PhpStorm.
 * User: Joel
 * Date: 22-Nov-14
 * Time: 08:53
 */

namespace controllers;

use helpers\Input;
use helpers\Setting;
use Twig\WpTwigViewHelper;

class AdminController extends Controller{


    public function  CalendarsAction(){
        $data = array();
        $cal = $this->dbContext->Calendars();
        if($search = urldecode(Input::Get('search'))){
            $cal->WhereLike('name',$search);
        }
        $data['calendars'] = $cal->Execute();
        $data['search'] = $search;
        return WpTwigViewHelper::getInstance()->Render('admin-schedule.twig',$data);
    }

    public function SettingsAction(){
        $data=array();
        $data['calendars'] = $this->dbContext->Calendars()->Execute();
        $data['calendar'] = Setting::get('defaultCalendar');
        return WpTwigViewHelper::getInstance()->Render('admin-settings.twig',$data);
    }

    public function CalendarFormAction(){
        $data=array();
        if($id = Input::Param('id'))
            $data['calendar'] = $this->dbContext->Calendars()->Where('id',$id)->Execute()->First();
        return WpTwigViewHelper::getInstance()->Render('admin-calendar-form.twig',$data);
    }

    public function  EventsAction(){

        $data = array();
        $cal = $this->dbContext->Events();
        if($search = urldecode(Input::Get('search'))){
            $cal->WhereLike('name',$search);
        }
        $data['events'] = $cal->Execute();
        $data['search'] = $search;
        return WpTwigViewHelper::getInstance()->Render('admin-event-overview.twig',$data);
    }

    public function EventFormAction(){
        $data=array();
        $data['calendars'] = $this->dbContext->Calendars()->Execute();
        if($id = Input::Param('id'))
            $data['event'] = $this->dbContext->Events()->Where('id',$id)->Execute();
        return WpTwigViewHelper::getInstance()->Render('admin-event-form.twig',$data);
    }



//    protected function saveCalendar($data){
//        $calendar = new Calendar($data);
//        return $this->dbContext->Calendars()->Insert($calendar);
//    }

//    protected function  GotoPage($action){
//        header('Location: '.$_SERVER['PHP_SELF'].'?page=jih-'.$action);
//        die;
//    }

}