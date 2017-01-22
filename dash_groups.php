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
	setcookie("returnpath",HOST_SERVER."/dash_groups.php",0,'/');
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

<style type="text/css">
.gp-new {float:right!important;}
</style>

<div id="template_content_body">
<h3>Groups</h3>
<div id="ta-gpbox" class="jquitabs">
	<ul>
	  	<li><a href="#gp-tabs-yourgroups" title="Groups in which you are a member"><span class="hidden-xs hidden-sm hidden-md">Your Groups</span></a></li>
	  	<li><a href="#gp-tabs-gpadmin" title="Groups in which you are an Admin"><span class="hidden-xs hidden-sm hidden-md">Groups you Manage</span></a></li>
	  	<!-- <li><a href="#gp-tabs-recgp" title="Groups Recommended for you based on your interest"><span class="hidden-xs hidden-sm hidden-md">Recommended</span></a></li>-->
	    <li><a href="#gp-tabs-ftgp" title="Featured Groups"><span class="hidden-xs hidden-sm hidden-md">Featured</span></a></li>
	    <li class="gp-new"><a href="#gp-tabs-newgp" title="Create a New Group"><i class="fa fa-plus"></i> <span class="hidden-xs hidden-sm hidden-md">Create a Group</span></a></li>
	</ul>			
	
	<div id="gp-tabs-yourgroups" data-taburl="groups_yours.php">
	<?php require_once 'groups_yours.php';?>
	</div>
	
	<div id="gp-tabs-gpadmin" data-taburl="groups_manage.php">Loading content.. Please Wait..</div>
	<!-- <div id="gp-tabs-recgp" data-taburl="groups_yours.php">Loading content.. Please Wait..</div>-->
	<div id="gp-tabs-ftgp" data-taburl="groups_featured.php">Loading content.. Please Wait..</div>
	<div id="gp-tabs-newgp" data-taburl="groups_new.php">Loading content.. Please Wait..</div>

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
		
		var tabid="#ta-gpbox";
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
}

document.addEventListener("DOMContentLoaded",evtbx_init_datetime);
</script>