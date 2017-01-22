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
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

if(isset($_POST["notid"]))
{
	$notid=$_POST["notid"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

if($notid=="")
{
	echo "<statusmsg>Notification ID is empty</statusmsg>";
	echo "<statuscode>4</statuscode>";
	echo "</document>";
	die('');
}

$uid=$userobj->getuid();

$socialobj=new ta_socialoperations();

$socialobj->marknotification($notid,"1");

echo '<statuscode>1</statuscode>';
echo '<statusmsg>Successfully Marked Notification as Read</statusmsg>';
echo "</document>";
?>
