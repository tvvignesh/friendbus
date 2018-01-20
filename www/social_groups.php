<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$muobj=new ta_userinfo();
$galobj=new ta_galleryoperations();
$assetobj=new ta_assetloader();
$socialobj=new ta_socialoperations();

$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_groups.php",0,'/');
	die('<div id="template_content_body">Please login</div>');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}

$galid_att=$galobj->get_galid_special($userobj->uid,"16");
?>

<div id="template_content_body">

<?php 
	if(!isset($_GET["gpid"]))
	{
		echo 'Please select the group before trying to view it!';
		return;
	}
	$gpid=$_GET["gpid"];
	$gpres=$socialobj->groups_get($gpid);
	if(count($gpres)==0)
	{
		echo 'OOPS! No such group exists in our database!';
		return;
	}
	$gpflag=$gpres[0][changesqlquote(tbl_groups_info::col_gpflag,"")];
	if($gpflag=="3")
	{
		echo 'Sorry! The group has been deactivated by the Creator! ';
		if($socialobj->group_user_check_creator($gpid, $uid))
		{
			echo '<a style="cursor:pointer;" class="ajax-btn" data-mkey="gp_activate" data-gpid="'.$gpid.'" data-eltarget="-1">Activate Group</a>';
		}
		$themeobj->template_load_right();
		require MASTER_TEMPLATE.'/footer.php';
		$assetobj->load_js_product_login();
		return;
	}
	$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
	$gppic_medid=$gpres[0][changesqlquote(tbl_groups_info::col_gppic,"")];
	$gpdesc=$gpres[0][changesqlquote(tbl_groups_info::col_gpdesc,"")];
	$memtype=$gpres[0][changesqlquote(tbl_groups_info::col_gpmemtype,"")];
	$gpvis=$gpres[0][changesqlquote(tbl_groups_info::col_gpprivacy,"")];
	
	$gppic=$utilityobj->pathtourl($galobj->geturl_media("", $gppic_medid,"3"));
	if($gppic=="")
	{
		$picheight="0px";
	}
	else
	{
		$picheight="200px";
	}
?>

<div class="row">
	<div style="background-image: url('<?php echo $gppic;?>');height:<?php echo $picheight;?>;width:100%;background-repeat:no-repeat;background-position: center;">
				<!-- <div class="input-group margin-bottom-sm pull-left" style="width:250px;"> <input type="text" id="sbox_input" placeholder="Search this group" class="form-control"> <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span> </div>-->
				
				<div class="dropdown pull-right">
				    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><button class="btn btn-default" style="margin-top: 5px;margin-left:5px;"><i class="fa fa-cog"></i></button></a>
				    <ul class="dropdown-menu">
				      
				      <?php	
				      if($socialobj->group_user_check_admin($gpid,$userobj->uid))
				      {
				      	echo '<li><a title="" data-placement="bottom" data-original-title="Attach Documents" class="box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-afterupload="gp_cvrpic_set" data-galid="'.$galid_att.'">Set Cover Pic</a></li>';
				      	echo '<li><a title="" data-placement="bottom" data-original-title="Edit Group Settings" data-gpid="'.$gpid.'" class="box-tog" data-mkey="box_gp_edit_set" data-toggle="modal">Edit Group Settings</a></li>';
				      }
				      if($socialobj->group_user_check_creator($gpid, $userobj->uid))
				      {
				      	echo '<li><a title="" data-placement="bottom" data-original-title="Deactivate this Group" data-eltarget="-1" data-gpid="'.$gpid.'" class="ajax-btn" data-mkey="gp_deactivate" data-prompt="1" data-toggle="modal">Deactivate Group</a></li>';
				      }
				      ?>
				      
				      <li><a href="#">Add Group to List</a></li>
				    </ul>
				  </div>
				
				<button class="btn btn-default pull-right" style="margin-top: 5px;margin-left:5px;"><i class="fa fa-share-square-o"></i> Share</button>
				
				<?php 
				if(!$socialobj->group_user_check_creator($gpid, $uid))
				{
					if(!$socialobj->group_user_check($gpid,$uid))
					{
						if(!$socialobj->group_user_check_processing($gpid, $uid))
						{
							$gdisp='<i class="fa fa-sign-in"></i> Join Group';
						}
						else
						{
							$gdisp='<i class="fa fa-sign-in"></i> Cancel Request';
						}
					}
					else
					{
						$gdisp='<i class="fa fa-sign-out"></i> Leave Group';
					}
					
					echo '<button class="btn btn-primary pull-right ajax-btn" data-mkey="s_toggroup" data-gpid="'.$gpid.'" data-eltarget="-1" style="margin-top: 5px;"><span class="s_gpstat_'.$gpid.'">'.$gdisp.'</span></button>';
				}
				?>
				
				
				
				<div style="clear: both;"></div>
				
		</div>
		<?php	echo ' <h4><b><a href="#" id="ta_gp_edit_gpname">'.$gpname.'</a></b></h4>';?>
</div>

<div class="statusinput_cont">
    <br>
    <div class="row">
    
    <?php 
    if($socialobj->group_user_check($gpid,$uid))
    {
    ?>
    
    
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    	

	<input type="text" class="form-control status-subject" placeholder="Subject of your post">
    						<br>
    						<div class="widget-area no-padding blank">
								<div class="status-upload">
									<form>
										<!-- <textarea class="statusinput mentions" data-placeholder="What would you like to say?"></textarea>-->
										<div class="statusinput mentions" contenteditable="true" data-placeholder="What would you like to say?"></div>
										<ul>
											<?php echo '<li><a title="" data-placement="bottom" data-original-title="Attach Documents" class="box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-rld="2" data-galid="'.$galid_att.'"><i class="fa fa-paperclip"></i></a></li>';?>
											<!-- <li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Picture"><i class="fa fa-picture-o"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Link"><i class="fa fa-link"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Poll"><i class="fa fa-bar-chart"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Location"><i class="fa fa-map-marker"></i></a></li>-->
											<li><a title="Insert Smileys" data-placement="bottom" data-original-title="Add Smileys" class="box-tog" data-mkey="box_smileys" data-toggle="modal" data-eltarget="-1" data-intarget=".statusinput"><i class="fa fa-smile-o"></i></a></li>
										</ul>
										<div class="pull-right">
											<button type="button" class="btn btn-sm btn-primary status-ptag box-tog" data-mkey="box_ptag" data-reselem=".status-ptag" data-toggle="modal"><i class="fa fa-tags"></i> <span class="hidden-xs hidden-sm hidden-md">Tag</span></button>
											<button type="button" class="btn btn-sm btn-primary status-audienceset box-tog" data-mkey="box_audience" data-reselem=".status-audienceset" data-invoker="status-post" data-toggle="modal" data-eltarget="-2"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Set Audience</span></button>
											<button type="button" class="btn btn-sm btn-success status-post"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md">Post</span></button>
										</div>
										<div style="clear:both;"></div>
									</form>
								</div><!-- Status Upload  -->
						</div><!-- Widget Area -->
						<div style="clear:both;"></div>


	</div>
	
	<?php }?>
	
     
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-info-circle"></i> About this Group</b>
         	<?php echo '<span class="pull-right"><a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_viewgpadmins" data-gpid="'.$gpid.'">View Admins</a></span>';?>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
				  
				  <?php
				  if($socialobj->group_user_check_admin($gpid,$userobj->uid))
				  {
				  	echo '<a href="#" id="ta_gp_edit_desc">';
				  }
				  echo $gpdesc;
				  if($socialobj->group_user_check_admin($gpid,$userobj->uid))
				  {
				  	echo '</a>';
				  }
				  ?>
				  <!-- <br><br>
				  <u><b>TAGS:</b></u>
				  <br> 
				  <span class="label label-success">Technology</span> <span class="label label-info">Computers & Internet</span> <span class="label label-danger">Programming</span>-->
				  <br><br>
				  
				  
				  <em><a href="#" id="ta_gp_edit_vis">
			        	<?php 
			        		if($gpvis=="1"){echo "Public Group";}else if($gpvis=="2"){echo "Secret Group";}else if($gpvis=="3"){echo "Closed Group";}
			        	?>
			       </a></em>
         	</div>
         </div>
         
    </div>
     
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-users"></i> Members (<?php echo $socialobj->group_get_nomem($gpid);?>)</b>
         	<?php echo '<span class="pull-right"><a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_viewgpmem" data-gpid="'.$gpid.'" data-slimit="0" data-tot="20">View All</a></span>';?>
         	
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
         		
         		<?php 
         			$gpmemres=$socialobj->group_get_mem($gpid,"0","5");
         			for($i=0;$i<count($gpmemres);$i++)
         			{
         				$memuid=$gpmemres[$i][changesqlquote(tbl_members_attached::col_uid,"")];
         				$mempic=$utilityobj->pathtourl($muobj->getprofpic($memuid));
         				echo '<a href="/users.php?uid='.$memuid.'"><img src="'.$mempic.'" width="32" height="32"></a> ';
         			}
         		?>
				  
				  <br>
				  <?php 
				  if($socialobj->group_user_check($gpid,$uid))
				  {
				  	echo '<button class="btn btn-primary box-tog" data-toggle="modal" data-mkey="box_gp_inviteppl" data-gpid="'.$gpid.'" style="margin-top: 5px;"><i class="fa fa-user-plus"></i> Invite People</button>';
				  }
				  ?>
         	</div>
         </div>
         
         <?php 
         if($socialobj->group_user_check_admin($gpid,$userobj->uid))
         {
         	?>
         	<div class="panel panel-default">
         	<div class="panel-heading"><b><i class="fa fa-ban"></i> Blocked Users (<?php echo $socialobj->group_get_blockedusers_no($gpid);?>)</b></div>
         	<div class="panel-body">
         	<?php echo '<a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_viewgpblkusers" data-gpid="'.$gpid.'" data-slimit="0" data-tot="20">View All</a>';?>
         	</div>
         	</div>
         	<?php 
         }
         
         if(($memtype=="3"&&$socialobj->group_user_check($gpid, $userobj->uid))||($memtype=="2"&&$socialobj->group_user_check_admin($gpid, $userobj->uid)))
         {
         ?>
         <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-users"></i> Requests (<?php echo $socialobj->group_get_no_requests($gpid);?>)</b>
         	<?php echo '<span class="pull-right"><a style="cursor:pointer;" class="box-tog" data-toggle="modal" data-mkey="box_viewgpreq" data-gpid="'.$gpid.'" data-slimit="0" data-tot="20">View All</a></span>';?>
         	</div>
         	<div class="panel-body">
         	<ul class="list-group">
         		<?php 
         			
	         			$memreq=$socialobj->group_get_requests($gpid,"0","3");
	         			for($i=0;$i<count($memreq);$i++)
	         			{
	         				$memuid=$memreq[$i][changesqlquote(tbl_members_attached::col_uid,"")];
	         				$mempic=$muobj->getprofpic($memuid);
	         				$memname=$muobj->user_getfullname($memuid);
	         				echo '<li class="list-group-item gpreq_mem_'.$memuid.'"><img src="'.$mempic.'" width="32" height="32"> <a href="/users.php?uid='.$memuid.'">'.$memname.'</a> 
								<div class="btn-group pull-right">
								<button class="btn btn-primary btn-sm ajax-btn" data-mkey="gpbx_apreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-suchide=".gpreq_mem_'.$memuid.'"><i class="fa fa-check"></i></button>
							';
							
					       if($socialobj->group_user_check_admin($gpid,$userobj->uid))
					       {
								echo '<button class="btn btn-default btn-sm ajax-btn" data-mkey="gpbx_blockreq" data-gpid="'.$gpid.'" data-memuid="'.$memuid.'" data-suchide=".gpreq_mem_'.$memuid.'" data-prompt="1"><i class="fa fa-ban"></i></button>';
					       }
								
								echo '
							</div><div style="clear:both;"></div></li>';
	         			}
         		?>
         	 </ul>
         	</div>
         </div>
         <?php 
         	}
         ?>
         
    </div>
        
    </div>
        	
</div>



<div class="ta-feedcontainer">

<div class="panel panel-default">
           <!-- <div class="panel-heading">Feeds</div>-->
   			<div class="panel-body">

                <ul class="nav nav-tabs">
                  <li class="active" title="Posts made in this group"><a href="#A" data-toggle="tab"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Posts</span></a></li>
                  <!--<li title="Events created by this Group"><a href="#B" data-toggle="tab"><i class="fa fa-calendar"></i> <span class="hidden-xs hidden-sm hidden-md">Events</span></a></li>
                  <li title="Files uploaded to this Group"><a href="#C" data-toggle="tab"><i class="fa fa-file"></i> <span class="hidden-xs hidden-sm hidden-md">Gallery</span></a></li>
                  <li title="Posts Featured by the Administrators"><a href="#D" data-toggle="tab"><i class="fa fa-thumb-tack"></i> <span class="hidden-xs hidden-sm hidden-md">Featured</span></a></li>
                  <li title="Posts Classified by you in this Group"><a href="#E" data-toggle="tab"><i class="fa fa-list"></i> <span class="hidden-xs hidden-sm hidden-md">Your Lists</span></a></li>-->
                </ul>
                <div class="tabbable">
                  <div class="tab-content">
                    <div class="tab-pane active" id="A">
						<?php require_once 'groups_posts.php';?>                      	
                    </div>
                   <!--  <div class="tab-pane" id="B">
                      <div class="well well-sm">Howdy, I'm in Section B.</div>
                    </div>
                    <div class="tab-pane" id="C">
                      <div class="well well-sm">I've decided that I like wells.</div>
                    </div>
                    <div class="tab-pane" id="D">
                      <div class="well well-sm">I've decided that I like wells.</div>
                    </div>
                    <div class="tab-pane" id="E">
                      <div class="well well-sm">I've decided that I like wells.</div>
                    </div>-->
                  </div>
                </div> <!-- /tabbable -->
              
            </div>
</div> 



  
  <hr>
  
  </div>
  
<?php
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
window.mediaidarr=[];
function sender_editable(params)
{
	console.log(params);
	params.mkey=params.name;
    var d = new $.Deferred;
        $.ajax({
	      method:"POST",
		  url: "/request_process.php",
		  data:params,
		  dataType:"json",
		}).done(function(data) {
			if(data.returnval!=1)
			{
				return d.reject(data.message);
			}
			console.log(data);
			d.resolve();
		});
        return d.promise();
}

function statusbx_init_temp()
{
	process_tarea($('.statusinput,.status-c-input'));

	var utilityobj=new JS_UTILITY();
	var loadobj=new JS_LOADER();

	 $(".tbar_items").removeClass("active");
	 $(".tbar_i_feeds").addClass("active");

	loadobj.jsload_mentions(function(){
		$('.statusinput').mentionsInput({source: "/item_getter.php?key=tagfriend",showAtCaret: true});
	});
	 
	/*utilityobj.mentions($('.statusinput'), {source: "/item_getter.php?key=tagfriend"});*/

	 
	listenevent_future(".status-post",$("body"),"click", function(){
		var mentions=$('.statusinput').mentionsInput('getMentions');		
		
		var statussubject=$(".status-subject").val();
		if(statussubject=="")
		{
			var statussubject=prompt("The subject field is empty! Are you sure you want to continue posting?")
			if(!statussubject){return;}
		}
		
		var mystatus=$(".statusinput").html();
		var audid=$(".status-audienceset").attr("data-audid");
		var tagcolid=$(".status-ptag").attr("data-tagcol");
		var attvar=JSON.stringify(window.mediaidarr);
		var mentions=JSON.stringify(mentions);
		loadobj.ajax_call({
			url:"/request_process.php",
			  method:"POST",
			  data:{mkey:"tbx_newpost",thepost:mystatus,audid:audid,subject:statussubject,attachments:attvar,tagcolid:tagcolid,mentions:mentions,gpid:"<?php echo $gpid;?>"},
			  cache:false,
			  success:function(data){
				  $(".alert-text").html("The post has been made successfully");
				  $(".alert-dismissable").css("display","block");
				  $(".statusinput").html("");
				  $(".status-subject").val("");
				  $(".mentions-input .highlighter").remove();
				  $(".statusinput").css("background-color","white");
			  }
		});
	});

	listenevent_future(".post-combtn",$("body"),"click", function(){
		var tid=$(this).attr("data-threadid");
		var repto=$(this).attr("data-replyto");
		var mycomment=$(".status-c-input[data-threadid="+tid+"]").html();
		var attvar=JSON.stringify(window.mediaidarr);
		loadobj.ajax_call({
			url:"/request_process.php",
			  method:"POST",
			  data:{mkey:"tbx_newcomment",thecomment:mycomment,attachments:attvar,tid:tid,repto:repto},
			  cache:false,
			  success:function(data){
				  $(".status-c-input[data-threadid="+tid+"]").html("");
			  }
		});
	});

	listenevent_future(".reply-comment",$("body"),"click", function(){		
		var tid=$(this).attr("data-threadid");
		var msgid=$(this).attr("data-msgid");
		var repfname=$(this).attr("data-replyto");
		$(".status-c-input[data-threadid="+tid+"]").html("@"+repfname+": &nbsp;");
		scrolltodiv($(".status-c-input[data-threadid="+tid+"]:first"));

		$(".post-combtn[data-threadid="+tid+"]").attr("data-replyto",msgid);
		$(".status-c-input[data-threadid="+tid+"]").focus();
		elem = $(".status-c-input[data-threadid="+tid+"]").get(0);
		setEndOfContenteditable(elem);
	});	

	loadobj.jsload_isonscreen(function(){
		$(window).scroll(function(){
			$(".ta-fdbx").each(function(){
				var tid=$(this).attr("data-threadid");
				var msgid=$(this).attr("data-msgid");
				if ($(".ta-fdbx-body[data-threadid="+tid+"]").isOnScreen())
				{
	    			loadobj.ajax_call({
	    				url:"/request_process.php",
	    				  method:"POST",
	    				  data:{mkey:"tbx_postview",tid:tid,msgid:msgid},
	    				  cache:false,
	    				  success:function(data){
	    					  
	    				  }
	    			});
	            }
			});        		 
		});
	});

	loadobj.jsload_emoticons(function(){
		$('.ta-fdbx').emoticonize();
	});

    <?php 
    if($socialobj->group_user_check_admin($gpid,$userobj->uid))
    {
     ?>
	
	utilityobj.editable($("#ta_gp_edit_vis"),{
		value: '<?php echo $gpvis;?>',
        source: [
              {value: '1', text: 'Public Group'},
              {value: '2', text: 'Secret Group'},
              {value: '3', text: 'Closed Group'}
           ],
		type: 'select',
		pk: '<?php echo $gpid;?>',
	    url:sender_editable,
	    title: 'Select your Group Visibility',
	    mode:'inline',
	    name:'gp_edit_vis'
	});

	utilityobj.editable($("#ta_gp_edit_desc"),{
		type: 'textarea',
		pk: '<?php echo $gpid;?>',
	    url:sender_editable,
	    title: 'Enter your group description',
	    mode:'inline',
	    rows:3,
	    name:'gp_edit_desc'
	});

	utilityobj.editable($("#ta_gp_edit_gpname"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $gpid;?>',
		type: 'text',
	    title: 'Enter Group Name',
	    name:'gp_edit_gpname'
	});
	

	<?php 
	}
	?>
	
	rebind_all();
}

function gp_cvrpic_set()
{
	var attvar=JSON.stringify(window.mediaidarr);
	var loadobj=new JS_LOADER();

	loadobj.ajax_call({
		url:"/request_process.php",
		  method:"POST",
		  data:{mkey:"gp_cvrpic_set",attachments:attvar,gpid:"<?php echo $gpid;?>"},
		  cache:false,
		  success:function(data){
			  $(".alert-text").html("The cover pic has been set successfully");
			  $(".alert-dismissable").css("display","block");
			  $(".statusinput").html("");
			  $(".status-subject").val("");
			  $(".mentions-input .highlighter").remove();
			  $(".statusinput").css("background-color","white");
			  window.location.reload();
		  }
	});
}

document.addEventListener("DOMContentLoaded",statusbx_init_temp);
</script>