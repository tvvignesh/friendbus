<?php
require_once 'adjustment.php';
header("content-type:text/xml");
$noecho="yes";
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';
$userobj=new ta_userinfo();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "<document>";

if(isset($_POST["uid"]))
{
	$uid=$_POST["uid"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

if($uid=="")
{
	echo "<statusmsg>User ID is empty</statusmsg>";
	echo "<statuscode>4</statuscode>";
	echo "</document>";
	die('');
}

$uobj=new ta_userinfo();
$uobj->user_initialize_data($uid);
$pic_normal=$uobj->profpicurl;
$pic_small=$uobj->compprofpic1;
$pic_vsmall=$uobj->compprofpic2;

echo '<statuscode>1</statuscode>';
echo '<pic_collection>';
echo '<pic_normal>'.$pic_normal.'</pic_normal>';
echo '<pic_small>'.$pic_small.'</pic_small>';
echo '<pic_vsmall>'.$pic_vsmall.'</pic_vsmall>';
echo '</pic_collection>';

echo "</document>";

?>