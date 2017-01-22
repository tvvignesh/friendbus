<?php
require_once 'adjustment.php';
header("content-type:text/xml");
$noecho="yes";
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';
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

if((isset($_POST["rateid"]))&&(isset($_POST["conts"])))
{
	$rateid=$_POST["rateid"];
	$content=$_POST["conts"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

if($content=="")
{
	echo "<statusmsg>Comment is empty</statusmsg>";
	echo "<statuscode>4</statuscode>";
	echo "</document>";
	die('');
}


$userobj->userinit();
$audienceobj=new ta_audience();
$messageobj=new ta_messageoperations();

$tid=$messageobj->get_item_comthreadid($rateid);
$audienceid=$messageobj->get_thread_audienceid($tid);

if(!($audienceobj->audience_check_user($audienceid,$userobj->uid)))
{
	echo "<statusmsg>Access Denied</statusmsg>";
	echo "<statuscode>5</statuscode>";
	echo "</document>";
	die('');
}

$comid=$messageobj->sendcomment($userobj->uid, $content, $tid, $GLOBALS["appid"],"");
echo "<statusmsg>Comment Made Successfully</statusmsg>";
echo "<statuscode>1</statuscode>";

echo "</document>";

?>