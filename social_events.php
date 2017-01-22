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
	setcookie("returnpath",HOST_SERVER."/social_events.php",0,'/');
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
  		<div class='col-xs-12 col-sm-6 col-md-4 col-lg-9'>
  			
  		</div>
        <div class='col-xs-12 col-sm-6 col-md-4 col-lg-3'>
        Go to Date: 
            <div class="form-group">
                <div class='input-group date' id='evt-eventdatetime'>
                    <input type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
    </div>
</div>
</div>


<div id="evtbox_social" class="jquitabs">
	    <ul>
	  	<li><a href="#se-tabs-yourevents" title="Events you are hosting"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md">Your Events</span></a></li>
	  	<li><a href="#se-tabs-going"><i class="fa fa-calendar-check-o"></i> <span class="hidden-xs hidden-sm hidden-md">Going</span></a></li>
	    <li><a href="#se-tabs-invited"><i class="fa fa-calendar-plus-o"></i> <span class="hidden-xs hidden-sm hidden-md">Invited</span></a></li>
	    <li><a href="#se-tabs-ignored"><i class="fa fa-calendar-times-o"></i> <span class="hidden-xs hidden-sm hidden-md">Ignored</span></a></li>
	    <li><a href="#se-tabs-attended"><i class="fa fa-calendar-o"></i> <span class="hidden-xs hidden-sm hidden-md">Attended</span></a></li>
	    <li><a href="#se-tabs-lists"><i class="fa fa-list"></i> <span class="hidden-xs hidden-sm hidden-md">Your Event Lists</span></a></li>
	    <li><a href="#se-tabs-evtnearby"><i class="fa fa-globe"></i> <span class="hidden-xs hidden-sm hidden-md">Events Nearby</span></a></li>
	    <li><a href="#se-tabs-evtfeatured"><i class="fa fa-star"></i> <span class="hidden-xs hidden-sm hidden-md">Featured Events</span></a></li>
	</ul>			
	
	<div id="se-tabs-yourevents" data-taburl="events_yours.php">
	<?php require_once 'events_yours.php';?>
	</div>
	
	<div id="se-tabs-going" data-taburl="events_going.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-invited" data-taburl="events_invited.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-ignored" data-taburl="events_ignored.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-attended" data-taburl="events_attended.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-lists" data-taburl="events_lists.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-evtnearby" data-taburl="events_nearby.php">Loading content.. Please Wait..</div>
	<div id="se-tabs-evtfeatured" data-taburl="events_featured.php">Loading content.. Please Wait..</div>

</div>

 </div>
 
</div>
  
  <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
function evtbx_init_datetime()
{
		var utilityobj=new JS_UTILITY();
		utilityobj.datetimepicker($("#evt-eventdatetime"),{});
		
		var tabid="#evtbox_social";
		$(tabid).tabs({
			create:function(event,ui){
				if(window.location.hash=="")
				{
					window.location.hash=$(tabid+" ul li a:first").attr('href');
				}
			},
			beforeLoad: function( event, ui ) {
		        ui.jqXHR.fail(function() {
		          ui.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible.");
		        });
		      },
		    hide: {
		        effect: "fade",
		        duration: 500
		    },
		    show:{
				effect: "fade",
		        duration: 500
			},
			activate: function(event, ui) 
		   { 
		      window.location.hash= ui.newTab.context.hash;
		   }
		});

			/*var loadobj=new JS_LOADER();
			loadobj.hashmanager();
			monitorhash();
			window.hashstatus=1;*/
}

document.addEventListener("DOMContentLoaded",evtbx_init_datetime);
</script>