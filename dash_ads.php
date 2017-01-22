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
	setcookie("returnpath",HOST_SERVER."/dash_ads.php",0,'/');
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
	<div class="panel-body">
		Creating or publishing ADS are as simple as given in the steps below. You can get your ADS to reach the right people at the right place at the right time. 
		<br>Things are completely customizable out here. Set your own budget, duration, layout, and more to cater your needs. 
	</div>
</div>

<h4>ADVERTISERS</h4>
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">1</span> Create an AD</div>
				<div class="panel-body">
					Create your AD content using the tools we provide you or even by yourself.
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">2</span> Optimize the AD to your needs</div>
				<div class="panel-body">
					Choose your budget, duration, size, layout, colors, type and more..
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">3</span> Choose your Audience</div>
				<div class="panel-body">
					Choose the right people to whom you want to deliver the ADS
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">4</span> Deliver the AD</div>
				<div class="panel-body">
					Get your AD to reach all the right people at the right time at the right place
				</div>
			</div>
		</div>
	</div>
	
	<a href="ads_advertiser.php"><button type="button" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Get Started as an Advertiser</button></a>
	
	<hr>

<h4>PUBLISHERS</h4>
	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">1</span> Explain your Website</div>
				<div class="panel-body">
					Let us know what your website is about, the theme and the kind of users you get throughout
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">2</span> Make the Choice</div>
				<div class="panel-body">
					Choose the preferred AD types you would like to show on your website from the list we provide.
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">3</span> Publish the AD</div>
				<div class="panel-body">
					Publish the AD on your website by pasting the code snippet we provide and make sure you follow all the Terms & Conditions.
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><span class="badge">4</span> Get Paid</div>
				<div class="panel-body">
					Get paid for every visit an user makes on your website and sees the AD
				</div>
			</div>
		</div>
	</div>
	
	<a href="ads_publisher.php"><button type="button" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Get Started as a Publisher</button></a>

</div>

 <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>

<script type="text/javascript">
function ads_init_temp()
{
	var utilityobj=new JS_UTILITY(); 

	setTimeout(function(){
			utilityobj.checkbox($('input[type="checkbox"]'), {
				checkedClass: 'glyphicon glyphicon-ok'
			});
		}, 300);
	
}

document.addEventListener("DOMContentLoaded",ads_init_temp);
</script>