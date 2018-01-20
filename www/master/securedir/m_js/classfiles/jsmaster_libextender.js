/**
 * 
 * CONTAINS FUNCTIONS WHICH ADDS CUSTOM FUNCTIONALITIES TO LIBRARIES
 * @returns
 */
function JS_EXTENDER(){};

/**
 * 
 * EXTENDS FUNCTIONS OF JQUERY THROUGH CUSTOM FUNCTIONS
 * @param funcflag Functionality to be added (1-slide left & right,2-pulsate effect)
 */
JS_EXTENDER.prototype.jquery=function(funcflag){
switch (funcflag) {
case "1":
	 jQuery.fn.extend({
         slideRightShow: function(speed,callback) {
             return this.each(function() {
                 $(this).show('slide', {direction: 'right'}, speed, callback);
             });
         },
         slideLeftHide: function(speed,callback) {
             return this.each(function() {
                 $(this).hide('slide', {direction: 'left'}, speed, callback);
             });
         },
         slideRightHide: function(speed,callback) {
             return this.each(function() {  
                 $(this).hide('slide', {direction: 'right'}, speed, callback);
             });
         },
         slideLeftShow: function(speed,callback) {
             return this.each(function() {
                 $(this).show('slide', {direction: 'left'}, speed, callback);
             });
         }
     });
	break;
case "2":
	$.fn.pulse = function(options) {
	    var options = $.extend({
	        times: 3,
	        duration: 1000
	    },options);
	    
	    var period = function(callback) {
	        $(this).animate({opacity: 0}, options.duration, function() {
	            $(this).animate({opacity: 1}, options.duration, callback);
	        });
	    };
	    return this.each(function() {
	        var i = +options.times, self = this,
	        repeat = function() { --i && period.call(self, repeat) };
	        period.call(this, repeat);
	    });
	};
break;
default:alert("Invalid Choice!");break;
	break;
}
	
};