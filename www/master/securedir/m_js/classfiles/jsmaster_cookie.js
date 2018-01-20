/**
 *
 * HAS ALL COOKIE OPERATIONS
 * @returns
 */
function JS_COOKIE() {};

/**
 *
 * SET A NEW COOKIE
 * @param name Cookie Key
 * @param value Cookie value
 * @param days No. of days cookie should exist
 * @param path Path where cookie has to be accessible (Defaults to / if not passed)
 */
JS_COOKIE.prototype.set=function(name,value,days,path)
{
	if(typeof path=="undefined")path="/";
	var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = name+"="+value+expires+"; path="+path;
};

/**
 *
 * READ THE VALUE OF A COOKIE
 * @param name Key of the cookie
 * @returns The cookie value with respect to the key
 */
JS_COOKIE.prototype.get=function(name)
{
	 var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
};

/**
 *
 * REMOVE A COOKIE BY ITS KEY
 * @param name KEY VALUE OF COOKIE TO BE REMOVED
 */
JS_COOKIE.prototype.remove=function(name)
{
	var cookieobj=new JS_COOKIE();
	cookieobj.set(name, "", -1);
};

/**
 *
 * REMOVE ALL COOKIES FROM THE BROWSER
 */
JS_COOKIE.prototype.destroy=function()
{
	var cookies = document.cookie.split(";");
	var $cookieobj=new JS_COOKIE();
	for (var i = 0; i < cookies.length; i++)
	{
		$cookieobj.remove(cookies[i].split("=")[0]);
	}
};