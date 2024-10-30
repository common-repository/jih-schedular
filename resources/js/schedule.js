/**
 * Created by Joel on 02-Aug-15.
 * Requires: Moment.js, jQuery.query.js, jQuery.Cookie.js
 */
$.widget( "jih.schedule", {

    options: {
        value: 0,
        calendarId: 0,
        date : moment(),
        showCalendarOptions: true,
        calendarSize: 7,
        ClassOnFilledEventDivs : 'is-filled',
        ClassOnEventsInPastDivs : 'inThePast',
        retrieveCalendarId: function(){},
        registerTriggers: function(cal){
            $(document).keyup(function(e){
                var period = "day";
                if(e.shiftKey)
                    period = "week";
                if(e.ctrlKey)
                    period = "month";
                if(e.which==37){
                    cal.setOnDate(cal.CurDate().subtract(1,period));
                }
                if(e.which==39){
                    cal.setOnDate(cal.CurDate().add(1,period));
                }
            });
        },
        loadEvents : api.EventsForWeek,
        loadCalendars : api.Calendars,
        loadCalendarDetail : api.CalendarById,

        headDateFormat: 'dddd<br>DD MMM',
        titleDateFormat: 'MMMM YYYY',
        dateFormat:  'YYYY-MM-DD',
        onEventsLoading: function() {},
        onEventsLoaded: function(){},
        onTimeSlotClicked: function(calendar,element,event) {}
    },

    _create: function() {
        //Constructor
        this.options.registerTriggers(this);

        var cal = this;
        $('tbody td',this.element).click(function(event){
            cal.options.onTimeSlotClicked(
                {
                    calendar : cal,
                    event : this,
                    date : cal.getDateFromElement(this)
                }, event);
        });

        this.setOnDate(this.options.date);
        this.changeCalendar(this.options.calendarId);
    },

    gotoNext : function(amount,period){
        this.setOnDate(this.options.date.add(amount, period));
    },
    gotoLast : function(amount,period){
        this.setOnDate(this.options.date.subtract(amount,period));
    },
    goto : function(momentjs) {
        this.setOnDate(momentjs);
    },

    reload : function(){
        this.setOnDate(this.options.date);
    },

    setOnDate: function(date){
        this.options.date = date;

        history.pushState(null, null,$.query.SET('date',date.format(this.options.dateFormat)));
        this.updateHeadDates(date);
        $('#calendar-header-date').html(date.format(this.options.titleDateFormat));
        this.loadCalendarEvents(date);

        //add class  to event divs in the past
        var hourDiff = moment.duration(moment().diff(this.options.date)).asHours();
        var cells = $('td',this.element).removeClass(this.options.ClassOnEventsInPastDivs);
        var $i = 0;
        while (hourDiff > $i && $i <= 24*7){
            cells.eq(Math.floor($i/24)+$i%24*7).addClass(this.options.ClassOnEventsInPastDivs);
            $i++;
        }
    },

    changeCalendar: function(id){
        this.options.loadCalendarDetail(id,this._onCalendarDataLoaded,false,false,this);
    },


    _onCalendarDataLoaded : function(data,calendar){
        if(data.length < 1)
            alert('Calendar does not exist');
        else {
            var cal = data[0];
            calendar.options.calendarId = cal.id;
            $('#calenderDescription').html(cal.description);
            $('h1.entry-title').text(cal.name);

            $.cookie('calendarId',cal.id);
            history.pushState(null, null,$.query.SET('calendarId',cal.id));
            calendar.loadCalendarEvents(calendar.options.date);
        }
    },

    lastCalendarCall : null,
    loadCalendarEvents : function(date){
        this.emptyCalendar();
        if(this.lastCalendarCall)
            this.lastCalendarCall.abort();
        var cal = this;
        this.lastCalendarCall = this.options.loadEvents(this.options.calendarId,date, function(events){
            cal.saturateCalendar(events);
        } ); // --TODO buid in caching
    },


    saturateCalendar: function(events){
        var cal = this;
        $.each( events, function( index, event ) {
            var $dateEl = cal.getElementFromDate(moment(event.datetime));
            $dateEl.addClass(cal.options.ClassOnFilledEventDivs).text(event.name);
            $dateEl.data('event',event);
        });
    },

    getElementFromDate : function(date){
        var days = date.diff(this.options.date,'days');
        var hours = date.hours();
        var index = (hours * this.options.calendarSize) + days;
        return $('td',this.element).eq(index);
    },

    getDateFromElement: function(el){
        var index = $('td',this.element).index(el);
        var hour = Math.floor(index/7);
        var daysFromDate = index%7;
        return this.CurDate().add(daysFromDate,'days').add(hour,'hours');
    },

    emptyCalendar : function(){
        $('.'+this.options.ClassOnFilledEventDivs,this.element).removeClass(this.options.ClassOnFilledEventDivs).text('');
    },


    updateHeadDates : function(date){
        var cal = this;

        $('thead th',this.element).each(function(i){
            if(i>0){
                $(this).html(date.clone().add(i-1,'days').format(cal.options.headDateFormat));
            }
        });
    },
    CurDate : function(){
        return this.options.date.clone();
    },
    getCalendarId : function(){
        return this.options.calendarId;
    },
    _destroy: function() {
        //what to do when plugin is removed?
    }

});
