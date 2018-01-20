/**
 * 
 * CONTAINS ERROR HANDLING FUNCTIONS
 * @returns
 */
function JS_ERROR(){};

/**
 * 
 * FUNCTION WHICH DISPLAYS ERROR MESSAGE
 * @param custerrormsg Custom error message sent by developer Default:OOPS! An error occurred!
 * @param errno Error number sent by developer or script Default:"0"
 * @param callback1 URL of page where request has to be redirected on clicking btn 1 (Defaults to goback which goes one step back to history)
 * @param ctext1 Text to be displayed on button 1 (Defaults to Go Back)
 * @param callback2 URL of page where request has to be redirected on clicking btn 2 (Defaults to index.php which goes to the current app's homepage)
 * @param ctext2 Text to be displayed on button 2 (Defaults to Go to Home Page)
 * @param errtitle Title of error box Text to be displayed in error box titlebar (Defaults to OOPS! An Error Occured!)
 * @param errmsg Script default error message Default:""
 */
JS_ERROR.prototype.displayerror=function(custerrormsg,errno,callback1,ctext1,callback2,ctext2,errtitle,errmsg){
	if(typeof custerrormsg==="undefined")
	{
		custerrormsg="OOPS! An Unexpected error occurred!";
	}
	
	if(typeof errno==="undefined")
	{
		errno="0";
	}
	
	if(typeof callback1==="undefined")
	{
		callback1="goback";
	}
	
	if(typeof ctext1==="undefined")
	{
		ctext1="Go Back";
	}
	
	if(typeof callback2==="undefined")
	{
		callback2="index.php";
	}
	
	if(typeof ctext2==="undefined")
	{
		ctext2="Get me out of here!";
	}
	
	if(typeof errtitle==="undefined")
	{
		errtitle="OOPS! An Error Occured!";
	}
	
	if(typeof errmsg==="undefined")
	{
		errmsg="";
	}
	
	$("body").append('<div id="errmsg"><div id="errbox_title">'+errtitle+'</div><div id="errbox_content"><div id="errbox_custmsg">'+custerrormsg+'</div> <div id="errbox_msg">'+errmsg+'</div> <div id="errbox_errcode">ERROR CODE:<div id="errbox_errno">'+errno+'</div></div></div> <div style="position:relative;left:35%;">	<div class="talboxbtn" align="center" id="err_okaybtn"><input type="button" value="'+ctext1+'"></div> <div class="talboxbtn" align="center" id="err_outbtn"><input type="button" value="'+ctext2+'"></div> <div style="clear:both;"></div> </div></div>'); 
	
	$(document).ready(function(){
		$("#err_okaybtn").click(function(){
			if(callback1=="goback")
			{
				history.go(-1);
			}
			else
			{
				window.location.assign(callback1);
			}
		});
		$("#err_outbtn").click(function(){
			if(callback2=="goback")
			{
				history.go(-1);
			}
			else
			{
				window.location.assign(callback2);
			}
		});
	});

};