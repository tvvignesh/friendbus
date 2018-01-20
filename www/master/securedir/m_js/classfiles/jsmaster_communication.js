/**
 * 
 * COMMUNICATION CLASS
 * @returns
 */
function JS_COMMUNICATION(){}

/**
 * 
 * INITIALIZE WEB SOCKET AND CALLBACKS
 */
JS_COMMUNICATION.prototype.socket_init=function(opencbk,messagecbk,closecbk,errcbk,host)
{	
	var comobj=new JS_COMMUNICATION();
	if(typeof opencbk==='undefined'){opencbk=function(){console.log("socket open");};}
	if(typeof messagecbk==='undefined'){messagecbk=function(e){console.log("received");comobj.process_message(e.data);console.log(e);};}
	if(typeof closecbk==='undefined'){closecbk=function(){console.log("socket closed");};}
	if(typeof errcbk==='undefined'){errcbk=function(){console.log("socket error");};}
	if(typeof host==='undefined'){host="wss://www.friendbus.com/websocket/";}
	var socket;
	try {
		socket = new WebSocket(host);
		console.log('WebSocket - status '+socket.readyState);
		socket.onopen=opencbk; 
		socket.onmessage=messagecbk;		
		socket.onclose=closecbk;
		socket.onerror=errcbk;
	}
	catch(ex){
		console.log(ex); 
	}
	console.log(socket);
	return socket;
};

/**
 * SEND A MESSAGE THROUGH A CONNECTED SOCKET
 * */
JS_COMMUNICATION.prototype.socket_send=function(socket,msg)
{
	try {
		socket.send(msg); 
		console.log('Sent: '+msg);
	} catch(ex) { 
		console.log(ex); 
	}
};

/**
 * QUIT A SOCKET CONNECTION
 * */
JS_COMMUNICATION.prototype.socket_quit=function(socket)
{
	if (socket != null) {
		console.log("Goodbye!");
		socket.close();
		socket=null;
	}
};

/**
 * RECONNECT A SOCKET AND GET NEW SOCKET OBJECT
 * */
JS_COMMUNICATION.prototype.socket_reconnect=function(socket)
{
	this.socket_quit(socket);
	return this.socket_init();
};

JS_COMMUNICATION.prototype.process_message=function(msg)
{
	var comobj=new JS_COMMUNICATION();
	var msgobj = jQuery.parseJSON(msg);
	console.log(msgobj);
	var uid=msgobj.uid;
	var target=msgobj.target;
	var mtype=msgobj.mtype;
	var message=msgobj.msg;
	
	console.log(message.elkey);
	
	if(typeof message.elkey!=="undefined")
	{
		switch(message.elkey)
		{
			case "frequest":
			case "frequest_accepted":
			case "thread_addedparticipant":
				comobj.notification_update(msgobj);
				break;
			case "msg_new":
				comobj.notification_update(msgobj);
				comobj.msg_reload(msgobj);
				break;
			default:
				comobj.notification_update(msgobj);
			break;
		}
	}
};

JS_COMMUNICATION.prototype.notification_update=function(msgobj)
{
	if(jQuery.inArray(msgobj.msg.elid, window.notifarr) !== -1)
	{return;}
	else
	{window.notifarr.push(msgobj.msg.elid);}
	
	var msg=msgobj.msg;
	var comobj=new JS_COMMUNICATION();
	var apphtml='';
	if(msg.elkey=="frequest")
	{
		var fuid=msg.suid;
	   apphtml='<span class="s_fshipstat_'+fuid+'" style="display:none;"></span><li class="list-group-item cbx-frcont-'+fuid+'" data-nid="'+msg.elid+'"><i class="fa fa-times pull-right notif-remove"></i><a href="'+msg.ellink+'"><img src="'+msg.elpic+'" style="height:inherit;" width="32" height="32" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span></a><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span><div class="btn-group pull-right"><button class="btn btn-default ajax-btn" data-mkey="s_togfriend" data-fuid="'+fuid+'" data-suchide=".cbx-frcont-'+fuid+'" data-sucfunc="notif_remove" data-nid="'+msg.elid+'" data-eltarget=".s_fshipstat_'+fuid+'"><i class="fa fa-check-circle"></i> Accept</button><button class="btn btn-default ajax-btn" data-mkey="s_removefriend" data-fuid="'+fuid+'" data-suchide=".cbx-frcont-'+fuid+'" data-sucfunc="notif_read" data-eltarget=".s_fshipstat_'+fuid+'"><i class="fa fa-times-circle"></i> Decline</button></div><div style="clear:both;"></div></li>';
	}
	else
	if(msg.elkey=="frequest_accepted")
	{
		apphtml='<li class="list-group-item data-nid="'+msg.elid+'"><i class="fa fa-times pull-right notif-remove"></i><a href="'+msg.ellink+'"><img src="'+msg.elpic+'" style="height:inherit;" width="32" height="32" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span></a><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span><div style="clear:both;"></div></li>';
	}
	else
	if(msg.elkey=="thread_addedparticipant")
	{
		apphtml='<li class="list-group-item data-nid="'+msg.elid+'"><i class="fa fa-times pull-right notif-remove"></i><a href="'+msg.ellink+'"><img src="'+msg.elpic+'" style="height:inherit;" width="32" height="32" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span></a><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span><div style="clear:both;"></div></li>';
	}
	else
	if(msg.elkey=="msg_new")
	{
		apphtml='<li class="list-group-item data-nid="'+msg.elid+'"><i class="fa fa-times pull-right notif-remove"></i><a href="'+msg.ellink+'"><img src="'+msg.elpic+'" style="height:inherit;" width="32" height="32" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span></a><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span><div style="clear:both;"></div></li>';
	}
	else
	{
		apphtml='<li class="list-group-item data-nid="'+msg.elid+'"><i class="fa fa-times pull-right notif-remove"></i><a href="'+msg.ellink+'"><img src="'+msg.elpic+'" style="height:inherit;" width="32" height="32" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span></a><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span><div style="clear:both;"></div></li>';
	}
	
	if($(".notify-ul").length>0)
	{
		//$(".notify-ul").prepend('<li class="list-group-item" data-nid="'+msg.elid+'"><a href="'+msg.ellink+'"><i class="fa fa-times pull-right"></i><img src="'+msg.elpic+'" style="height:inherit;" width="50" height="50" class="pull-left"><span class="pull-right" style="width:70%;">'+msg.elval+'</span><br><span class="pull-right"><i class="fa fa-info-circle"></i> <em>'+msg.eltime+'</em></span></a><div style="clear:both;"></div></li>');
		$(".notify-ul").prepend(apphtml);
		comobj.notification_inc();
	}
};

JS_COMMUNICATION.prototype.notification_inc=function()
{
	var cnt=$(".cnt-notif:first").html();
	cnt=parseInt(cnt);
	cnt++;
	$(".cnt-notif:first").html(cnt);
	$(".cnt-notif").css("display","block");
	$(".notification-none").css("display","none");
};

JS_COMMUNICATION.prototype.notification_dec=function()
{
	var cnt=$(".cnt-notif:first").html();
	cnt=parseInt(cnt);
	cnt--;
	if(cnt<0)cnt=0;
	$(".cnt-notif:first").html(cnt);
	if(cnt==0)
	{
		$(".cnt-notif").css("display","none");
	}
	else
	{
		$(".notification-none").css("display","none");
		$(".cnt-notif").css("display","block");
	}
};

JS_COMMUNICATION.prototype.notification_remove=function(nid)
{
	var comobj=new JS_COMMUNICATION();
	var loadobj=new JS_LOADER();
	loadobj.ajax_call({
		  url:"/request_process.php",
		  method:"POST",
		  data:{mkey:"nbx_delnot",nid:nid},
		  cache:false,
		  success:function(data){
			  $("[data-nid="+nid+"]").hide();
			  comobj.notification_dec();
			  window.notifarr.splice(nid,1);
		  },
		  error:function(err){
			  alert("OOPS! An error occured");
			  console.log(err);
		  }
	});
};

JS_COMMUNICATION.prototype.msg_reload=function(msgobj)
{
	var tid=msgobj.msg.tid;
	var loadobj=new JS_LOADER();
	
	loadobj.ajax_call({
		  url:"/conversations_message_cont.php",
		  method:"GET",
		  data:{tid:tid},
		  cache:false,
		  success:function(data){
			  var mydiv=$(".convbx_convcont[data-threadid="+tid+"]");
			  mydiv.html(data);
			  loadobj.jsload_emoticons(function(){
				  mydiv.emoticonize();
				});
			  mydiv.each(function(){
				  $(this).animate({ scrollTop: $(this).prop("scrollHeight")+20}, 1000);
			  });
		  },
		  error:function(err){
			  alert("OOPS! An error occured");
			  console.log(err);
		  }
	});
};

/*this.socket_send(mysocket,'{"uid":"'+uid+'","mtype":"1","msg":"Initialize User socket","target":"-1"}');*/
