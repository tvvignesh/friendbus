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
	setcookie("returnpath",HOST_SERVER."/social_apps.php",0,'/');
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

<div id="apps_social" class="jquitabs">
	<ul>
	  	<li><a href="#app-tabs-main"><span class="hidden-xs hidden-sm hidden-md">Causes</span></a></li>
	  	<li><a href="#app-tabs-about"><span class="hidden-xs hidden-sm hidden-md">About</span></a></li>
	  	<li><a href="#app-tabs-reviews"><span class="hidden-xs hidden-sm hidden-md">Posts & Reviews</span></a></li>
	  	<li><a href="#app-tabs-settings"><span class="hidden-xs hidden-sm hidden-md">Settings</span></a></li>
	    <li><a href="#app-tabs-screenshots"><span class="hidden-xs hidden-sm hidden-md">Screenshots</span></a></li>
	    <li><a href="#app-tabs-help"><span class="hidden-xs hidden-sm hidden-md">Help & FAQ</span></a></li>
	    <li><a href="#app-tabs-changelog"><span class="hidden-xs hidden-sm hidden-md">Changelog</span></a></li>
	</ul>			
	
	<div id="app-tabs-main" data-taburl="app_main.php">
	<?php require_once 'app_main.php';?>
	</div>
	
	<div id="app-tabs-about" data-taburl="app_about.php">Loading content.. Please Wait..</div>
	<div id="app-tabs-reviews" data-taburl="app_reviews.php">Loading content.. Please Wait..</div>
	<div id="app-tabs-settings" data-taburl="app_settings.php">Loading content.. Please Wait..</div>
	<div id="app-tabs-screenshots" data-taburl="app_screenshots.php">Loading content.. Please Wait..</div>
	<div id="app-tabs-help" data-taburl="app_help.php">Loading content.. Please Wait..</div>
	<div id="app-tabs-changelog" data-taburl="app_changelog.php">Loading content.. Please Wait..</div>

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
		
		var tabid="#apps_social";
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

		listenevent_future(".galbox_viewpic_cont",$("#app-tabs-screenshots"),"click",galbox_showpicdet);

			/*var loadobj=new JS_LOADER();
			loadobj.hashmanager();
			monitorhash();
			window.hashstatus=1;*/
}

document.addEventListener("DOMContentLoaded",evtbx_init_datetime);
</script>