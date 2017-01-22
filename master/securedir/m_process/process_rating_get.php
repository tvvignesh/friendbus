<?php
require_once 'adjustment.php';
header("content-type:text/xml");
$noecho="yes";
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/techahoy/filemaster.php';
$userobj=new ta_userinfo();

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "<document>";

if(isset($_POST["rateid"]))
{
	$rateid=$_POST["rateid"];
}
else
{
	echo "<statusmsg>Improper Request</statusmsg>";
	echo "<statuscode>2</statuscode>";
	echo "</document>";
	die('');
}

$socialobj=new ta_socialoperations();
$rating=$socialobj->rating_current($rateid);
echo "<rating>".$rating."</rating>";
echo "<statusmsg>Rating Retrieval Successful</statusmsg>";
echo "<statuscode>1</statuscode>";
echo "</document>";

?>