<?php
require_once 'adjustment.php';
header("content-type:text/xml");
$noecho="yes";
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$userobj=new ta_userinfo();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "<document>";

if(!$userobj->checklogin())
{
	echo "<statusmsg>Not Logged In</statusmsg>";
	echo "<statuscode>5</statuscode>";
	echo "</document>";
	die('');
}

$uid=$userobj->getuid();

if((isset($_POST["status"])))
{
	$status=$_POST["status"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

if($status=="")
{
	echo "<statusmsg>Status is empty</statusmsg>";
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

$socialobj=new ta_socialoperations();
$res=$socialobj->readnotifications($uid,$status);

$dbobj=new ta_dboperations();

echo '<statuscode>1</statuscode>';
echo '<statusmsg>Successfully Retrieved Notifications</statusmsg>';

for($i=0;$i<count($res);$i++)
{
	$notify_icon=$dbobj->colval($res,tbl_notifications::col_notifyicon,$i);
	$notify_id=$dbobj->colval($res,tbl_notifications::col_notifyid,$i);
	$notify_link=$dbobj->colval($res,tbl_notifications::col_notifylink,$i);
	$notify_status=$dbobj->colval($res,tbl_notifications::col_notifystatus,$i);
	$notify_text=$dbobj->colval($res,tbl_notifications::col_notifytext,$i);
	$notify_time=$dbobj->colval($res,tbl_notifications::col_notifytime,$i);
	$notify_type=$dbobj->colval($res,tbl_notifications::col_notifytype,$i);
	$notify_uid=$dbobj->colval($res,tbl_notifications::col_uid,$i);

	echo '<notify_collection>';
		echo '<notify_id>'.$notify_id.'</notify_id>';
		echo '<notify_icon>'.$notify_icon.'</notify_icon>';
		echo '<notify_link>'.$notify_link.'</notify_link>';
		echo '<notify_status>'.$notify_status.'</notify_status>';
		echo '<notify_text>'.$notify_text.'</notify_text>';
		echo '<notify_time>'.$notify_time.'</notify_time>';
		echo '<notify_type>'.$notify_type.'</notify_type>';
		echo '<notify_uid>'.$notify_uid.'</notify_uid>';
	echo '</notify_collection>';
}

echo "</document>";

?>
