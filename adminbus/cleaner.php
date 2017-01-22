<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/header.php';

$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$dbobj=new ta_dboperations();
$galobj=new ta_galleryoperations();
$assetobj=new ta_assetloader();
$logobj=new ta_logs();

$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();

if(isset($_POST["a_delall"]))
{	
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname, tbl_user_info::dbname);
	for($i=0;$i<count($res);$i++)
	{
		$uid=$res[$i][changesqlquote(tbl_user_info::col_usrid,"")];
		$logobj->store_templogs("Usr:".$uid);
		$userobj->user_account_delete_process($uid);
	}
}
else
if(isset($_POST["a_deluser"]))
{
	if(isset($_POST["usrid"]))
	{
		$uid=$_POST["usrid"];
		$userobj->user_account_delete_process($uid);
	}
	else
	{
		echo "Please enter User ID";
	}
}
?>

<div id="template_content_body">
<form action="cleaner.php" method="post">
	<input type="text" class="form-control" name="usrid">
	<input type="submit" name="a_delall" value="Delete all Users" class="btn btn-default">
	<input type="submit" name="a_deluser" value="Delete Specific User" class="btn btn-default">
</form>
</div>

<?php
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
?>