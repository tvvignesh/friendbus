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
	setcookie("returnpath",HOST_SERVER."/dash_settings.php",0,'/');
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
	<div class="panel panel-default">
		<div class="panel-heading"><i class="fa fa-cog"></i> Settings</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3 col-md-4 col-sm-6">
					<ul class="list-group" id="ta-setting-list">
						<li class="list-group-item active"><i class="fa fa-user"></i> <a href="#ta-setting-account" class="link-white">Account &amp; Contact Info</a></li>
						<!-- <li class="list-group-item"><i class="fa fa-eye"></i> <a href="#ta-setting-privacy" class="link-white">Privacy</a></li>
						<li class="list-group-item"><i class="fa fa-bell"></i>  <a href="#ta-setting-notifications" class="link-white">Notifications</a></li>
						<li class="list-group-item"><i class="fa fa-users"></i>  <a href="#ta-setting-contacts" class="link-white">Contacts</a></li>
						<li class="list-group-item"><i class="fa fa-language"></i>  <a href="#ta-setting-language" class="link-white">Language</a></li>
						<li class="list-group-item"><i class="fa fa-tablet"></i> <a href="#ta-setting-devicesync" class="link-white">Devices & Sync</a></li>
						<li class="list-group-item"><i class="fa fa-map-marker"></i> <a href="#ta-setting-location" class="link-white">Location</a></li>
						<li class="list-group-item"><i class="fa fa-file"></i> <a href="#ta-setting-gallery" class="link-white">Gallery</a></li>
						<li class="list-group-item"><i class="fa fa-ban"></i> <a href="#ta-setting-blacklists" class="link-white">Blacklists & Blocks</a></li>
						<li class="list-group-item"><i class="fa fa-briefcase"></i> <a href="#ta-setting-apps" class="link-white">Apps & Widgets</a></li>
						<li class="list-group-item"><i class="fa fa-hdd-o"></i> <a href="#ta-setting-storage" class="link-white">Storage</a></li>
						<li class="list-group-item"><i class="fa fa-bullhorn"></i> <a href="#ta-setting-ads" class="link-white">Advertisements</a></li>
						<li class="list-group-item"><i class="fa fa-money"></i> <a href="#ta-setting-payment" class="link-white">Payment</a></li>-->
					</ul>
				</div>
				
				<div class="col-lg-9 col-md-8 col-sm-6">
					
					<div id="ta-setting-account" class="ta-ajaxdiv" data-taburl="settings_account.php"></div>
					<!--<div id="ta-setting-privacy" class="ta-ajaxdiv" data-taburl="settings_privacy.php"></div>
					<div id="ta-setting-notifications" class="ta-ajaxdiv" data-taburl="settings_notifications.php"></div>
					<div id="ta-setting-language" class="ta-ajaxdiv" data-taburl="settings_language.php"></div>
					<div id="ta-setting-contacts" class="ta-ajaxdiv" data-taburl="settings_contacts.php"></div>
					<div id="ta-setting-devicesync" class="ta-ajaxdiv" data-taburl="settings_devicesync.php"></div>
					<div id="ta-setting-location" class="ta-ajaxdiv" data-taburl="settings_location.php"></div>
					<div id="ta-setting-gallery" class="ta-ajaxdiv" data-taburl="settings_gallery.php"></div>
					<div id="ta-setting-blacklists" class="ta-ajaxdiv" data-taburl="settings_blacklists.php"></div>
					<div id="ta-setting-apps" class="ta-ajaxdiv" data-taburl="settings_apps.php"></div>
					<div id="ta-setting-storage" class="ta-ajaxdiv" data-taburl="settings_storage.php"></div>
					<div id="ta-setting-ads" class="ta-ajaxdiv" data-taburl="settings_ads.php"></div>
					<div id="ta-setting-payment" class="ta-ajaxdiv" data-taburl="settings_payment.php"></div>-->
					
				</div>
			</div>
		</div>
		<div class="panel-footer">
			This section is under construction
		</div>
	</div>
</div>
  
  <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
function settingtbl_init_temp()
{
	if(window.location.hash=="")
	{
		window.location.hash="ta-setting-account";
	}
	else
	{
		hashval=window.location.hash;
		window.location.hash="";
		window.location.hash=hashval;
		myhash=processhash(hashval);
		myhash="#"+myhash;
		$("#ta-setting-list li").removeClass("active");
		var myelem=$("#ta-setting-list a[href='"+myhash+"']").parent("li");
		myelem.addClass("active");
	    $(".ta-ajaxdiv").css("display","none");
	    $(myhash).css("display","block");
	}

	listenevent($("#ta-setting-list li"),"click",function(){
		$("#ta-setting-list li").removeClass("active");
	    $(this).addClass("active");
	    var targetdiv=$(this).children("a:first").attr("href");
	    $(".ta-ajaxdiv").css("display","none");
	    $(targetdiv).css("display","block");
	});
}

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

document.addEventListener("DOMContentLoaded",settingtbl_init_temp);
</script>