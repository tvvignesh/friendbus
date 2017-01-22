<?php 
header('Content-Type: application/json');
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
$logobj=new ta_logs();

$elemtype=$_POST["elemtype"];
$uid_get=$_POST["uid"];

if(!$userobj->checklogin())
{
	$data = array( 'returnval' =>3, 'message' =>"OOPS! You must be logged in to add data to this field!" );
	die(json_encode($data));
}

$userobj->userinit();
if($userobj->uid!=$uid_get)
{
	$data = array( 'returnval' =>4, 'message' =>"OOPS! You are only allowed to add data to your profile!" );
	die(json_encode($data));
}

switch($elemtype)
{	
	case "prof_edu":
		$degree=$_POST["degree"];
		$institution=$_POST["institution"];
		$stime=$_POST["stime"];
		$etime=$_POST["etime"];
		$notes=$_POST["notes"];
		$insturl=$_POST["insturl"];
		$locid=$_POST["locid"];
		$listid=$_POST["listid"];
		$edutype=$_POST["edutype"];
		$ret=$userobj->education_add($institution,$edutype,$locid,$degree,$userobj->uid,$insturl,$stime,$etime,"1",$notes,"",$listid);
		break;
	case "prof_work":
		$role=$_POST["role"];
		$institution=$_POST["institution"];
		$worktype=$_POST["worktype"];
		$stime=$_POST["stime"];
		$etime=$_POST["etime"];
		$notes=$_POST["notes"];
		$insturl=$_POST["insturl"];
		$listid=$_POST["listid"];
		$locid=$_POST["locid"];
		$ret=$userobj->work_add($institution,$worktype,$locid,$role,$userobj->uid,$insturl,$stime,$etime,"1",$notes,"","","",$listid);
		break;
	case "prof_skill":
		$skill=$_POST["skill"];
		$skillid=$utilityobj->skilltoid($skill);
		$ret=$userobj->skill_add($userobj->uid,$skillid);
		break;
	case "prof_achievement":
		$achievement=$_POST["achievement"];
		$desc=$_POST["desc"];
		$achievetime=$_POST["achievetime"];
		$ret=$userobj->achievement_add($userobj->uid,$achievement,$desc,$achievetime,"");
		break;
	case "prof_socialadd":
		$weburl=$_POST["weburl"];
		$label=$_POST["label"];
		$ret=$utilityobj->link_add($weburl,$label);
		$res=$userobj->user_getextrainfo($userobj->uid);
		$col_socialid=$res["col_social"];
		$dbobj=new ta_dboperations();
		$ret=$dbobj->dbinsert("INSERT INTO ".tbl_collection_links::tblname." (".tbl_collection_links::col_col_linkid.",".tbl_collection_links::col_linkid.") VALUES ('$col_socialid','$ret')", tbl_collection_links::dbname);
		break;
	default:
		$data = array( 'returnval' =>5, 'message' =>"OOPS! Unidentified Request!" );
		die(json_encode($data));
		break;
		
}

	$data = array( 'returnval' =>1, 'message' =>"Success" );
	echo json_encode($data);
?>