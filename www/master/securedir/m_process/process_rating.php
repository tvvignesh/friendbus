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

if(isset($_POST["rateid"])&&isset($_POST["ratestat"]))
{
	$rateid=$_POST["rateid"];
	$stat=$_POST["ratestat"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

$userobj->userinit();
$uid=$userobj->uid;
$socialobj=new ta_socialoperations();
if($stat=="1")
{
	if($socialobj->rating_check_alreadyrated_up_user($uid,$rateid))
	{
		echo "<statusmsg>You already rated this Up</statusmsg>";
		echo "<statuscode>5</statuscode>";
		echo "</document>";
		die('');
	}
	$socialobj->rating_up($uid,$rateid);
	echo "<statusmsg>Successfully Rated Up</statusmsg>";
	echo "<statuscode>1</statuscode>";
}
else
if($stat=="-1")
{
	if($socialobj->rating_check_alreadyrated_down_user($uid, $rateid))
	{
		echo "<statusmsg>You already rated this Down</statusmsg>";
		echo "<statuscode>6</statuscode>";
		echo "</document>";
		die('');
	}
	$socialobj->rating_down($uid,$rateid);
	echo "<statusmsg>Successfully Rated Down</statusmsg>";
	echo "<statuscode>1</statuscode>";
}
else
{
	echo "<statusmsg>Improper Rating Request</statusmsg>";
	echo "<statuscode>4</statuscode>";
	echo "</document>";
	die('');
}

echo "</document>";

?>