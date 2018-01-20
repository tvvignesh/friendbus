<?php
ob_start();
require_once 'header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$galobj=new ta_galleryoperations();
$socialobj=new ta_socialoperations();
$msgobj=new ta_messageoperations();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$uiobj=new ta_uifriend();
	setcookie("returnpath",HOST_SERVER."/privacy.php",0,'/');
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
?>

<div id="template_content_body">
	<div class="row">
		<!-- <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
			<ul class="list-group">
				<li class="list-group-item">Technology</li>
				<li class="list-group-item">Technology</li>
			</ul>		
		</div>-->
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Advertising 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					We have a unique advertising policy. We like to make sure that everyone is happy! So, we follow some special strategies.
					<br><br>
					
<pre style="white-space: pre-wrap;word-break: normal;">
<b>What you give is What you Get</b>
Say you sell a product or service. If you give some discount specially to users of Friendbus, you get the same percentage of discount in return when publishing your AD at Friendbus for the duration the offer is provided.
	
Example: Say, you sell shoes with 30% discount to all Friendbus users. You get the same 30% discount (of your AD cost) when you publish the AD.
	
This makes the user as well as yourself happy.
	
<b>Coupons</b>
The market has changed and not many people may be interested in seeing ADS. So, we have a new strategy again which makes everyone happy. 

The sellers will get options to provide coupons to all Friendbus users which may grant users any offer or gifts or anything. 
	
This will help in getting your brand noticed as well as making the buyer happy.
	
<b>Don't like it? Withdraw it</b>
This is a facility we provide every advertiser. If a person has opted for an AD campaign of 5 days and does not like the way the campaign progresses, he/she may withdraw it anytime and get the remaining amount (for the duration unutilized) as refund without any deductions.

<b>Special concessions for Governmental ADS, NGOs & Non-Profits</b>
We provide exclusive and special concessions for Governmental Organizations, NGOs, Non-profits after proper scrutiny. The percentage of concession varies depending upon the Organization, the nature of AD and the frequency of the AD. 
</pre>

<br><br>
<b>INTERESTED?</b>
<br>
You may contact us anytime by sending us a mail to <b>tvvignesh@techahoy.in</b> or calling up <b>044-22243697</b>

<br>(We are currently building the AD portal to make everything automated till which we can be contacted by using the above methods.)
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>