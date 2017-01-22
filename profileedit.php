<?php
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$userobj->userinit();

if(isset($_POST["taprofeditformfield"]))
{
	$uid=$_POST["taprofeditformfield"];
	$secobj=new ta_secureclass();
	$uid=$secobj->decrypt($uid,"#ta_profeditform_123");

	$uname=$userobj->uname;

	if($uid!=$userobj->uid)
	{

	}
	if(isset($_POST["ufname"])&&isset($_POST["umname"])&&isset($_POST["ulname"])&&isset($_POST["email"])&&isset($_POST["mobno"])&&isset($_POST["ucountry"])&&isset($_POST["ustate"])&&isset($_POST["pincode"])&&isset($_POST["ucompaddr"])&&isset($_POST["taprofeditformfield"]))
	{
		$fname=$_POST["ufname"];
		$mname=$_POST["umname"];
		$lname=$_POST["ulname"];
		$email=$_POST["email"];
		$mobno=$_POST["mobno"];
		$country=$_POST["ucountry"];
		$state=$_POST["ustate"];
		$pincode=$_POST["pincode"];
		$compaddr=$_POST["ucompaddr"];
		if($fname==""&&$lname==""&&$email==""&&$mobno==""&&$country==""&&$state==""&&$pincode==""&&$compaddr=="")
		{
			$errobj=new ta_errorhandle();
			$errobj->senderror("OOPS! Some of the Required fields were left blank! Try Again!","#ta#profedit#fieldblank","","1","profile.php#editprofile","Try Again!");
		}

		$dbobj=new ta_dboperations();
		$status=$dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_ufname."='$fname',".tbl_user_info::col_umname."='$mname',".tbl_user_info::col_ulname."='$lname',".tbl_user_info::col_uemail."='$email',".tbl_user_info::col_umobno."='$mobno',".tbl_user_info::col_ucountry."='$country',".tbl_user_info::col_ustate."='$state',".tbl_user_info::col_upincode."='$pincode',".tbl_user_info::col_uaddress."='$compaddr' WHERE ".tbl_user_info::col_usrid."='$uid' AND ".tbl_user_info::col_unm."='$userobj->uname'",tbl_user_info::dbname);
		if($status!="SUCCESS")
		{
			$errobj=new ta_errorhandle();
			$errobj->senderror("OOPS! An error occured in updating your profile details","#ta#profileeditupdateerror",$status->error,"1","profile.php#editprofile","Try Again");
		}
		else
		{
			if($_FILES["uprofpic"]["name"]!="")
			{
				$p1=$userobj->profpicurl;
				$p2=$userobj->compprofpic1;
				$p3=$userobj->compprofpic2;
				$fileobj=new ta_fileoperations();
				$userobj->profpicurl=$fileobj->picupload($_FILES["uprofpic"],"profile_orig",0);
				$userobj->compprofpic1=$fileobj->picupload($_FILES["uprofpic"],"profile_comp1",1,150,150);
				$userobj->compprofpic2=$fileobj->picupload($_FILES["uprofpic"],"profile_comp2",1,80,80);
				if($userobj->profpicurl==""||$userobj->compprofpic1==""||$userobj->compprofpic2=="")
				{
					$errobj=new ta_errorhandle();
					$errobj->senderror("OOPS! An error occured in Uploading the Profile Picture!","#ta#profpicedituploaderror","","2","profile.php#profileedit","Try Again!");
				}
				else
				{
					$status=$dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_profpicurl."='$userobj->profpicurl',".tbl_user_info::col_cprofpic1."='$userobj->compprofpic1',".tbl_user_info::col_cprofpic2."='$userobj->compprofpic2' WHERE ".tbl_user_info::col_usrid."='$userobj->uid'",tbl_user_info::dbname);
					$pparts=pathinfo($p1);
					$filname=$pparts["filename"];
					$ext=$pparts["extension"];
					unlink(ROOT_SERVER."/mediainfo/profilepic/original/".$filname.".".$ext);
					$pparts=pathinfo($p2);
					$filname=$pparts["filename"];
					$ext=$pparts["extension"];
					unlink(ROOT_SERVER."/mediainfo/profilepic/compressed_1/".$filname.".".$ext);
					$pparts=pathinfo($p3);
					$filname=$pparts["filename"];
					$ext=$pparts["extension"];
					unlink(ROOT_SERVER."/mediainfo/profilepic/compressed_2/".$filname.".".$ext);
				}
			}

			$uiobj=new ta_uifriend();
			$uiobj->redirectjs("profile.php#editprofile");
			die();
		}
	}
}
else
{
	$uname=$_POST["uname"];
	$uid=$_POST["uid"];
	$email=$_POST["email"];
}
$errobj=new ta_errorhandle();
if(!$userobj->checklogin())
{
	$errobj->senderror("OOPS! You must Login to Edit your Profile","#ta#profeditnotloggedin","","1","index.php","Login","profile.php","Back to Profile!");
}

if($uid!=$userobj->uid)
{
	$errobj->senderror("OOPS! You are only allowed to edit your profile!","#ta#profeditnotallowed","","1","profile.php","Back to Profile");
}
$secobj=new ta_secureclass();
echo
'
<form name="taprofeditform" action="profileedit.php" method="post" id="taprofeditform" enctype="multipart/form-data">
	<fieldset>
		<legend>Edit Your Profile</legend>
		<div style="float:left;">
			First Name: <input type="text" name="ufname" value="'.$userobj->fname.'">
		</div>
		<div style="float:left;">
		Middle Name: <input type="text" name="umname" value="'.$userobj->mname.'">
		</div>
		<div style="float:left;">
		Last Name: <input type="text" name="ulname" value="'.$userobj->lname.'">
		</div>
		<div style="clear:both;"></div>
		E-Mail Address: <input type="email" name="email" value="'.$userobj->email.'">
		<br>
		Mobile Number: <input type="text" name="mobno" value="'.$userobj->mobno.'">
		<br>
		<div style="float:left;">
		Country: <input type="text" name="ucountry" value="'.$userobj->country.'">
		</div>
		<div style="float:left;">
		State: <input type="text" name="ustate" value="'.$userobj->state.'">
		</div>
		<div style="float:left;">
		Pincode: <input type="text" name="pincode" value="'.$userobj->pincode.'">
		</div>
		<div style="clear:both;"></div>
		Complete Address:
		<br>
		<textarea name="ucompaddr" rows="6" cols="40" style="padding:5px;">'.$userobj->compaddr.'</textarea>
		<br><br><img src="'.$userobj->compprofpic1.'"><br><br>
		Change your Profile Picture: <input type="file" name="uprofpic">
		<br><br>
				';
$encuid=$secobj->encrypt($userobj->uid,'#ta_profeditform_123');
echo '
		<input type="hidden" name="taprofeditformfield" value="'.$encuid.'">
		<div class="talboxbtn" align="center"><input type="submit" value="Done Editing"></div>
	</fieldset>
</form>
';

?>