/**
 * Created by joel on 27/10/2014.
 */
var api = {};
(function($) {
    var _datetimeFormat = 'YYYY-MM-DD HH:mm:ss';

    api.SendMail = function(data,onSuccess,onError, sync, context){
        return post('EventById',data,onSuccess,onError, sync, context)
    };


    api.EventById = function(id,onSuccess,onError, sync, context){
        return post('EventById',{id : id},onSuccess,onError, sync, context)
    };

    api.EventsForWeek = function(calendarId, date,onSuccess,onError, sync, context){
        return post('EventsForWeek',{calendarId : calendarId, date : date.format(_datetimeFormat)},onSuccess,onError, sync, context)
    };

    api.SaveEvent = function(data,onSuccess,onError, sync, context){
        return post('SaveEvent',data,onSuccess,onError, sync, context)
    };

    api.DeleteEvent = function(id,onSuccess,onError, sync, context){
        return post('DeleteEvent',{id : id},onSuccess,onError, sync, context)
    };

    //Data: Id and Pin
    api.DeleteEventByPin = function(data,onSuccess,onError, sync, context){
        return post('DeleteEventByPin',data,onSuccess,onError, sync, context)
    };

    api.Calendars = function(data,onSuccess,onError, sync, context){
        return post('Calendars',data,onSuccess,onError, sync, context)
    };

    api.CalendarById = function(id,onSuccess,onError, sync, context){
        return post('CalendarById',{id : id},onSuccess,onError, sync, context)
    };

    api.SaveCalendar = function(data,onSuccess,onError, sync, context){
        return post('SaveCalendar',data,onSuccess,onError, sync, context)
    };

    api.DeleteCalendar = function(id,onSuccess,onError, sync, context){
        return post('DeleteCalendar',{id : id},onSuccess,onError, sync, context)
    };

    api.SetSetting = function(name,value,onSuccess,onError, sync, context){
        return post('SetSetting',{name : name, value: value},onSuccess,onError, sync, context)
    };



    var post = function(action,input,onSuccess, onError, sync, context, options){
        var data = {
            action : action,
            dataType : 'json',
            input : input
        };
        var successHandle = !onSuccess ? Log : onSuccess;

        var defaults = {
            type : "POST",
            url : document.location.pathname + location.search,
            data : data,
            dataType : 'json',
            success : function(data){
                if(data.success){
                    successHandle(data.data,context);
                } else {
                    if(onError)
                        onError(data,context);
                    Log("Ajax request: " + action + ", gave error: "+data.message);
                }
            },
            error : DefaultOnErrorHandling,
            statusCode: {
                404: pageNotFound,
                401: GotoLoginPage
            },
            async: !sync
        };
        if(!IsEmpty(options)){
            defaults = $.extend(true, defaults, options);
        }
        return $.ajax(defaults);
    };

    function pageNotFound(){
        alert( "Ajax Request Page was not found, ask administrator" );
    }

    var DefaultOnErrorHandling = function(result,textStatus,thrownError){
        Log(result);
        Log("Request failed with: "+textStatus.ucFirst()+": "+thrownError+"\nMessage: "+result.responseJSON+"\nSee log for detailed error.");
    }

})(jQuery);