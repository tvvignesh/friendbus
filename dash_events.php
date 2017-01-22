<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/dash_events.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">

<div class="row">
	<div style="background-image: url('https://scontent-sin1-1.xx.fbcdn.net/hphotos-xfa1/v/t1.0-9/538386_429935043725781_1231158587_n.jpg?oh=9c1ac23fb73fc5e3d9eb29c541972683&oe=565E736A');height:200px;width:100%;background-repeat:no-repeat;background-position: center;">
				<div class="input-group margin-bottom-sm pull-left" style="width:250px;"> <input type="text" id="sbox_input" placeholder="Search this page" class="form-control"> <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span> </div>
				
				<div class="pull-right">
					Are you attending?
				</div>
				
				<div style="clear: both;"></div>
				
				<div class="pull-right">
		            	<div class="dropdown pull-right comp-more-feed">
	                		<button class="btn btn-default" title="More" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
	                		<ul class="dropdown-menu">
	                		  <li><a href="#"><i class="fa fa-bullseye"></i> Promote Event</a></li>
	                		  <li><a href="#"><i class="fa fa-share"></i> Share Event</a></li>
	                		  <li><a href="#"><i class="fa fa-eye"></i> Manage Privacy</a></li>
						      <li><a href="#"><i class="fa fa-area-chart"></i> View Activity</a></li>
						      <li><a href="#"><i class="fa fa-link"></i> Get Link</a></li>
						      <li><a href="#"><i class="fa fa-pencil-square-o"></i> Suggest Edits</a></li>
						      <li><a href="#"><i class="fa fa-list"></i> Add to List</a></li>
						      <li><a href="#"><i class="fa fa-bell-o"></i> Unfollow Event</a></li>
						      <li><a href="#"><i class="fa fa-floppy-o"></i> Backup Event</a></li>
						      <li><a href="#"><i class="fa fa-flag"></i> Report Content</a></li>
						    </ul>
                		</div>
		            
		            
		            	<button class="btn btn-primary"><i class="fa fa-check"></i> Yes</button>
		            	<button class="btn btn-default"><i class="fa fa-times"></i> No</button>
		            	<button class="btn btn-default"><i class="fa fa-question"></i> Maybe</button>
		            </div>
		            
		        <div style="clear: both;"></div>    
		</div>
</div>

<div class="statusinput_cont">
    <br>
    <div class="row">
    
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    						<div class="widget-area no-padding blank">
								<div class="status-upload">
									<form>
										<div class="statusinput" contenteditable="true" data-placeholder="What would you like to say?"></div>
										<ul>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Attach Documents"><i class="fa fa-paperclip"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Picture"><i class="fa fa-picture-o"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Audio"><i class="fa fa-music"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Video"><i class="fa fa-video-camera"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Sound Record"><i class="fa fa-microphone"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Link"><i class="fa fa-link"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Poll"><i class="fa fa-bar-chart"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Location"><i class="fa fa-map-marker"></i></a></li>
											<li><a title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Add Smileys"><i class="fa fa-smile-o"></i></a></li>
										</ul>
										<button type="submit" class="btn btn-success green"><i class="fa fa-share"></i> Post</button>
									</form>
								</div><!-- Status Upload  -->
						</div><!-- Widget Area -->
						<div style="clear:both;"></div>
	</div>
     
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-info-circle"></i> About this Event</b>
         	<span class="pull-right"><a href="#">View Admins</a></span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
				  This event is a fundraiser meant to empower the children who are unable to get proper education.
				  <br><br>
				  <u><b>TAGS:</b></u>
				  <br> 
				  <span class="label label-success">Technology</span> <span class="label label-info">Computers & Internet</span> <span class="label label-danger">Programming</span>
				  <br><br>
				  <em><a href="#">Public Event</a></em>
				  <br><br>
				  <i class="fa fa-calendar"></i> <em>12th January 2015</em>
				  <br><br>
				  <i class="fa fa-map-marker"></i> <em>Express Avenue, Mount Road, Chennai</em>
				  <br><br>
				  <i class="fa fa-user"></i> <em>Host: <a href="#">T.V.Vignesh</a></em>
				  <br><br>
				  <i class="fa fa-link"></i> <em>Website: <a href="#">http://www.techahoy.in</a></em>
         	</div>
         </div>
         
    </div>
     
     <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
     
     <div class="panel panel-default">
         	<div class="panel-heading">
         	<b><i class="fa fa-users"></i> Guests</b>
         	<span class="pull-right"><a href="#">View All</a></span>
         	<div style="clear:both;"></div>
         	</div>
         	<div class="panel-body">
				  <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xta1/v/t1.0-1/p40x40/11707688_10205923174291873_7102410582550377035_n.jpg?oh=72ef4022c0d6882393d22a81c3af05da&oe=565F47A8&__gda__=1449409670_fdb569badc919787db372e4f4467ab4c" width="30" height="30">
				  <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xta1/v/t1.0-1/p40x40/11707688_10205923174291873_7102410582550377035_n.jpg?oh=72ef4022c0d6882393d22a81c3af05da&oe=565F47A8&__gda__=1449409670_fdb569badc919787db372e4f4467ab4c" width="30" height="30">
				  <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xta1/v/t1.0-1/p40x40/11707688_10205923174291873_7102410582550377035_n.jpg?oh=72ef4022c0d6882393d22a81c3af05da&oe=565F47A8&__gda__=1449409670_fdb569badc919787db372e4f4467ab4c" width="30" height="30">
				  <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xta1/v/t1.0-1/p40x40/11707688_10205923174291873_7102410582550377035_n.jpg?oh=72ef4022c0d6882393d22a81c3af05da&oe=565F47A8&__gda__=1449409670_fdb569badc919787db372e4f4467ab4c" width="30" height="30">
				  <img src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xta1/v/t1.0-1/p40x40/11707688_10205923174291873_7102410582550377035_n.jpg?oh=72ef4022c0d6882393d22a81c3af05da&oe=565F47A8&__gda__=1449409670_fdb569badc919787db372e4f4467ab4c" width="30" height="30">
				  
				  <br><br>
				  
				  <ul class="list-group">
				  	<li class="list-group-item"><b>Invited:</b> <span class="pull-right">25000</span></li>
				  	<li class="list-group-item"><b>Attending:</b> <span class="pull-right">2500</span></li>
				  	<li class="list-group-item"><b>Maybe:</b> <span class="pull-right">1000</span></li>
				  	<li class="list-group-item"><b>Not Attending:</b> <span class="pull-right">15000</span></li>
				  </ul>
				  <button class="btn btn-primary" style="margin-top: 5px;"><i class="fa fa-user-plus"></i> Invite People</button>
         	</div>
         </div>
         
    </div>
        
    </div>
        	
</div>



<div class="ta-feedcontainer">

<div class="panel panel-default">
           <!-- <div class="panel-heading">Feeds</div>-->
   			<div class="panel-body">

                <ul class="nav nav-tabs nav-tabs-ignorehash">
                  <li class="active" title="Posts made in this group"><a href="#A" data-toggle="tab"><i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Posts</span></a></li>
                  <li title="Files uploaded to this Page"><a href="#B" data-toggle="tab"><i class="fa fa-file"></i> <span class="hidden-xs hidden-sm hidden-md">Gallery</span></a></li>
                  <li title="Posts Featured by the Administrators"><a href="#C" data-toggle="tab"><i class="fa fa-thumb-tack"></i> <span class="hidden-xs hidden-sm hidden-md">Featured</span></a></li>
                  <li title="Posts Classified by you in this Group"><a href="#D" data-toggle="tab"><i class="fa fa-list"></i> <span class="hidden-xs hidden-sm hidden-md">Your Lists</span></a></li>
                </ul>
                <div class="tabbable">
                  <div class="tab-content">
                    <div class="tab-pane active" id="A">
						<?php require_once 'groups_posts.php';?>                      	
                    </div>
                    <div class="tab-pane" id="B">
                      <div class="well well-sm">Howdy, I'm in Section B.</div>
                    </div>
                    <div class="tab-pane" id="C">
                      <div class="well well-sm">I've decided that I like wells.</div>
                    </div>
                    <div class="tab-pane" id="D">
                      <div class="well well-sm">I've decided that I like wells.</div>
                    </div>
                  </div>
                </div> <!-- /tabbable -->
              
                <div class="col-sm-12 text-center">
                  <ul class="pagination center-block" style="display:inline-block;">
                    <li><a href="#">&lt;&lt;</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">&gt;&gt;</a></li>
                  </ul>
                </div>
              
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
function statusbx_init_temp()
{
	process_tarea($('.statusinput'));
}

document.addEventListener("DOMContentLoaded",statusbx_init_temp);
</script>