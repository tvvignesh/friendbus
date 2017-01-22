function listenevent(elem,evtype,callbackfunc,data)
{
	if(typeof data===undefined)
	{
		data={};
	}
	elem.unbind(evtype,callbackfunc).bind(evtype,data,callbackfunc);
}

function listenevent_future(elem,parelem,evtype,callbackfunc,data)
{
	if(typeof data===undefined)
	{
		data={};
	}
	parelem.off(evtype,elem,callbackfunc).on(evtype,elem,data,callbackfunc);
}

function monitorhash()
{
	listenevent($(window),"hashchange", function() {
		console.log("HASH CHANGED"+window.location.hash);
		
		var loadobj=new JS_LOADER();
		loadobj.hashmanager();
	});
}

$(document).ready(function(){
	var utilityobj=new JS_UTILITY();
	var notifyobj=new JS_NOTIFICATIONS();
	var loadobj=new JS_LOADER();
	
	loadobj.ajax_call({
		  url:"/request_process.php",
		  method:"POST",
		  dataType:"json",
		  data:{mkey:"userstat_update",stat:"1"},
		  cache:false,
		  success:function(data){}
	});
	
	listenevent($(window),"hashchange",utilityobj.hashlisten);
	listenevent_future(".tachatboxlabel", $("body"), "click", chatbox_toggle);
	listenevent($("#notify_unread"),"click",notifyobj.toggle_notifypopup);
	listenevent($("#sbox_input"),"keydown",utilityobj.search_suggest);
	listenevent($(".rbar_item_head"),"click",utilityobj.collapse_rbaritem);
	listenevent($(".list_friend_profpic"),"click",function(){$(this).parent('.list_friend_lbar').next('.list_friend_rbar').slideToggle();});
	listenevent_future(".chatbxclose", $("body"), "click", function(){$(this).parents(".taprofilechatbox:first").hide();$(this).parents(".taprofilechatbox:first").remove();window.totchatbox--;});
	listenevent($(".galbox_viewvid_contimg"),"click",galbox_showviddet);
	listenevent($(".galbox_viewdoc_contimg"),"click",galbox_showdocdet);
	listenevent($("#galbox_view_slideshow"),"click",galbox_slideshow_start);
	listenevent($("#galbox_view_slideshow_vid"),"click",galbox_slideshow_start_vid);
	listenevent($(".galbox_notes_viewbtn"),"click",galbox_audio_shownotes);
	listenevent($(".galbox_notes_backbtn"),"click",galbox_audio_hidenotes);
	listenevent($(".galbox_notes_clearqueue"),"click",galbox_audio_clearqueue);
	listenevent($(".galbox_picshow_thumbpreview_icon"),"click",function(){$(this).css("display","none");$(".blueimp-gallery-controls>.indicator").style('display','block','important');});
	listenevent($("body"),"click",body_closepopovers);
	listenevent_future(".galbox_viewpic_cont",$("#galbox_tab_pic"),"click",galbox_showpicdet);
	listenevent($("#tbar_login"),"click",function(){$('#ta_loginbox').modal('toggle');});
	listenevent($("button.ico-toggle"),"click",toggle_btnico);
	listenevent_future(".ajax-btn",$("body"),"click",function(e){
		var predata=$(this).data();
		ajax_sender(e,predata);
	});
	listenevent_future(".box-tog",$("body"),"click",function(e){
		$("[data-toggle=popover]").popover('hide');
		var mydata=$(this).data();
		if("sucfunc" in mydata && mydata.sucfunc!="")
		{box_toggle(e,mydata,mydata,window[mydata.sucfunc]());}
		else
		{box_toggle(e,mydata);}
	});
	
	listenevent_future(".smiley-addbtn", $("body"), "click", function(){
		var smiley=$(this).attr("data-smiley");
		var intarget=$(this).parents("ul.smiley-ul:first").attr("data-intarget");
		
	    var cursorPosStart = $(intarget).prop('selectionStart');
	    var cursorPosEnd = $(intarget).prop('selectionEnd');
	    var v = $(intarget).html();
	    var textBefore = v.substring(0,  cursorPosStart );
	    var textAfter  = v.substring( cursorPosEnd, v.length );
	    $(intarget).html("");
	    $(intarget).html( textBefore+" "+ smiley );
	});
	
	listenevent_future(".notif-remove",$("body"),"click",function(){
		var comobj=new JS_COMMUNICATION();
		var nid=$(this).parents("li:first").attr("data-nid");
		comobj.notification_remove(nid);
	});
	
	listenevent_future(".fdbx-com-ldmore",$("body"),"click", function(e){
		$(e.target).prop('disabled', true);
		var cstart=parseInt($(this).attr("data-cstart"));
		var ctot=parseInt($(this).attr("data-ctot"));
		var ctid=$(this).attr("data-ctid");
		var tid=$(this).attr("data-threadid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"ld_com_post",cstart:cstart,ctot:ctot,ctid:ctid,tid:tid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".ta-fdbx-com[data-comtid='"+ctid+"']").append(data);
				$(".fdbx-com-ldmore[data-ctid='"+ctid+"']").attr("data-cstart",cstart+ctot);
				$(".fdbx-com-ldmore[data-ctid='"+ctid+"']").attr("data-ctot","10");
			  }
		});
	});
	
	listenevent_future(".ld_gal_pics",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var galid=$(this).attr("data-galid");
		var vuid=$(this).attr("data-vuid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"ld_gal_pics",st:start,tot:tot,galid:galid,vuid:vuid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".galbox_viewpic_outcont").append(data);
				$(".ld_gal_pics").attr("data-st",start+tot);
				$(".ld_gal_pics").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".ld_gal_vids",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var galid=$(this).attr("data-galid");
		var vuid=$(this).attr("data-vuid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"ld_gal_vids",st:start,tot:tot,galid:galid,vuid:vuid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled', false);
				$(".galbox_viewvid_outcont").append(data);
				$(".ld_gal_vids").attr("data-st",start+tot);
				$(".ld_gal_vids").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".ld_gal_docs",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var galid=$(this).attr("data-galid");
		var vuid=$(this).attr("data-vuid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"ld_gal_docs",st:start,tot:tot,galid:galid,vuid:vuid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".galbox_viewdoc_outcont").append(data);
				$(".ld_gal_docs").attr("data-st",start+tot);
				$(".ld_gal_docs").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".ld_notif_more",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"ld_notif_more",st:start,tot:tot},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".notify-ul").append(data);
				$(".ld_notif_more").attr("data-st",start+tot);
				$(".ld_notif_more").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".gpmem_ldmore",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var gpid=$(this).attr("data-gpid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  data:{mkey:"gpmem_ldmore",st:start,tot:tot,gpid:gpid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".gpmem_list").append(data);
				$(".gpmem_ldmore").attr("data-st",start+tot);
				$(".gpmem_ldmore").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".ldmore_gppost",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var gpid=$(this).attr("data-gpid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"ldmore_gppost",st:start,tot:tot,gpid:gpid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".gppost_col1").append(data.col1);
				$(".gppost_col2").append(data.col2);
				$(".ldmore_gppost").attr("data-st",start+tot);
				$(".ldmore_gppost").attr("data-tot","10");
			  }
		});
	});
	
	listenevent_future(".fd_ldmore_gppost",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var lvl=parseInt($(this).attr("data-lvl"));
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"fd_ldmore_gppost",lvl:lvl},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".fd_gppost_col1").append(data.col1);
				$(".fd_gppost_col2").append(data.col2);
				$(".fd_ldmore_gppost").attr("data-lvl",data.lvl);
			  }
		});
	});
	
	listenevent_future(".usr_fdload_more",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("st"));
		var tot=parseInt($(this).attr("tot"));
		var lvl=parseInt($(this).attr("lvl"));
		
		loadobj.ajax_call({
			  url:"/user_feeds.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"usr_fdload_more",st:start,tot:tot,lvl:lvl},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".usrfd_col1").append(data.col1);
				$(".usrfd_col2").append(data.col2);
				lvl=parseInt(data.lvl);
				st=parseInt(data.st);
				$(".usr_fdload_more").attr("st",st);
				$(".usr_fdload_more").attr("tot","2");
				$(".usr_fdload_more").attr("lvl",lvl);
			  }
		});
	});
	
	listenevent_future(".usr_fdcustload_more",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("st"));
		var tot=parseInt($(this).attr("tot"));
		var lvl=parseInt($(this).attr("lvl"));
		
		loadobj.ajax_call({
			  url:"/user_customfeeds.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"usr_fdcustload_more",st:start,tot:tot,lvl:lvl},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".usrcfd_col1").append(data.col1);
				$(".usrcfd_col2").append(data.col2);
				lvl=parseInt(data.lvl);
				st=parseInt(data.st);
				$(".usr_fdcustload_more").attr("st",st);
				$(".usr_fdcustload_more").attr("tot","2");
				$(".usr_fdcustload_more").attr("lvl",lvl);
			  }
		});
	});
	
	listenevent_future(".fd_ldmore_wallpost",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"fd_ldmore_wallpost",st:start,tot:tot},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".fdwall_col1").append(data.col1);
				$(".fdwall_col2").append(data.col2);
				st=parseInt(data.st);
				$(".fd_ldmore_wallpost").attr("data-st",st);
			  }
		});
	});
	
	listenevent_future(".fd_ldmore_featured",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"fd_ldmore_featured",st:start,tot:tot},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".fdft_col1").append(data.col1);
				$(".fdft_col2").append(data.col2);
				st=parseInt(data.st);
				$(".fd_ldmore_featured").attr("data-st",st);
			  }
		});
	});	
	
	listenevent_future(".ldmore_upvtbox",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var rateid=$(this).attr("data-rateid");
		
		loadobj.ajax_call({
			  url:"/lazyload.php",
			  method:"GET",
			  dataType:"json",
			  data:{mkey:"ldmore_upvtbox",st:start,tot:tot,rateid:rateid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".ul_upvbx").append(data.res);				
				st=parseInt(data.st);
				$(".ldmore_upvtbox").attr("data-st",st);
			  }
		});
	});	
	
	listenevent_future(".ldmore_cbx_lcont",$("body"),"click",function(e){
		$(e.target).prop('disabled', true);
		var start=parseInt($(this).attr("data-st"));
		var tot=parseInt($(this).attr("data-tot"));
		var lbltagid=$(this).attr("data-lbltagid");
		
		loadobj.ajax_call({
			  url:"/request_process.php",
			  method:"POST",
			  dataType:"json",
			  data:{mkey:"load_cbx_lcont",st:start,tot:tot,tagid:lbltagid},
			  cache:false,
			  success:function(data){
				$(e.target).prop('disabled',false);
				$(".cbx_lcont").append(data.op);				
				st=parseInt(data.st);
				$(".ldmore_cbx_lcont").attr("data-st",st);
				$("body").append(data.execscript);
			  }
		});
	});	
	
	jQuery(window).bind('beforeunload', function(e) {
		loadobj.ajax_call({
			  url:"/request_process.php",
			  method:"POST",
			  dataType:"json",
			  data:{mkey:"userstat_update",stat:"2"},
			  cache:false,
			  async: false,
			  success:function(data){}
		});
	});
	
	listenevent_future(".rcbx_item_cbopen",$("body"),"click",chatbox_open);
	listenevent_future("strong[data-mention]",$("body"),"click",function(){
		var usrid=$(this).attr("data-mention");
		window.location.assign("/users.php?uid="+usrid);
	});
	
	/*$(".sbox_input").change(function(){
	  	window.globalsender = $(this).val();
	 });*/
	
	rebindhotkeys();
	
	var loadobj=new JS_LOADER();
	loadobj.hashmanager();
	monitorhash();
	window.hashstatus=1;
});