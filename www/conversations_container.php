<?php
if(!isset($_GET["mobile"]))
{
	$noecho="yes";
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
}
else
{
	require_once 'header.php';
}

	$userobj=new ta_userinfo();
	$audobj=new ta_audience();
	$msgobj=new ta_messageoperations();
	$utilityobj=new ta_utilitymaster();
	$galobj=new ta_galleryoperations();
	
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	
	$userobj->userinit();
	if(isset($_POST["tid"]))
	{
		$tid=$_POST["tid"];
	}
	else
	if(isset($_GET["tid"]))
	{
		$tid=$_GET["tid"];
	}
	else
	if(isset($initid)&&$initid!=FAILURE)
	{
		$tid=$initid;
	}
	else
	{
		$tid=FAILURE;
		echo "";return FAILURE;
	}
	$msgobj->get_thread_audienceid($tid);
	$tres=$msgobj->getthreadoutline($tid);
	$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
	if(!$audobj->audience_check_user($audid,$userobj->uid))
	{
		echo "Sorry! You don't have permission to access this thread";return FAILURE;
	}	
	$thpicurl=$msgobj->get_threadpic($tid);
	$galid_att=$galobj->get_galid_special($userobj->uid,"16");
?>
    <div class="card hovercard">
    <div id="cbx-controlbtns" class="pull-right">
    	<div class="btn-group" role="group">
    		<!-- <button type="button" id="convbx_searchconv" class="btn btn-default pull-left" title="Search within this Conversation"><i class="fa fa-search"></i></button>-->
	    	<button type="button" id="convbx_adduser" class="btn btn-default pull-left box-tog" title="Add User(s) to this Conversation" data-mkey="box_participants_add" data-threadid="<?php echo $tid;?>" data-eltarget="-1"><i class="fa fa-user-plus"></i></button>
	    	<!-- <button type="button" id="convbx_starconv" class="btn btn-default pull-left" title="Favorite this Conversation"><i class="fa fa-star"></i></button>-->
	    	<button type="button" id="convbx_viewtrash" class="btn btn-default pull-left ajax-btn" title="View Trash (or) Delete this Conversation" data-mkey="tbx_delthread" data-threadid="<?php echo $tid;?>" data-prompt="1" data-eltarget="-1"><i class="fa fa-trash"></i></button>
	    	<button type="button" id="convbx_addlist" class="btn btn-default pull-left" title="Add this Conversation to a List"><i class="fa fa-list"></i></button>
	    	
	    	<?php echo '<button type="button" id="convbx_tpicupload" class="btn btn-default pull-left box-tog" title="Upload Thread Picture" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-afterupload="uplded_threadpic" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-upload"></i></button>';?>
    	</div>
    </div>
    
        <div class="card-background"></div>
        <div class="useravatar">
            <img alt="" src="<?php echo $thpicurl;?>">
        </div>
        <div class="card-info"> <span class="card-title"><?php echo $tres[0][changesqlquote(tbl_message_outline::col_subject,"")];?></span></div>
    </div>
    <div class="btn-pref btn-group btn-group-justified btn-group-lg btn-tabs convbx_innercont" role="group" aria-label="Conversations" data-threadid="<?php echo $tid;?>">
        <div class="btn-group" role="group">
            <a href="#convbx_textchat_cont"><button type="button" id="convbx_textchat" class="btn btn-primary" data-toggle="tab"><i class="fa fa-comments"></i>
                <div class="hidden-xs">Message</div>
            </button></a>
        </div>
        <!-- <div class="btn-group" role="group">
            <a href="#convbx_vidchat_cont"><button type="button" id="convbx_vidchat" class="btn btn-default" data-toggle="tab"><i class="fa fa-video-camera"></i>
                <div class="hidden-xs">Video/Audio Chat</div>
            </button></a>
        </div>
        <div class="btn-group" role="group">
            <a href="#convbx_call_cont"><button type="button" id="convbx_call" class="btn btn-default" data-toggle="tab"><i class="fa fa-phone"></i>
                <div class="hidden-xs">Call</div>
            </button></a>
        </div>
        <div class="btn-group" role="group">
            <a href="#convbx_media_cont"><button type="button" id="convbx_media" class="btn btn-default" data-toggle="tab"><i class="fa fa-file"></i>
                <div class="hidden-xs">Media</div>
            </button></a>
        </div>
        <div class="btn-group" role="group">
            <a href="#convbx_archives_cont"><button type="button" id="convbx_archives" class="btn btn-default" data-toggle="tab"><i class="fa fa-floppy-o"></i>
                <div class="hidden-xs">Drafts & Archives</div>
            </button></a>
        </div>-->
        <div class="btn-group" role="group">
            <a href="#convbx_settings_cont"><button type="button" id="convbx_settings" class="btn btn-default" data-toggle="tab"><i class="fa fa-cog"></i>
                <div class="hidden-xs">Settings</div>
            </button></a>
        </div>
        
    </div>

        <div class="well" style="padding:0px;">
      <div class="tab-content convbx_tabcont" data-threadid="<?php echo $tid;?>">
	     <div class="tab-pane fade in active" id="convbx_textchat_cont" data-taburl="conversations_message.php" data-threadid="<?php echo $tid;?>">
	      	<?php require 'conversations_message.php';?>
	     </div>
	     <!--<div class="tab-pane fade in" id="convbx_vidchat_cont" data-taburl="conversations_vidchat.php" data-threadid="<?php echo $tid;?>">
	     </div>
	     <div class="tab-pane fade in" id="convbx_call_cont" data-taburl="conversations_call.php" data-threadid="<?php echo $tid;?>">
	     </div>
	     <div class="tab-pane fade in" id="convbx_media_cont" data-taburl="conversations_media.php" data-threadid="<?php echo $tid;?>">
	     </div>
	     <div class="tab-pane fade in" id="convbx_archives_cont" data-taburl="conversations_archives.php" data-threadid="<?php echo $tid;?>">
	     </div>-->
	     <div class="tab-pane fade in" id="convbx_settings_cont" data-taburl="conversations_settings.php" data-threadid="<?php echo $tid;?>">
	     </div>
      </div>
    </div>
    
<?php
if(isset($_GET["mobile"]))
{
	require_once MASTER_TEMPLATE.'/footer.php';
	$assetobj=new ta_assetloader();
	$assetobj->load_js_product_login();

?>

<script type="text/javascript">
$(document).ready(function(){
	var loadobj=new JS_LOADER();
	
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
});
</script>

<?php }?>