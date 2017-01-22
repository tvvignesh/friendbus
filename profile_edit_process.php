<?php 
header('Content-Type: application/json');
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$utilityobj=new ta_utilitymaster();
$socialobj=new ta_socialoperations();

$name=$_POST["name"];
$val=$_POST["value"];
$pk=$_POST["pk"];

if(!$userobj->checklogin()||$pk=="")
{
	$data = array( 'returnval' =>3, 'message' =>"You must be logged in to do this operation!" );
	die(json_encode($data));
}

$userobj->userinit();
if($name!="s_addfriend"&&$name!="s_removefriend")
{
	if($userobj->uid!=$pk)
	{
		$data = array( 'returnval' =>4, 'message' =>"OOPS! You are only allowed to edit your profile!" );
		die(json_encode($data));
	}
}

switch($name)
{
	case "fname":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_ufname,$val);
		break;
	case "mname":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_umname,$val);
		break;
	case "lname":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_ulname,$val);
		break;
	case "dob":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_udob,$val);
		break;
	case "gender":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_ugender,$val);
		break;
	case "phone":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_uphone,$val);
		break;
	case "mobile":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_umobno,$val);
		break;
	case "email":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_uemail,$val);
		break;
	case "address":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_uaddress,$val);
		break;
	case "educelem":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_degree,$val);
		break;
	case "educinst":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_instname,$val);
		break;
	case "edu_stime":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_stime,$val);
		break;
	case "edu_etime":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_etime,$val);
		break;
	case "edu_notes":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_notes,$val);
		break;
	case "edu_insturl":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_insturl,$val);
		break;
	case "edu_loc":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_locid,$val);
		break;
	case "edu_batchmate":
		$elemid=$_POST["elemid"];
		$ret=$userobj->education_edit($userobj->uid,$elemid,tbl_user_edu::col_listid,$val);
		break;
	case "workelem":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_role,$val);
		break;
	case "workinst":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_instname,$val);
		break;
	case "work_stime":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_stime,$val);
		break;
	case "work_etime":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_etime,$val);
		break;
	case "work_notes":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_notes,$val);
		break;
	case "work_minsal":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_salarymin,$val);
		break;
	case "work_maxsal":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_salarymax,$val);
		break;
	case "work_orgurl":
		$elemid=$_POST["elemid"];
		$ret=$userobj->work_edit($userobj->uid,$elemid,tbl_user_work::col_insturl,$val);
		break;
	case "politicalview":
		$ret=$userobj->user_editextrainfo($userobj->uid,tbl_user_extras::col_politicalview,$val);
		break;
	case "aliases":
		$ret=$userobj->user_editextrainfo($userobj->uid,tbl_user_extras::col_aliases,$val);
		break;
	case "relationship_stat":
		$ret=$userobj->user_editextrainfo($userobj->uid,tbl_user_extras::col_relstat,$val);
		break;
	case "religion":
		$ret=$userobj->user_editextrainfo($userobj->uid,tbl_user_extras::col_religid,$val);
		break;
	case "pincode":
		$ret=$userobj->user_editinfo($userobj->uid,tbl_user_info::col_upincode,$val);
		break;
	case "socialurl":
		$elemid=$_POST["elemid"];
		$ret=$utilityobj->link_edit($elemid,tbl_linkdb::col_url,$val);
		$utilityobj->link_favico_refresh($elemid);
		break;
	case "sociallabel":
		$elemid=$_POST["elemid"];
		$ret=$utilityobj->link_edit($elemid,tbl_linkdb::col_label,$val);
		break;
	case "achievement":
		$elemid=$_POST["elemid"];
		$colelemid=$userobj->extras->col_achievementid;
		$ret=$utilityobj->achievement_edit($colelemid,$elemid,tbl_collection_achievements::col_label,$val);
		break;
	case "devices":
		$elemid=$_POST["elemid"];
		$ret=$userobj->device_edit($userobj->uid,$elemid,tbl_user_devices::col_label,$val);
		break;
	case "scribbles":
		$ret=$userobj->user_editextrainfo($userobj->uid,tbl_user_extras::col_scribbles,$val);
		break;
	default:
		$data = array( 'returnval' =>5, 'message' =>"OOPS! Unidentified Request!" );
		die(json_encode($data));
		break;
		
}

if($ret==SUCCESS)
{
	$data = array( 'returnval' =>1, 'message' =>"Success" );
	echo json_encode($data);
}
else
if($ret==FAILURE)
{
	$data = array( 'returnval' =>2, 'message' =>"Failure" );
	echo json_encode($data);
}
?>