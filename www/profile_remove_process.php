<?php 
header('Content-Type: application/json');
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
$logobj=new ta_logs();
$colobj=new ta_collection();

$elemtype=$_POST["elemtype"];
$elemid=$_POST["elemid"];
$uid_get=$_POST["uid"];

if(!$userobj->checklogin())
{
	$data = array( 'returnval' =>3, 'message' =>"OOPS! You must be logged in to remove this field!" );
	die(json_encode($data));
}

$userobj->userinit();
if($userobj->uid!=$uid_get)
{
	$data = array( 'returnval' =>4, 'message' =>"OOPS! You are only allowed to remove data from your profile!" );
	die(json_encode($data));
}

switch($elemtype)
{	
	case "prof_weburl":
		$res=$userobj->user_getextrainfo($userobj->uid);
		$colid=$res["col_social"];
		$ret=$colobj->remove_collection_item(tbl_collection_links::tblname,tbl_collection_links::col_col_linkid,$colid,tbl_collection_links::col_linkid,$elemid);
		break;
	case "prof_education":
		$ret=$userobj->education_delete($userobj->uid,$elemid);
		break;
	case "prof_achievements":
		$ret=$userobj->achievement_remove($userobj->uid,$elemid);
		break;
	case "prof_skills":
		$ret=$userobj->skill_remove($userobj->uid,$elemid);
		break;
	case "prof_work":
		$ret=$userobj->work_delete($userobj->uid,$elemid);
		break;
	default:
		$data = array( 'returnval' =>5, 'message' =>"OOPS! Unidentified Request!" );
		die(json_encode($data));
		break;
		
}

	$data = array( 'returnval' =>1, 'message' =>"Success" );
	echo json_encode($data);
?>