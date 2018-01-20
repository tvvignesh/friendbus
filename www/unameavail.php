<?php
if(isset($_POST["uname"]))
{
	$uname=$_POST["uname"];
	$uname=strtolower($uname);
	if($uname=="")
	{
		die("");
	}
	$noecho="yes";
	$GLOBALS["noecho"]="yes";
	$GLOBALS["noheaders"]="yes";
	require_once("adjustment.php");
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	$dbobj=new ta_dboperations();
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname'",tbl_user_info::dbname);
	if($res==EMPTY_RESULT)
	{
		echo "SUCCESS";
	}
	else
	{
		echo "FAILURE";
	}
}
?>