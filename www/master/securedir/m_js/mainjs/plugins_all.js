function pluginfunc_togglecomments()
{
	var rateid=$(this).attr("rid");
	$(".pluginitem_pastcommentsbox_comcont[rid='"+rateid+"']").toggle(function(){
		if($(".pluginitem_pastcommentsbox_comcont[rid='"+rateid+"']").css("display")=="none")
		{
			$(".pluginitem_pastcommentsbox_togglecomments[rid='"+rateid+"']").html("Show the hidden comments");
			$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").hide();
			plugin_statusmsg_hide(rateid);
			$(".pluginitem_pastcommentsbox_togglecomments[rid='"+rateid+"']").attr("title","Click to show the comments which were hidden by you");
		}
		else
		{
			plugin_refresh_comments(rateid);
			$(".pluginitem_pastcommentsbox_togglecomments[rid='"+rateid+"']").html("Hide all Comments");
			$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").show();
			$(".pluginitem_pastcommentsbox_togglecomments[rid='"+rateid+"']").attr("title","Click to Hide all Comments");
		}
	});
}

function pluginfunc_morecomments()
{
		var rid=$(this).attr("rid");
		var s=$(this).attr("limit_start");
		var increment=$(this).attr("limit_inc");

		s=parseInt(s);
		increment=parseInt(increment);

		plugin_refresh_stop(rid);

		var loadobj=new JS_LOADER();
		var convobj=new JS_CONVERTER();

		loadobj.ajaxloadpage(host_process+"/process_comments_item_display.php",function(results){
			jsonobj=convobj.xmltojson(results);
			//console.log("JSON RESPONSE: "+JSON.stringify(jsonobj));

			if(typeof jsonobj["document"]["comment_body_enclose"]["comment_body"]==="undefined")
			{
				alert("There are no more comments to show!");
				plugin_statusmsg_send("There are no more comments to show!",rid,"2");
				plugin_refresh(rid);
				return;
			}
			else
			{
				var commentbody=jsonobj["document"]["comment_body_enclose"]["comment_body"];
				var rateid=jsonobj["document"]["comment_body_enclose"]["@attributes"]["rid"];

				var this_comment,this_comment_html;
				this_comment_html='';

				if($.isArray(commentbody))
				{
					for(var i=0;i<commentbody.length;i++)
					{
						this_comment=commentbody[i];
						this_comment_comid=this_comment["comid"]["#text"];
						this_comment_comcont=this_comment["com_cont"]["#text"];
						this_comment_poster=this_comment["com_poster"]["#text"];
						this_comment_posttime=this_comment["com_posttime"]["#text"];
						this_comment_replyto=this_comment["com_replyto"]["#text"];
						this_comment_tagid=this_comment["com_tagid"]["#text"];
						this_comment_com_attachid=this_comment["com_attachid"]["#text"];
						this_comment_com_flag=this_comment["com_flag"]["#text"];

						this_comment_html+='<div class="pluginitem_pastcommentsbox_comcont" comid="'+this_comment_comid+'" posterid="'+this_comment_poster+'" cpicstat="2" rid="'+rateid+'"><div class="pluginitem_pastcommentsbox_comcont_poster"><img src="" width="50" height="50" class="pluginitem_pastcommentsbox_posterpicimg"></div><div class="pluginitem_pastcommentsbox_comcont_content">'+this_comment_comcont+'</div><div class="clear_float"></div></div>';
					}
				}
				else
				{
					this_comment=commentbody;
					this_comment_comid=this_comment["comid"]["#text"];
					this_comment_comcont=this_comment["com_cont"]["#text"];
					this_comment_poster=this_comment["com_poster"]["#text"];
					this_comment_posttime=this_comment["com_posttime"]["#text"];
					this_comment_replyto=this_comment["com_replyto"]["#text"];
					this_comment_tagid=this_comment["com_tagid"]["#text"];
					this_comment_com_attachid=this_comment["com_attachid"]["#text"];
					this_comment_com_flag=this_comment["com_flag"]["#text"];

					this_comment_html+='<div class="pluginitem_pastcommentsbox_comcont" comid="'+this_comment_comid+'" posterid="'+this_comment_poster+'" cpicstat="2" rid="'+rateid+'"><div class="pluginitem_pastcommentsbox_comcont_poster"><img src="" width="50" height="50" class="pluginitem_pastcommentsbox_posterpicimg"></div><div class="pluginitem_pastcommentsbox_comcont_content">'+this_comment_comcont+'</div><div class="clear_float"></div></div>';
				}

				$(".pluginitem_pastcommentsbox[rid='"+rateid+"']").html(this_comment_html);
				commentbox_append_comments(this_comment_html,rateid,increment);
				auto_add_compics();

				plugin_refresh(rateid);

				$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").attr("limit_start",s+increment);
				$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").attr("limit_inc",increment);
			}
		},function(error){
			console.log(error);
			alert("error");
		},"xml","2",{rateid:rid,start:s,increment:increment});
}

function commentbox_append_comments(html,rateid,noadded)
{
	if(typeof noadded==="undefined")
	{
		noadded=2;
	}
	$(".pluginitem_pastcommentsbox_morecomments[rid='"+rateid+"']").before(html);
	var totcomments=$(".pluginitem_pastcommentsbox[rid='"+rateid+"']").attr("totcomments");
	totcomments=parseInt(totcomments);
	totcomments+=noadded;
	$(".pluginitem_pastcommentsbox[rid='"+rateid+"']").attr("totcomments",totcomments);
}

$(document).ready(function(){
	$(".pluginbox").each(function(i){
		var rid=$(this).attr("rid");
		if($(this).css("display")!="none")
		{
			plugin_refresh(rid);
		}
	});

	listenevent($(".pluginitem_currating"),"click",function(){});
	listenevent($(".pluginitem_rateup"),"click",function(){
		var rid=$(this).attr("rid");
		var stat="1";

		var loadobj=new JS_LOADER();
		var convobj=new JS_CONVERTER();

		loadobj.ajaxloadpage(host_process+"/process_rating.php",function(results){
			jsonobj=convobj.xmltojson(results);
			var status_msg=jsonobj["document"]["statusmsg"]["#text"];
			var status_code=jsonobj["document"]["statuscode"]["#text"];
			if(status_code=="1")
			{
				plugin_statusmsg_send("Successfully Rated Up!",rid,"1");
				//plugin_statusmsg_hide(rid);
			}
			else
			if(status_code=="5")
			{
				plugin_statusmsg_send("You have already rated this Up!",rid,"2");
			}
			else
			if(status_code!="1")
			{
				alert("OOPS! An error occured while rating. DETAILS: "+status_msg+ " ERROR CODE: "+status_code);
			}
		},function(error){
			console.log(error);
			alert("Error");
			},"xml",2, {rateid:rid,ratestat:stat});
	});

	listenevent($(".pluginitem_ratedown"),"click",function(){
		var rid=$(this).attr("rid");
		var stat_down="-1";

		var loadobj=new JS_LOADER();
		var convobj=new JS_CONVERTER();

		loadobj.ajaxloadpage(host_process+"/process_rating.php",function(results){
			jsonobj=convobj.xmltojson(results);
			var status_msg=jsonobj["document"]["statusmsg"]["#text"];
			var status_code=jsonobj["document"]["statuscode"]["#text"];

			if(status_code=="1")
			{
				plugin_statusmsg_send("Successfully Rated Down!",rid,"1");
				//plugin_statusmsg_hide(rid);
			}
			else
			if(status_code=="6")
			{
				plugin_statusmsg_send("You have already rated this Down!",rid,"2");
			}
			else
			if(status_code!="1")
			{
				alert("OOPS! An error occured while rating. DETAILS: "+status_msg+ " ERROR CODE: "+status_code);
			}
		},function(err){alert("Error");console.log(err);},"xml",2, {rateid:rid,ratestat:stat_down});
	});

	listenevent($(".pluginitem_comment"),"click",function(){
		var rid=$(this).attr("rid");
		$(".pluginitem_commentbox[rid='"+rid+"']").toggle();
	});

	listenevent($(".pluginitem_share"),"click",function(){
		var rid=$(this).attr("rid");
		var dialogobj=new JS_DIALOG();
		dialogobj.open_modalmessage(".pluginitem_sharebox[rid='"+rid+"']","Share",{"Share it":function(){},"Close":function(){$(this).dialog("close");}},480,400);
	});

	listenevent($(".pluginitem_follow"),"click",function(){});

	listenevent($(".pluginitem_date"),"click",function(){});

	listenevent($(".pluginitem_pastcommentsbox_togglecomments"),"click",pluginfunc_togglecomments);

	listenevent($(".pluginitem_pastcommentsbox_morecomments"),"click",pluginfunc_morecomments);

	listenevent($(".pluginitem_meta"),"click",function(){
		var rid=$(this).attr("rid");
		var dialogobj=new JS_DIALOG();
		dialogobj.open_modalmessage(".pluginitem_metabox[rid='"+rid+"']","Meta Information",{"Help":function(){},"Close":function(){$(this).dialog("close");}},580,500);
	});

	listenevent($(".pluginitem_more"),"click",function(){
		var rid=$(this).attr("rid");
		var dialogobj=new JS_DIALOG();
		dialogobj.open_modalmessage(".pluginitem_morebox[rid='"+rid+"']","More",{"Help":function(){},"Close":function(){$(this).dialog("close");}},520,400);
	});

	listenevent($(".plugin_send_item_comment"),"click",function(){
		var rid=$(this).attr("rid");
		var comcontent=$(".pluginitem_commentbox_comment[rid='"+rid+"']").val();
		$(".pluginitem_commentbox_comment[rid='"+rid+"']").focus();

		var loadobj=new JS_LOADER();
		var convobj=new JS_CONVERTER();

		loadobj.ajaxloadpage(host_process+"/process_comments_item.php",function(results){
			jsonobj=convobj.xmltojson(results);
			var status_msg=jsonobj["document"]["statusmsg"]["#text"];
			var status_code=jsonobj["document"]["statuscode"]["#text"];
			if(status_code!="1")
			{
				alert("OOPS! An error occured while commenting. DETAILS: "+status_msg+ " ERROR CODE: "+status_code);
			}
			console.log("JSON RESPONSE: "+JSON.stringify(jsonobj));
			plugin_statusmsg_send("Comment Posted Successfully",rid,"1");
			$(".pluginitem_commentbox_comment[rid='"+rid+"']").val('');
		},function(){
			alert("error");
		},"xml","2",{rateid:rid,conts:comcontent});
	});
});