<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/social_feeds.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
$galid_att=$galobj->get_galid_special($userobj->uid,"16");
?>

<style type="text/css">
.ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  float: left;
  display: none;
  min-width: 160px;
  _width: 160px;
  padding: 4px 0;
  margin: 2px 0 0 0;
  list-style: none;
  background-color: #ffffff;
  border-color: #ccc;
  border-color: rgba(0, 0, 0, 0.2);
  border-style: solid;
  border-width: 1px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding;
  background-clip: padding-box;
  *border-right-width: 2px;
  *border-bottom-width: 2px;

  .ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;

    &.ui-state-hover, &.ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
    }
  }
}
</style>

<div id="template_content_body">

<div class="row">
	<div class="col-md-12">
	          <div class="alert alert-info alert-dismissable" style="display:none;">
	              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
	              <span class="alert-text"></span>
	          </div>
	</div>
</div>

<div class="statusinput_cont">
    <br>
    <div class="row">
    
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
											<button type="button" class="btn btn-sm btn-primary status-audienceset box-tog" data-mkey="box_audience" data-reselem=".status-audienceset" data-invoker="status-post" data-toggle="modal"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Set Audience</span></button>
											<button type="button" class="btn btn-sm btn-success status-post"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md">Post</span></button>
										</div>
										<div style="clear:both;"></div>
									</form>
								</div><!-- Status Upload  -->
						</div><!-- Widget Area -->
						<div style="clear:both;"></div>
						
						<br>
						<!-- <div class="panel panel-default">
							<div class="panel-body">
								<div class="btn-group">
									<button type="button" class="btn btn-default" title="Click to manage and filter feeds according to your liking"><i class="fa fa-rss"></i> <span class="hidden-xs hidden-sm hidden-md">Manage feeds</span></button>
									<button type="button" class="btn btn-default" title="Click to filter feeds by labels"><i class="fa fa-tags"></i> <span class="hidden-xs hidden-sm hidden-md">Labels</span></button>
									<button type="button" class="btn btn-default" title="Click to manage Widgets"><i class="fa fa-briefcase"></i> <span class="hidden-xs hidden-sm hidden-md"> Widgets</span></button>
									<button type="button" class="btn btn-default" title="Click to post advertisement(s) to reach out to people"><i class="fa fa-bullhorn"></i> <span class="hidden-xs hidden-sm hidden-md"> Post ADS</span></button>
								</div>
							</div>
						</div>-->
	</div>
     
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <!--  <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-pencil"></i> Complete your Profile</b>
         	<span class="pull-right">12/35</span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
				  Name of the city in which you were born:
				  <div class="input-group margin-bottom-sm"> 
					  <input type="text" placeholder="Enter your response here" class="form-control"> 
					  <span class="input-group-addon"><i class="fa fa-paper-plane"></i></span> 
				  </div>
				  
         	</div>
         </div>-->
         
         
         <span class="hidden-xs hidden-sm hidden-md">
         <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-pencil"></i> Quick Links</b>
         	<span class="pull-right"></span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
         	
         		<ul class="list-group">
         			<li class="list-group-item"><a href="/dash_feedback.php">Give Feedback</a></li>
         			<li class="list-group-item"><a href="/dash_import.php">Import Contacts</a></li>
         			<li class="list-group-item"><a href="/ads.php">Post a TAD</a></li>
         			<li class="list-group-item"><a href="/logs.php">View Logs</a></li>         			
         		</ul>
         		
         	</div>
         </div>
         </span>
         
         
         
    </div>
    
    <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-pencil"></i> Add a Life Event</b>
         	<span class="pull-right">9/10/2015 @ 6:00 PM</span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
				  What is special about your life today?
				  <div class="input-group margin-bottom-sm"> 
					  <input type="text" placeholder="Enter your response here" class="form-control"> 
					  <span class="input-group-addon"><i class="fa fa-paper-plane"></i></span> 
				  </div>
         	</div>
         </div>
     
    <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-pencil"></i> Send an SMS</b>
         	<span class="pull-right">0/35</span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
         		<label>Contact Number:</label>
         		<input type="text" class="form-control" placeholder="Enter Contact Number">
         		<label>Message</label>
				<textarea class="form-control" rows="3" placeholder="Please Enter your Message Here"></textarea>
				<br>
				<button type="button" class="btn btn-primary pull-right"><i class="fa fa-paper-plane"></i> Send</button>
				<div style="clear:both;"></div>
				  
         	</div>
      </div>
         
    </div>-->
        
    </div>
        	
</div>



<div class="ta-feedcontainer">

<div class="panel panel-default">
           <div class="panel-heading">Feeds</div>
   			<div class="panel-body ">

                <ul class="nav nav-tabs">
                  <li class="active" title="Feeds from your network"><a href="#feed_rec" data-toggle="tab"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Your Network</span></a></li>
                  <li title="Posts you made so far"><a href="#feed_wall" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md">Your Posts</span></a></li>
                  <li title="Feeds from Groups you belong to"><a href="#feed_groups" data-toggle="tab"><i class="fa fa-globe"></i> <span class="hidden-xs hidden-sm hidden-md">Groups</span></a></li>
                  <!-- <li title="Feeds from your interests"><a href="#B" data-toggle="tab"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md">Your Interests</span></a></li>
                  <li title="Latest News"><a href="#D" data-toggle="tab"><i class="fa fa-newspaper-o"></i> <span class="hidden-xs hidden-sm hidden-md">News</span></a></li>
                  <li title="Trending Now"><a href="#E" data-toggle="tab"><i class="fa fa-line-chart"></i> <span class="hidden-xs hidden-sm hidden-md">Trending</span></a></li>-->
                  <li title="View and Organize the feeds the way you like"><a href="#feed_custom" data-toggle="tab"><i class="fa fa-list"></i> <span class="hidden-xs hidden-sm hidden-md">Custom Feeds</span></a></li>
                  <li title="Featured News & Updates"><a href="#feed_featured" data-toggle="tab"><i class="fa fa-certificate"></i> <span class="hidden-xs hidden-sm hidden-md">Featured</span></a></li>
                </ul>
                <div class="tabbable">
                  <div class="tab-content">
                    <div class="tab-pane active" id="feed_rec" data-taburl="feeds_recommendations.php">
						<?php require_once 'feeds_recommendations.php';?>                      	
                    </div>
                    <div class="tab-pane" id="feed_wall" data-taburl="feeds_wall.php">
                    </div>
                    <div class="tab-pane" id="feed_custom" data-taburl="feeds_custom.php">
                    </div>
                    <div class="tab-pane" id="feed_groups" data-taburl="feeds_groups.php">
                    </div>
                    <div class="tab-pane" id="feed_featured" data-taburl="feeds_featured.php">
                    </div>
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
			  data:{mkey:"tbx_newpost",thepost:mystatus,audid:audid,subject:statussubject,attachments:attvar,tagcolid:tagcolid,mentions:mentions},
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
	
	rebind_all();
}

document.addEventListener("DOMContentLoaded",statusbx_init_temp);
</script>