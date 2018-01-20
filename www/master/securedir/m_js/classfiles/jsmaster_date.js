/**
 * HAS ALL DATE OPERATIONS
 * @returns
 */
function JS_DATE(){}

/**
 * 
 * RETURNS THE GMT OFFSET OF THE USER
 * @returns GMT OFFSET
 */
JS_DATE.prototype.getgmtoffset=function(){
	var localTime = new Date();
	var gmtOffset;
	gmtOffset = localTime.getTimezoneOffset()/60 * (-1);
	return gmtOffset;
};