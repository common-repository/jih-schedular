<?php
/**
 * Created by PhpStorm.
 * User: joel
 * Date: 27/10/2014
 * Time: 21:49
 */
namespace controllers;

use helpers\Ajax;
use helpers\Captcha;
use helpers\Date;
use helpers\Input;
use helpers\Setting;
use models\Calendar;
use models\Event;
use models\User;
use Twig\WpTwigViewHelper;


/*
 * Called if ajax request is done via api.js
 * ! Methods start with save will be array
 * Otherwise list of functions
 */
class AjaxController extends Controller {

    public  function SendMail($data){
        add_filter( 'wp_mail_content_type', array($this,'GetHtmlContentType') );
        $to      = 'denhartoghjohan@gmail.com';
        $subject = 'Aanvraag gebedsruimte';

        $message = WpTwigViewHelper::getInstance()->Render('mail-new-calendar.twig',$data);
        Ajax::Success(\wp_mail($to, $subject, $message));
    }

    /**
     * @param $calendarId
     * @param Date $date should be start of first day: time: 00:00:00
     *
     * @return $this
     */
    public function EventsForWeek($calendarId,$date){
        $date = new Date($date);
        Ajax::Success($this->dbContext->EventViewModels()->Where('calendarId',$calendarId)->Where('datetime >=',$date)->Where('datetime <',$date->CloneAddDays(7))->Execute());
    }

    public function EventById($id){
        Ajax::Success($this->dbContext->EventViewModels()->Where('id',$id)->Execute());
    }

    public function CalendarById($id){
        Ajax::Success($this->dbContext->Calendars()->Where('id',$id)->Execute());
    }

    public function Calendars(){
        Ajax::Success($this->dbContext->Calendars()->Execute());
    }

    public function SaveEvent($data){
        if(Input::Get('id')) //Remote to Edit
            $this->EditEvent($data);

        if($this->dbContext->Events()->Where('datetime',$data['datetime'])->Any()){
            Ajax::Error(5,"Already event on this datetime");
        }
        $loggedIn = User::IsLoggedIn();
        if(!$loggedIn && Setting::get("allowBookHour") == "user"){
            Ajax::Error(5,"Anonymous users are not allowed");
        }
        if($loggedIn){
            $user = User::Current();
            $event  = new Event( $data );
            if(!$event->getEmail()){
                $event->setEmail($user->user_email);
            }
            if(!$event->getName()){
                $event->setName($user->user_login);
            }
            $event->setUserId($user->ID);
            $result = $this->dbContext->Events()->Insert( $event );
            Ajax::Success($result);
        }

        if(isAdministrator() || $this->checkCaptcha($data)) {
            $event  = new Event( $data );
            $result = $this->dbContext->Events()->Insert( $event );
            Ajax::Success($result);
        }
        Ajax::Error(4,"Failed Captcha");
    }

    public function EditEvent($data){
        if(isAdministrator() || $this->checkCaptcha($data)) {
            $event = new Event($this->dbContext->Events()->FindById(Input::Get('id')));
            $event->setAttributes($data);
            $result = $this->dbContext->Events()->UpdateModel($event);
            Ajax::Success($result);
        }
        Ajax::Error(4,"Failed Captcha");
    }

    public function DeleteEvent($id){
        if(isAdministrator()){
            $result = $this->dbContext->Events()->Where('id',$id)->Delete();
            Ajax::Success($result, "Event deleted");
        }

        if (User::IsLoggedIn()){
            $eventUserId = $this->dbContext->Events()->Where('id',$id)->First()->userId;
            if(User::Current()->ID == $eventUserId){
                $result = $this->dbContext->Events()->Where('id',$id)->Delete();
                Ajax::Success($result,"Deleted one of your events");
            } else {
                Ajax::Error(3,"Not your event");
            }
        }
        Ajax::Error(2,"Not logged in");
    }

    public function DeleteEventByPin($id,$pin){
        $event = new Event($this->dbContext->Events()->FindById($id));
        if($event->getId() == 0){
            Ajax::Error(1, "Event does not exist");
        }
        if( !empty($pin) && $event->getPin() == $pin){
            $result = $this->dbContext->Events()->Where('id',$id)->Delete();
            Ajax::Success($result,"Event deleted");
        }
        Ajax::Error(3,"Event pin incorrect");
    }

    public function SaveCalendar($data){
        if(Input::Get('id'))
            $this->EditCalendar($data);
        if(isAdministrator()){
            $calendar = new Calendar($data);
            $result = $this->dbContext->Calendars()->Insert($calendar);
            Ajax::Success($result,"Calendar Created");
        }
        Ajax::Error(1, "No admin rights");
    }

    public function EditCalendar($data){
        if(isAdministrator()){
            $calendar = new Calendar($this->dbContext->Calendars()->FindById(Input::Get('id')));
            if($calendar->getId() == 0)
                Ajax::Error(1, "Calendar does not exist");

            $calendar->setAttributes($data);
            $result = $this->dbContext->Calendars()->UpdateModel($calendar);
            Ajax::Success($result, "Calendar updated");
        }
        Ajax::Error(1, "No admin rights");
    }

    public function DeleteCalendar($id){
        if(isAdministrator()){
            $result = $this->dbContext->Events()->Where('calendarId',$id)->Delete();
            $result += $this->dbContext->Calendars()->Where('id',$id)->Delete();
            Ajax::Success($result);
        }
        Ajax::Error(1, "No admin rights");
    }

    public function SetSetting($name,$value){
        if(isAdministrator()){
            Setting::set($name,$value);
            Ajax::Success($value);
        }
        Ajax::Error(1, "No admin rights");
    }

	/**
     * @param $data
     *
     * @return bool
     */
    private function checkCaptcha($data){
       return Captcha::Check($data);
    }
}

