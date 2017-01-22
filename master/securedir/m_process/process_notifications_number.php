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
	echo "<statuscode>3</statuscode>";
	echo "</document>";
	die('');
}

$uid=$userobj->getuid();

$socialobj=new ta_socialoperations();
$res=$socialobj->readnotifications($uid,"1");
$res1=$socialobj->readnotifications($uid,"2");
$res2=$socialobj->readnotifications($uid,"3");

echo '<statuscode>1</statuscode>';
echo '<notify_number_read>'.count($res).'</notify_number_read>';
echo '<notify_number_unread>'.count($res1).'</notify_number_unread>';
echo '<notify_number_total>'.count($res2).'</notify_number_total>';

echo "</document>";

?>
