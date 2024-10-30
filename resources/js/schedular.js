/**
 * Created by Joel on 11-Oct-14.
 */

var $ = jQuery;
moment.locale('nl');

jQuery(document).ready(function(){
    var $ = jQuery;
    _calendarEl = $('#jih-calendar');
    $eventModal = $('#jih-plan-hour');
    $infoModal = $('#jih-info-modal');
    _calendarId = $.query.get('calendarId') || $.cookie('calendarId') || _calendarId;
    $('#jih-calendar-choice').val(_calendarId);
    $('h1.entry-title').text($("option:selected",'#jih-calendar-choice').text());
    $('tbody td',_calendarEl).click(function(){
        var $this = $(this);
        if($this.hasClass('is-filled')){
            $infoModal.modal('show');
            var event = $this.data('event');
            $.each(event,function(attr,value){
                $infoModal.find('div.data-'+attr).text(value);
                $infoModal.find('input.data-'+attr).val(value);
                //$infoModal.find('input')
                if(isLoggedIn()){
                    if(event.userId != _userId)
                        $('#deleteEventButton').prop( "disabled", true );
                    else
                        $('#deleteEventButton').prop( "disabled", false );
                }

            });

        } else if(!$this.hasClass('inThePast')) {
            var date = getDateFromElement(this);
            debugger;
            
            $('#jih-date').val(date.format(_datetimeFormat));
            $('#jih-date-show').val(date.format("dddd DD MMM HH:mm"));
            $('#redirect-url').val(document.URL);
            $($eventModal).modal('show');
        }

    });

    var $eventForm = $('#schedular-event-form');
    $eventForm.submit(function(e){
        e.preventDefault();
        var data = $eventForm.serializeObject();
        data.calendarId = _calendarId;
        if(data.repeat){
            $('.repeatEventCheckbox').click();
            var iteration = 1;
            while(data.repeatQuantity >= iteration){
                api.SaveEvent(data,false,false,true);
                data.datetime = moment(data.datetime).add(1,data.repeatPeriod).format(_datetimeFormat);
                iteration++;
            }
            onSuccesEventSave();
        } else {
            api.SaveEvent(data,onSuccesEventSave);
        }

        if(isLoggedIn()){
            window.location.reload();
        }
    });

    var $deleteForm = $('#delete-event-form');
    $deleteForm.submit(function(e){
        e.preventDefault();
        var data = $deleteForm.serializeObject();
        var onSuccess = function(){
            $infoModal.modal('hide');
            reloadCalendar();
        };
        if(isLoggedIn()){
            api.DeleteEvent(data.id,onSuccess,function(){
                alert('Event not deleted, it was not made on this account')
            })
        } else {
            api.DeleteEventByPin(data,onSuccess,function(){
                alert('Event not deleted, wrong pincode. (Events without pin cannot be deleted by users)')
            })
        }

    });

    //Repeat behaviour
    $('.repeatEventCheckbox').click(function(){
       $(this).next().toggle();
    });



    setCalendarOnDate(_date);
});

var onSuccesEventSave = function(){
    $eventModal.modal('hide');
    reloadCalendar();
};

var $infoModal;
var $eventModal;
//Initial values
var _calendarSize = 7;
var _calendarId = 1;
var _calendarEl = null;
var _date = ThisWeek();
var CurDate = function(){
    return _date.clone();
};
var _dateFormat = 'YYYY-MM-DD';
var _datetimeFormat = 'YYYY-MM-DD HH:mm:ss';
var _userId = 0;
function isLoggedIn(){
    return _userId != 0;
}
//END Initial values

function gotoWeekAfter(){
    setCalendarOnDate(CurDate().add(7,'days'));
}

function gotoWeekBefore(){
    setCalendarOnDate(CurDate().subtract(7,'days'));
}
function gotoDayBefore(){
    setCalendarOnDate(CurDate().subtract(1,'days'));
}
function gotoDayAfter(){
    setCalendarOnDate(CurDate().add(1,'days'));
}
function gotoToday(){
    setCalendarOnDate(ThisWeek());
}

function getDate(){
    return  $.query.get('date') || moment().format('YYYY-MM-DD');
}

function ChangeCalendarId($id){
    _calendarId = $id;
    reloadCalendar();
    $.cookie('calendarId',$id);
    $('h1.entry-title').text($("option:selected",'#jih-calendar-choice').text());
    history.pushState(null, null,$.query.SET('calendarId',$id));
}

function setCalendarOnDate(date){
    _date = date;
    history.pushState(null, null,$.query.SET('date',_date.format(_dateFormat)));
    $('thead th',_calendarEl).each(function(i){
        if(i>0){
            $(this).html(CurDate().add(i-1,'days').format('dddd<br>DD MMM'));
        }
    });
    $('#calendar-header-date').html(_date.format('MMMM YYYY'));
    loadCalendarEvents(date);

    //Add in the past class
    var hourDiff = moment.duration(moment().diff(_date)).asHours();
    var cells = $('td',_calendarEl).removeClass('inThePast');
    var $i = 0;
    while (hourDiff > $i && $i <= 24*7){
        cells.eq(Math.floor($i/24)+$i%24*7).addClass('inThePast');
        $i++;
    }
    //$('td',_calendarEl).removeClass('inThePast').slice(0,hourDiff).addClass('inThePast');
}

function getDateFromElement(el){
    var index = $('td',_calendarEl).index(el);
    var hour = Math.floor(index/7);
    var daysFromDate = index%7;
    return CurDate().add(daysFromDate,'days').add(hour,'hours')
}

function getElementFromDate(date){
    var days = date.diff(_date,'days');
    var hours = date.hours();
    var index = (hours * _calendarSize) + days;
    return $('td',_calendarEl).eq(index);
}

function reloadCalendar(){
    setCalendarOnDate(_date);
    //loadCalendarEvents(_date);
}
var lastCalendarCall = null;
function loadCalendarEvents(date){
    emptyCalendar();
    if(lastCalendarCall)
        lastCalendarCall.abort();
    lastCalendarCall = api.EventsForWeek(_calendarId,date,saturateCalendar); //,false synchronious call against spamming server --TODO buid in caching
}


var filledClass = 'is-filled';

function saturateCalendar(events){
    $.each( events, function( index, event ) {
        $dateEl = getElementFromDate(moment(event.datetime));
        $dateEl.addClass(filledClass).text(event.name);
        $dateEl.data('event',event);
    });
}

function emptyCalendar(){
    $('.'+filledClass,_calendarEl).removeClass(filledClass).text('');
}

//HOTKEYS

$(document).keyup(function(e){
    var period = "day";
    if(e.shiftKey)
        period = "week";
    if(e.ctrlKey)
        period = "month";
    if(e.which==37){
        setCalendarOnDate(CurDate().subtract(1,period));
    }
    if(e.which==39){
        setCalendarOnDate(CurDate().add(1,period));
    }
});
