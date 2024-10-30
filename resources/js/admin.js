/**
 * Created by Joel on 22-Nov-14.
 */

(function($){
    $(document).ready(function(){
        //Add Date picker, need time though
        jQuery('input[type="datetime"]').datetimepicker();
        //jQuery('input[type="date"]').datepicker();

        //Add Search to OVERVIEW
        $('#search-form').submit(function(e){
            GotoUrl(jQuery.query.set('search', jQuery('#plugin-search-input').val()));
            e.preventDefault();
        });

        //Activate back links: <a href="#" class="go-back">Back</a>
        $('a.go-back').click(function(e){
            e.preventDefault();
            window.history.back();
        })
    })

})(jQuery);

function DeleteCalendar($id){
    api.DeleteCalendar($id,function(){
        GotoUrl();
    })
}


function DeleteEvent($id){
    api.DeleteEvent($id,function(){
        GotoUrl();
    })
}
function EditEvent($id){
    GotoUrl('?page=jih-EventForm&id='+$id)
}

function EditCalendar($id){
    GotoUrl('?page=jih-CalendarForm&id='+$id)
}

