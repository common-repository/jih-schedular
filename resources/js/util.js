/**
 * Created by joel on 27/10/2014.
 */
$ = jQuery;

var Log = function(data){
    console.log(data);
};


var GotoLoginPage = function(){
  alert('GotoLoginPage not implement yet');
};

/**
 * @return {boolean}
 */
function IsDefined(item){
    return typeof item !== "undefined";
}

/**
 * @return {boolean}
 */
function IsEmpty(item){
    return IsDefined(item) && (!item || 0 === item.length);
}

function GotoUrl($url){
    if(!IsDefined($url))
        $url = window.location.href;
    window.location.href = $url;
}

/* DATE FUNCTIONS*/
function ThisWeek(){
    return moment().day(0).startOf('day');
}


/* Object prototype Extensions */
String.prototype.ucFirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

//Serialize Form to Object
(function($){
    $.fn.serializeObject = function () {
        "use strict";

        var result = {};
        var extend = function (i, element) {
            var node = result[element.name];

            // If node with same name exists already, need to convert it to an array as it
            // is a multi-value field (i.e., checkboxes)

            if ('undefined' !== typeof node && node !== null) {
                if ($.isArray(node)) {
                    node.push(element.value);
                } else {
                    result[element.name] = [node, element.value];
                }
            } else {
                result[element.name] = element.value;
            }
        };

        $.each(this.serializeArray(), extend);
        return result;
    };
})(jQuery);