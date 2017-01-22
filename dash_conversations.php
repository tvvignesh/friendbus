<?php
ob_start();
require_once 'header.php';
$themeobj->template_load_left();
$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
if(!$userobj->checklogin())
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	die('<div id="template_content_body">Please <a href="/index.php">login</a> to access this section</div>');
}
$userobj->userinit();
$msgobj=new ta_messageoperations();
?>
<div id="template_content_body">

<button class="btn btn-primary box-tog pull-left ta-rmargin" data-mkey="box_thread_new" data-toggle="modal" data-tuid="" data-eltarget="-1" title="Create a new thread to have conversations with people you know"><i class="fa fa-plus-circle"></i> <span class="hidden-xs hidden-sm hidden-md">New Thread</span></button>
<div class="dropdown pull-left ta-lmargin">
	<button class="btn btn-default" data-toggle="dropdown"><i class="fa fa-tags"></i> <span class="hidden-xs hidden-sm hidden-md">Labels</span></button>
	<ul class="dropdown-menu">
		<li><a class="box-tog" data-mkey="box_tagcreate" data-toggle="modal" title="Add a Label Tag"><i class="fa fa-plus-circle"></i> New Label</a></li>
		<?php 
		$tagres=$msgobj->tagpost_get($userobj->uid);
		for($i=0;$i<count($tagres);$i++)
		{
			$tagid=$tagres[$i][changesqlquote(tbl_tags_post::col_tagid,"")];
			$tagname=$tagres[$i][changesqlquote(tbl_tags_post::col_tagname,"")];
			echo '<li><a class="tbx_ldtag" data-ctagid="'.$tagid.'" style="cursor:pointer;">'.$tagname.'</a></li>';
		}
		?>
		<li role="separator" class="divider"></li>
		<li><a class="tbx_ldtag" data-ctagid="-1" style="cursor:pointer;">View All</a></li>
	</ul>
</div>
 
<br><br>

<div class="row">
<div class="col-lg-3 col-sm-3">
	<ul class="list-group cbx_lcont" data-lbltagid="-1">
	<?php 	
		$res=$msgobj->getuserthreadoutline($userobj->uid,0,15,"1");
		if($res==FAILURE)
		{
			$initid=FAILURE;
			echo "You have neither created nor received any messages yet!";
		}
		else
		{
			$initid=$res[0][changesqlquote(tbl_message_incoming::col_tid,"")];
			for($i=0;$i<count($res);$i++)
			{
				$tid=$res[$i][changesqlquote(tbl_message_incoming::col_tid,"")];
				$latestupdate=$res[$i][changesqlquote(tbl_message_incoming::col_rtime,"")];
				$res1=$msgobj->getthreadoutline($tid);
				$mtype=$res1[0][changesqlquote(tbl_message_outline::col_msgtype,"")];
				if($mtype!="1"&&$mtype!="2")
				{
					if($initid==$tid)$initid="";
					continue;
				}
				if($initid=="")$initid=$tid;
				$no_msg_unread=$msgobj->get_no_unread_messages($userobj->uid,$tid);
				$res2=$msgobj->get_thread_audience($tid);
				$audno=count($res2);
				if($no_msg_unread==0)
				{
					$tico='<span class="convbx_convstaticon badge"><i class="fa fa-check"></i></span>';
				}
				else
				{
					$tico='<span class="convbx_convstaticon badge"><i class="fa fa-reply"></i></span>';
				}
				$tpicurl=$msgobj->get_threadpic($tid);
				echo
				'
				<li class="list-group-item convbx_overview" data-threadid="'.$tid.'">
					<small class="convbx_convtime">'.$latestupdate.'</small>
					<kbd class="convbx_newbadge">'.$no_msg_unread.'</kbd>
					'.$tico.'
					<img alt="" src="'.$tpicurl.'" width="50" height="50" class="convbx_thumbimg">
					<small>'.$res1[0][changesqlquote(tbl_message_outline::col_subject,"")].'</small>
				</li>
			';
			}
		}
	
	?>
	
	</ul>
	
	<button class="btn btn-default ldmore_cbx_lcont" data-lbltagid="-1" data-st="15" data-tot="10">Load More</button>
</div>

<div class="col-lg-9 col-sm-9 convbx_container hidden-xs hidden-sm">
<?php 
	require_once ROOT_SERVER.'/conversations_container.php';
?>
</div>
    
  </div>
    
    

</div>

<div id="overlay"></div>

<?php
$themeobj->template_load_right();
require_once MASTER_TEMPLATE.'/footer.php';
$assetobj=new ta_assetloader();
$assetobj->load_js_final();
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
<?php 
	if(isset($_GET["thid"]))
	{
		echo '
		if( screen.width <= 480 ){
			window.location.assign("/conversations_container.php?tid='.$_GET["thid"].'");
		}
		else
		{
			window.location.assign("/dash_conversations.php?tid='.$_GET["thid"].'");
		}
		';
	}
?>


function confuncinit(){
	var loadobj=new JS_LOADER();

	<?php 
		if(isset($_GET["thid"]))
		{
			echo 'if( screen.width > 480 ){
				
				loadobj.ajax_call({
				  url:"/conversations_container.php",
				  method:"POST",
				  data:{tid:"'.$_GET["thid"].'"},
				  cache:false,
				  success:function(data){
					window.location.hash="";
					$(".convbx_container").html(data);
					$(".convbx_overview").removeClass("active");
					$(".convbx_overview[data-threadid='.$_GET["thid"].']").addClass("active");
				  }
			});
			}';
		}
	?>


	listenevent_future(".tbx_ldtag",$("body"),"click",function(){
		var tagid=$(this).attr("data-ctagid");
		$(".cbx_lcont").attr("data-lbltagid",tagid);
		$(".ldmore_cbx_lcont").attr("data-lbltagid",tagid);
		$(".ldmore_cbx_lcont").prop("disabled",false);
		loadobj.ajax_call({
			  url:"/request_process.php",
			  method:"POST",
			  data:{mkey:"load_cbx_lcont",tagid:tagid},
			  cache:false,
			  success:function(data){
				  $(".cbx_lcont").html(data.op);
			  }
		});

	});
	
	
	listenevent_future(".convbx_overview",$("body"), "click", function(){
		var tid=$(this).attr("data-threadid");
		if( screen.width > 480 ) { 
			loadobj.ajax_call({
				  url:"/conversations_container.php",
				  method:"POST",
				  data:{tid:tid},
				  cache:false,
				  success:function(data){
					$(".convbx_container").html(data);
					$(".convbx_overview").removeClass("active");
					$(".convbx_overview[data-threadid="+tid+"]").addClass("active");
				  }
			});
		}
		else
		{
			window.location.assign("/conversations_container.php?tid="+tid+"&mobile=true");
		}
	});

	listenevent_future(".th_sendmsgbtn",$("body"), "click",function(){
		var themsg=$('.convbx_sendinput').html();
		var tid=$(".convbx_convcont").attr("data-threadid");
		var uid=$(".convbx_convcont").attr("data-vuid");
		console.log(window.mediaidarr);
		var attvar=JSON.stringify(window.mediaidarr);
		
		loadobj.ajax_call({
			  url:"/request_process.php",
			  method:"POST",
			  data:{mkey:"tbx_threadmsg",tid:tid,uid:uid,msg:themsg,attachments:attvar},
			  cache:false,
			  success:function(data){
				  $('.convbx_sendinput').html("");
				  var msgobj={};
				  msgobj.msg={};
				  msgobj.msg.tid=tid;
				  comobj.msg_reload(msgobj);
				  $("body").append(data.execscript);
			  },
			  error:function(err){
				  alert("OOPS! An error occured");
				  console.log(err);
			  }
		});
	});
	
	var curtid=$(".convbx_innercont").attr("data-threadid");
	$(".convbx_overview").removeClass("active");
	$(".convbx_overview[data-threadid="+curtid+"]").addClass("active");
};

document.addEventListener("DOMContentLoaded",confuncinit);
</script>