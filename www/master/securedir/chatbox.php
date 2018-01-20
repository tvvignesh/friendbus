<?php 
require_once 'adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';


$userobj=new ta_userinfo();
$uobj=new ta_userinfo();
$galobj=new ta_galleryoperations();

$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

$userobj->userinit();

$galid_att=$galobj->get_galid_special($userobj->uid,"16");

if(!(isset($tid)))
{
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
	{
		echo "";return FAILURE;
	}
}

$audobj=new ta_audience();
$msgobj=new ta_messageoperations();
$utilityobj=new ta_utilitymaster();

if($tid==""||$tid==FAILURE||$tid=="undefined")
{
	if(isset($_POST["fuid"]))
	{
		$fuid=$_POST["fuid"];
		$uobj->user_initialize_data($fuid);
		
		$ftid=$msgobj->thread_create($userobj->uid,"1",$uobj->fname." ".$uobj->mname." ".$uobj->lname." and ".$userobj->fname." ".$userobj->mname." ".$userobj->lname);
		$msgobj->thread_add_audience_users($ftid,Array($fuid));
		$tid=$ftid;
	}
	else
	{
		echo "";return FAILURE;
	}
}


$msgobj->get_thread_audienceid($tid);
$tres=$msgobj->getthreadoutline($tid);
$audid=$tres[0][changesqlquote(tbl_message_outline::col_audienceid,"")];
$thsubject=$tres[0][changesqlquote(tbl_message_outline::col_subject,"")];
if(!$audobj->audience_check_user($audid,$userobj->uid))
{
	echo "Sorry! You don't have permission to access this thread";return FAILURE;
}
$mymsg=$msgobj->getmsg($tid);
$tpicurl=$utilityobj->pathtourl($msgobj->get_threadpic($tid));
 
        	echo '
        <span class="hidden-xs hidden-sm">
		<div class="taprofilechatbox" style="display: inline-block; vertical-align: bottom;margin-left:20px;" data-threadid="'.$tid.'" data-vuid="'.$userobj->uid.'">
		<div class="tachatboxlabel">
			<img src="'.$tpicurl.'" height="30"> '.$thsubject.'
			<span class="pull-right chatbxclose"><i class="fa fa-times"></i></span>
		</div>
		<div class="tachatboxcont well">
				<div class="chatbx_headercontrol" align="right">
					<div class="btn-group">
						<button class="btn btn-default btn-sm box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-galid="'.$galid_att.'" title="Send Files"><i class="fa fa-paperclip"></i> Files</button>
						<!--<button class="btn btn-default btn-sm" title="Audio Call"><i class="fa fa-phone"></i></button>
						<!--<button class="btn btn-default btn-sm" title="Video Call"><i class="fa fa-video-camera"></i></button>
						<button class="btn btn-default btn-sm" title="Add Users to Conversation"><i class="fa fa-user-plus"></i></button>-->
						<button class="btn btn-default btn-sm box-tog" title="Add Smileys" data-mkey="box_smileys" data-toggle="modal" data-eltarget="-1" data-intarget=".chatbx_footertarea[data-threadid='.$tid.']"><i class="fa fa-smile-o"></i> Smileys</button>
						<button class="btn btn-default btn-sm" onclick=\'window.location.assign("/dash_conversations.php?tid='.$tid.'");\' title="View Full Conversation"><i class="fa fa-eye"></i> View</button>
        	
						 <div class="dropdown pull-left">
						 	<button class="dropdown-toggle btn btn-default btn-sm" data-toggle="dropdown"><i class="fa fa-cog"></i> More</button>
						    <ul class="dropdown-menu dropdown-menu-right">
						    	<!--<li><a href="#">Mute this conversation</a></li>-->
				                <li><a class="ajax-btn" data-mkey="tbx_delthread" data-threadid="'.$tid.'" data-prompt="1" data-eltarget="-1">Delete this conversation</a></li>
								
				                <!--<li><a href="#">Archive this conversation</a></li>-->
						    </ul>
						  </div>
			
					</div>
			
					<div style="clear:both;"></div>
				</div>
				
				<div class="chatbx_threads">
				';
        	
	        	echo '<div class="convbx_convcont" data-threadid="'.$tid.'" data-vuid="'.$userobj->uid.'">';
	        	require_once $_SERVER['DOCUMENT_ROOT'].'/conversations_message_cont.php';
	        	echo '</div>';
        			
        			
        			echo '
        		</div>
		 			
 			<div class="chatbx_footercontrol">
 				<textarea placeholder="Type in what you want to chat" class="form-control chatbx_footertarea" data-threadid="'.$tid.'"></textarea>
 			</div>        
		
		</div>
	</div>
        				';
        	?>
        
        </div></span>

<script type="text/javascript">
var mychatbox=$('.taprofilechatbox[data-threadid="<?php echo $tid;?>"]');
/*if(window.totchatbox==1)
{
	rpos=150;
}
else
{
	rpos=(window.totchatbox*150)+130;
}

mychatbox.css("right",rpos);*/
		
var loadobj=new JS_LOADER();
var tarea=$('.chatbx_footertarea[data-threadid="<?php echo $tid;?>"]');
	tarea.on("keyup",function(e){
		var mytarea=$(this);
		if(e.keyCode == 13 && !e.shiftKey)
		{            
            e.preventDefault();
    		var themsg=mytarea.val();
    		console.log("SENT:"+themsg);
    		var tid="<?php echo $tid;?>";
    		var uid="<?php echo $userobj->uid;?>";

    		var attvar=JSON.stringify(window.mediaidarr);
    		
    		loadobj.ajax_call({
    			  url:"/request_process.php",
    			  method:"POST",
    			  data:{mkey:"tbx_threadmsg",tid:tid,uid:uid,msg:themsg,attachments:attvar},
    			  cache:false,
    			  success:function(data){
    				  mytarea.val("");
    				  console.log("MSG SENT");
    				  var msgobj={};
    				  msgobj.msg={};
    				  msgobj.msg.tid=tid;
    				  comobj.msg_reload(msgobj);
    				  $("body").append(data.execscript);
    			  }
    		});
    		return false;
		}
		else
		if(e.keyCode == 13 && e.shiftKey)
		{
			process_tarea(mytarea);
		}
    });

	loadobj.jsload_emoticons(function(){
		$(".convbx_convcont").emoticonize();
	});
</script>
