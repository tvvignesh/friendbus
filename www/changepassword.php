<?php
require 'header.php';
$dbobj=new ta_dboperations();
$utilityobj=new ta_utilitymaster();

$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();

if(isset($_POST["uname"])&&isset($_POST["email"])&&isset($_POST["oldpass"])&&isset($_POST["newpass1"])&&isset($_POST["newpass2"]))
{
	$uname=$_POST["uname"];
	$email=$_POST["email"];
	$oldpass=$_POST["oldpass"];
	$newpass1=$_POST["newpass1"];
	$newpass2=$_POST["newpass2"];
	$secobj=new ta_secureclass();
	$op=$secobj->encryptpass($oldpass);

	if($newpass1!=$newpass2)
	{
		$errobj=new ta_errorhandle();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		$errobj->senderror("OOPS! The 2 Passwords you entered does not match!","#ta#pwdnomatch","","1","changepassword.php","Try Again!");
	}
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_uemail."='$email' AND ".tbl_user_info::col_upass."='$op'",tbl_user_info::dbname);
	if($res==EMPTY_RESULT||$res==FAILURE)
	{
		$errobj=new ta_errorhandle();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		$errobj->senderror("OOPS! Invalid Credentials! Please Try Re-Entering your Old Password or Try Logging in Again","#ta#pwdchangeinvalid","","1","changepassword.php","Try Again!","logout.php","Login Again");
	}
	else
	{
		$newp=$secobj->encryptpass($newpass1);
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_upass."='$newp' WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_uemail."='$email' AND ".tbl_user_info::col_upass."='$op'",tbl_user_info::dbname)=="SUCCESS")
		{
			die('
				<script type="text/javascript">
					alert("The password has been changed successfully! You will now be redirected to the Login Page.");
					window.location.assign("logout.php");
				</script>
				');
		}
		else
		{
			$errobj=new ta_errorhandle();
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			$errobj->senderror("OOPS! There was an error in Changing your Password! Please Try Again!","#ta#pwdupdateinvalid","","1","changepassword.php","Try Again!","logout.php","Login Again");
		}
	}
}
else
if(isset($_GET["newpwd"])&&isset($_GET["email"])&&isset($_GET["uname"]))
{
	$uname=$_GET["uname"];
	$np=$_GET["newpwd"];
	$email=$_GET["email"];
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_upass."='$np' AND ".tbl_user_info::col_uemail."='$email'",tbl_user_info::dbname);
	if($res!=EMPTY_RESULT)
	{
		die('
				<form name="pwdresetform" action="changepassword.php" method="post" id="tapwdresform">
					<fieldset>
						<legend>Change your Password</legend>
							<input type="hidden" value="'.$uname.'" name="uname">
							<input type="hidden" value="'.$email.'" name="email">
							<input type="hidden" value="'.$np.'" name="oldpass">
							Your New Password: <input type="password" name="newpass1" placeholder="Please Enter your New Password">
							<br><br>
							Re-Enter Password: <input type="password" name="newpass2" placeholder="Please Repeat the Password">
							<br><br>
							<div class="talboxbtn" align="center"><input type="submit" value="Update my Password"></div>
					</fieldset>
				</form>
			');
	}
}
else
{
	$userobj=new ta_userinfo();
	if($userobj->checklogin())
	{
		$userobj->userinit();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		echo '<div id="template_content_body">
				<form name="pwdresetform" action="changepassword.php" method="post" id="tapwdresform">
						<fieldset>
							<legend>Change your Password</legend>
								<input type="hidden" value="'.$userobj->uname.'" name="uname">
								<input type="hidden" value="'.$userobj->email.'" name="email">
								Your Old Password: <input type="password" class="form-control" name="oldpass" placeholder="Please Enter your Old Password">
								<br>
								Your New Password: <input type="password" class="form-control" name="newpass1" placeholder="Please Enter your New Password">
								<br>
								Re-Enter Password: <input type="password" class="form-control" name="newpass2" placeholder="Please Repeat the Password">
								<br>
								<input class="btn btn-primary" type="submit" value="Update my Password">
						</fieldset>
					</form></div>
			';
	}
	else
	{
		$errobj=new ta_errorhandle();
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		$errobj->senderror("You Must Login first with your existing account to Change your Password!","#ta#pwdresnotloggedin","","1","index.php","Log in","changepassword.php","No, Try Again");
	}
}

$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();

?>