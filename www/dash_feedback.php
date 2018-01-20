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
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
}

$assetobj=new ta_assetloader();
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();
?>

<div id="template_content_body">
<div class="panel panel-default">
	<div class="panel-heading">Feedback, Feature Requests & Bug Reports</div>
	<div class="panel-body">
		 <form id="ta-fdbkform">
		 	<b>Nature of Request:</b>
		 	<select class="form-control" name="fdbk_nature">
		 		<option value="1">Feedback</option>
		 		<option value="2">Feature Request</option>
		 		<option value="3">Bug Report</option>
		 	</select>
		 	<br>
		 	<b>URL (if any):</b>
		 	<input type="url" name="relurl" class="form-control" placeholder="Link related to this request if any">
		 	<br>
		 	<b>Your E-Mail Address:</b>
		 	<input type="email" name="fdbk_mail" class="form-control" value="<?php echo $userobj->email;?>">
		 	<br>
		 	<b>Your Request:</b>
		 	<textarea name="fdbk_request" class="form-control" rows="4" placeholder="Type in your request here."></textarea>
		 	<br>
		 	<button type="button" class="btn btn-primary ajax-btn" data-mkey="fdbk_submit" data-sform="#ta-fdbkform" data-eltarget=".fdbk-status" data-dtype="json" data-ddemand="json" >Submit</button>
		 </form>
		 <br><br>
		 <span class="fdbk-status"></span>
	</div>
	<div class="panel-footer">
	It may take a bit of time for us to respond. Hopefully you may get a response within 48 hours or even faster.
	</div>
</div>
</div>

 <?php 
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>