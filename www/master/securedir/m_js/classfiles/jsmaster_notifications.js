function JS_NOTIFICATIONS(){};

JS_NOTIFICATIONS.prototype.get_all=function(status){
	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	var notify_id,notify_icon,notify_link,notify_status,notify_text,notify_time,notify_type,notify_uid,notifyarray,notify;

	if(typeof status==="undefined")
	{
		status="2";
	}

	loadobj.ajaxloadpage(host_process+"/process_notifications_display.php",function(results){
		var jsonobj=convobj.xmltojson(results);
		console.log(JSON.stringify(jsonobj));

		if(jsonobj["document"]["statuscode"]["#text"]!="1")
		{
			console.log("Error in fetching notifications! DETAILS:"+jsonobj["document"]["statusmsg"]["#text"]);
			return;
		}

		if(typeof jsonobj["document"]["notify_collection"]==="undefined")
		{
			return null;
		}

		notifyarray=jsonobj["document"]["notify_collection"];
		var notifyobj=new JS_NOTIFICATIONS();
		if($.isArray(notifyarray))
		{
			for(var i=0;i<notifyarray.length;i++)
			{
				notify=notifyarray[i];
				notify_id=notify["notify_id"]["#text"];
				notify_icon=notify["notify_icon"]["#text"];
				notify_link=notify["notify_link"]["#text"];
				notify_status=notify["notify_status"]["#text"];
				notify_text=notify["notify_text"]["#text"];
				notify_time=notify["notify_time"]["#text"];
				notify_type=notify["notify_type"]["#text"];
				notify_uid=notify["notify_uid"]["#text"];

				notifyobj.push(notify_id,notify_icon,notify_link,notify_status,notify_text,notify_time,notify_type,notify_uid);
			}
		}
		else
		{
			notify=notifyarray;
			notify_id=notify["notify_id"]["#text"];
			notify_icon=notify["notify_icon"]["#text"];
			notify_link=notify["notify_link"]["#text"];
			notify_status=notify["notify_status"]["#text"];
			notify_text=notify["notify_text"]["#text"];
			notify_time=notify["notify_time"]["#text"];
			notify_type=notify["notify_type"]["#text"];
			notify_uid=notify["notify_uid"]["#text"];

			notifyobj.push(notify_id,notify_icon,notify_link,notify_status,notify_text,notify_time,notify_type,notify_uid);
		}

	},function(error){
		console.log(error);
		alert("error");
	},"xml","2",{status:status});
};

JS_NOTIFICATIONS.prototype.get_all_nos=function()
{
	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();
	var notifyobj=new JS_NOTIFICATIONS();

	loadobj.ajaxloadpage(host_process+"/process_notifications_number.php",function(results){
		var jsonobj=convobj.xmltojson(results);
		console.log(JSON.stringify(jsonobj));

		if(jsonobj["document"]["statuscode"]["#text"]=="1")
		{
			window.ta_notify_no_read=jsonobj["document"]["notify_number_read"]["#text"];
			if(window.ta_notify_no_unread!=jsonobj["document"]["notify_number_unread"]["#text"])
			{
				notifyobj.get_all();
			}
			window.ta_notify_no_unread=jsonobj["document"]["notify_number_unread"]["#text"];
			window.ta_notify_no_total=jsonobj["document"]["notify_number_total"]["#text"];
		}

		if(typeof window.ta_timer_notifications==="undefined")
		{
			window.ta_timer_notifications=setTimeout(function(){
				notifyobj.get_all_nos();
			},window.timer_notification_no_fromserver);
		}
		else
		{
			clearTimeout(window.ta_timer_notifications);
			window.ta_timer_notifications=setTimeout(function(){
				notifyobj.get_all_nos();
			},window.timer_notification_no_fromserver);
		}

		if(jsonobj["document"]["statuscode"]["#text"]!="1")
		{
			console.log("error with notifications!");
			return;
		}

	},function(error){
		console.log(error);
	},"xml","2",{});
};

JS_NOTIFICATIONS.prototype.get_no_unread=function()
{
	var notifyobj=new JS_NOTIFICATIONS();
	if(typeof window.ta_notify_no_unread==="undefined")
	{
		notifyobj.get_all_nos();
		return 0;
	}
	return window.ta_notify_no_unread;
};

JS_NOTIFICATIONS.prototype.get_no_read=function()
{
	var notifyobj=new JS_NOTIFICATIONS();
	if(typeof window.ta_notify_no_unread==="undefined")
	{
		notifyobj.get_all_nos();
		return 0;
	}
	return window.ta_notify_no_read;
};

JS_NOTIFICATIONS.prototype.get_no_total=function()
{
	var notifyobj=new JS_NOTIFICATIONS();
	if(typeof window.ta_notify_no_unread==="undefined")
	{
		notifyobj.get_all_nos();
		return 0;
	}
	return window.ta_notify_no_total;
};

JS_NOTIFICATIONS.prototype.push=function(notify_id,notify_icon,notify_link,notify_status,notify_text,notify_time,notify_type,notify_uid)
{
	if(typeof window.ta_notifyarray[notify_id]!=="undefined")
	{
		return;
	}

	window.ta_notifyarray[notify_id]={};
	window.ta_notifyarray[notify_id]["text"]=notify_text;
	window.ta_notifyarray[notify_id]["icon"]=notify_icon;
	window.ta_notifyarray[notify_id]["link"]=notify_link;
	window.ta_notifyarray[notify_id]["status"]=notify_status;
	window.ta_notifyarray[notify_id]["type"]=notify_type;
	window.ta_notifyarray[notify_id]["time"]=notify_time;
	window.ta_notifyarray[notify_id]["uid"]=notify_uid;
};

JS_NOTIFICATIONS.prototype.pop=function(notify_id)
{

};

JS_NOTIFICATIONS.prototype.update_notifyicon_counter=function()
{
	var notifyobj=new JS_NOTIFICATIONS();

	if(typeof window.updtcounter==="undefined")
	{
		window.updtcounter=setTimeout(function(){notifyobj.update_notifyicon_counter();},window.timer_notification_icon_update);
	}
	else
	{
		$("#notify_unread_count").html(window.ta_notify_no_unread);
		clearTimeout(window.updtcounter);
		window.updtcounter=setTimeout(function(){notifyobj.update_notifyicon_counter();},window.timer_notification_icon_update);
	}
};

JS_NOTIFICATIONS.prototype.toggle_notifypopup=function()
{
	var notifyobj=new JS_NOTIFICATIONS();
	var utilityobj=new JS_UTILITY();

	var contents='';
	if(notifyobj.get_no_unread()==0)
	{
		contents="There are no notifications to show currently!";
		window.ta_notifyarray={};
	}
	for(var k in window.ta_notifyarray)
	{
		v=window.ta_notifyarray[k];
		notify_id=k;
		contents+='<div class="notify_notitems" notid="'+notify_id+' notlink="'+window.ta_notifyarray[notify_id]["link"]+'""><span class="ui-icon '+window.ta_notifyarray[notify_id]["icon"]+'" style="float: left; margin: 0 7px 20px 0;"></span>'+window.ta_notifyarray[notify_id]["text"]+'</div><div class="clear_float"></div>';
	}

	$("#notify_popup").html(contents);
	$("#notify_popup").slideToggle(function(){
		listenevent($(".notify_notitems"),"click",notifyobj.mark_read_navigate);
		notifyobj.refresh_notifypopup();
		utilityobj.hide_ritems_all();
		$(".rbar_item_enclose").toggle();
	});
};

JS_NOTIFICATIONS.prototype.refresh_notifypopup=function()
{
	var notifyobj=new JS_NOTIFICATIONS();

	var contents='';
	if(notifyobj.get_no_unread()==0)
	{
		contents="There are no notifications to show currently!";
		window.ta_notifyarray={};
	}
	for(var k in window.ta_notifyarray)
	{
		v=window.ta_notifyarray[k];
		notify_id=k;
		contents+='<div class="notify_notitems" notid="'+notify_id+'" notlink="'+window.ta_notifyarray[notify_id]["link"]+'"><span class="ui-icon '+window.ta_notifyarray[notify_id]["icon"]+'" style="float: left; margin: 0 7px 20px 0;"></span>'+window.ta_notifyarray[notify_id]["text"]+'</div><div class="clear_float"></div>';
	}
	$("#notify_popup").html(contents);

	if($("#notify_popup").css("display")!="none")
	{
		if(typeof window.ta_popupupdate==="undefined")
		{
			window.ta_popupupdate=setTimeout(function(){
				notifyobj.refresh_notifypopup();
			},window.timer_notification_popup_update);
		}
		else
		{
			clearTimeout(window.ta_popupupdate);
			window.ta_popupupdate=setTimeout(function(){
				notifyobj.refresh_notifypopup();
			},window.timer_notification_popup_update);
		}
		listenevent($(".notify_notitems"),"click",notifyobj.mark_read_navigate);
	}
	else
	{
		if(typeof window.ta_popupupdate!=="undefined")
		{
			clearTimeout(window.ta_popupupdate);
		}
	}
};

JS_NOTIFICATIONS.prototype.mark_read_navigate=function()
{
	var notid,navurl;
	var loadobj=new JS_LOADER();
	var convobj=new JS_CONVERTER();

	notid=$(this).attr("notid");
	navurl=$(this).attr("notlink");

	loadobj.ajaxloadpage(host_process+"/process_notifications_read.php",function(results){
		var jsonobj=convobj.xmltojson(results);
		console.log("READ: "+JSON.stringify(jsonobj));

		window.location.assign(navurl);
	},function(error){
		alert("error");
		console.log(error);
	},"xml","2",{notid:notid});
};

JS_NOTIFICATIONS.prototype.notification_setdelay=function(ndelay){
	PNotify.prototype.options.delay ? (function() {
	    PNotify.prototype.options.delay = ndelay;
	    update_timer_display();
	}()) : (alert('Timer is already at zero.'));
};

JS_NOTIFICATIONS.prototype.notification_push=function(nid,noptions,nmode,nstack){
	if(nmode=="1")
	{
		PNotify.desktop.permission();
	}
	if(typeof nstack!==undefined)
	{
		noptions.stack=nstack;
	}
	var notobj = new PNotify(noptions);
	return notobj;
};

JS_NOTIFICATIONS.prototype.notification_consume_alert=function(){
    if (_alert) return;
    _alert = window.alert;
    window.alert = function(message) {
        new PNotify({
            title: 'Alert',
            text: message
        });
    };
};

JS_NOTIFICATIONS.prototype.notification_release_alert=function(){
    if (!_alert) return;
    window.alert = _alert;
    _alert = null;
};


JS_NOTIFICATIONS.prototype.tour_gallery=function(){
	var steps_tour=[
		 		  	{
		 			    element: "#galbox_cont_tabs",
		 			    title: "Title of my step",
		 			    content: "Content of my step"
		 			},
		 			{
		 			    element: "#galbox_tab_pic",
		 			    title: "Title of my step",
		 			    content: "Content of my step"
		 			}
	 			];
	
	var tour = new Tour({
		  name: "tour",
		  steps: steps_tour,
		  container: "body",
		  keyboard: true,
		  storage: window.localStorage,
		  debug: false,
		  backdrop: false,
		  backdropContainer: 'body',
		  backdropPadding: 0,
		  redirect: true,
		  orphan: false,
		  duration: false,
		  delay: false,
		  basePath: "",
//		  template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation><button class='btn btn-default' data-role='prev'>&#60; Prev</button><span data-role='separator'>|</span><button class='btn btn-default' data-role='next'>Next &#62;</button></div><button class='btn btn-default' data-role='end'>End tour</button></nav></div>",
		  afterGetState: function (key, value) {},
		  afterSetState: function (key, value) {},
		  afterRemoveState: function (key, value) {},
		  onStart: function (tour) {},
		  onEnd: function (tour) {},
		  onShow: function (tour) {},
		  onShown: function (tour) {},
		  onHide: function (tour) {},
		  onHidden: function (tour) {},
		  onNext: function (tour) {},
		  onPrev: function (tour) {},
		  onPause: function (tour, duration) {},
		  onResume: function (tour, duration) {},
		  onRedirectError: function (tour) {}
		});
	return tour;
};