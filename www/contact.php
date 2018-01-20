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
	setcookie("returnpath",HOST_SERVER."/contact.php",0,'/');
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
					Contact Us 
					<div style="clear:both;"></div>
				</div>
				<div class="panel-body">
					
					<form id="ta-cntctform">
					 	<b>URL (if any):</b>
					 	<input type="url" name="relurl" class="form-control" placeholder="Link related to this request if any">
					 	<br>
					 	<b>Your E-Mail Address:</b>
					 	<input type="email" name="cntct_mail" class="form-control" value="<?php echo $userobj->email;?>">
					 	<br>
					 	<b>Your Request:</b>
					 	<textarea name="cntct_request" class="form-control" rows="4" placeholder="Type in your request here."></textarea>
					 	<br>
					 	<button type="button" class="btn btn-primary ajax-btn" data-mkey="cntct_submit" data-sform="#ta-cntctform" data-eltarget=".cntct-status" data-dtype="json" data-ddemand="json" >Submit</button>
					 </form>
					<br><br>
					<span class="cntct-status"></span>
				
				<b>Other ways to get in touch with us:</b>
				<br><br>
					<b>Facebook:</b> <a href="https://www.facebook.com/techahoy">https://www.facebook.com/techahoy</a>
					<br><br>
					<b>Google Plus:</b> <a href="https://plus.google.com/+TechahoyIn">https://plus.google.com/+TechahoyIn</a>
					<br><br>
					<b>Twitter:</b> <a href="https://twitter.com/techahoy">https://twitter.com/techahoy</a>
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