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
	setcookie("returnpath",HOST_SERVER."/invest.php",0,'/');
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
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Invest on Us 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					We would only like to invite investors who beleive in what we beleive and who is willing to trust us with their soul.
					<br><br>
					 Why? It is because even though we require investors to accelerate our business growth and bring it to great heights, we like to do it the way we feel is right, 
					 the way people would love it, the way our staff feels right.
					<br><br>
					We beleive in a steady growth for our business since we beleive that it has a bright future. So, we don't entertain investors who may hinder the way we work or change things the way it is not supposed to be.
					<br><br>
					<b>But how do we assure investors of return? Here is the way:</b>
					<br>
					1) One video per week will be made on what the progress are for the respective week, what we will do for the next week and what feedbacks we have to work on. 
					<br><br>
					This video will be made available for the public and not only to the investors. So, this can be used by the investors to judge the way we work, the progress we bring in, our successes and failures. 
					This data can be used by them to see how much profit they may make if invested on.
					<br><br>
					2) Recording all transactions with public visibility is a strategy we use to ensure a transparent system where we record each and every transaction we have had including profits, losses, investments, assets, etc.
					<br> 
					This data can be used by the investors to get a projection of the money they may make if they invest on us.
					<br><br>
					<b>Contact us if you are interested:</b> (We can have all the discussion face to face)
					<br><br>
					Phone: 044-22243697
					<br><br>
					E-Mail: tvvignesh@techahoy.in
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