<?php
require 'header.php';
$userobj=new ta_userinfo();
$errobj=new ta_errorhandle();
$utilityobj=new ta_utilitymaster();

if($userobj->checklogin())
{
	$errobj->senderror("You are already Logged in! Please Logout to access this Page!","#ta#alreadyloggedin","","1","Logout","logout.php","Continue with my existing account","index.php","Already Logged in!");
}

if(isset($_GET["email"])&&isset($_GET["dob"])&&isset($_GET["forgot"]))
{
	$email=$_GET["email"];
	$dob=$_GET["dob"];
	$forgot=$_GET["forgot"];
	if($email==""||$dob==""||$forgot=="")
	{
		$utilityobj->enablebufferoutput();
		$utilityobj->outputbuffercont();
		$errobj->senderror("Some required fields are left empty!","#ta#forgotpwdempty","","1","forgotpassword.php","Try Again");
	}

	if($forgot=="1")
	{
		$uname=$_GET["uname"];
		if($uname=="")
		{
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			$errobj->senderror("User Name was empty!","#ta#forgotpwdempty","","1","forgotpassword.php","Try Again");
		}
		$uiobj=new ta_uifriend();
		$secobj=new ta_secureclass();
		$newpass=$uiobj->randomstring(8);
		$np=$secobj->encryptpass($newpass);
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_uemail."='$email'",tbl_user_info::dbname);
		if($res==EMPTY_RESULT)
		{
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			$errobj->senderror("Email Address does not exist!","#ta#invalidemail","","1","forgotpassword.php","Try Again");
		}
		if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_upass."='$np' WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_uemail."='$email' AND ".tbl_user_info::col_udob."='$dob'",tbl_user_info::dbname)=="SUCCESS")
		{
			$emaiobj=new ta_mailclass();
			$dateobj=new ta_dateoperations();
			$msg='
					Dear User,
					<br><br>
					We received a request to reset your account password at our website on '.$dateobj->timestamptodate(time()).' .
					Please use this link to change your password: <a href="http://www.friendbus.com/changepassword.php?newpwd='.$np.'&email='.$email.'&uname='.$uname.'">http://www.friendbus.com/changepassword.php?newpwd='.$np.'&email='.$email.'&uname='.$uname.'</a>
					<br><br>
					If the link above does not work, please visit http://www.friendbus.com/changepassword.php and enter your Old Password as:<b>'.$newpass.'</b> to reset the password
					<br><br>
					If you did not request for a password reset, please contact the Customer Care immediately.
				';
			$emaiobj->sendmail($email, $msg,"TECH AHOY PASSWORD RESET LINK","1","1212121");
			echo '
				<script type="text/javascript">
					alert("'.$newpass.'The password has been reset successfully and the password reset link has been sent to '.$email.'");
					window.location.assign("/index.php");
				</script>
				';
		}
		else
		{
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			$errobj->senderror("The entered data does not match with any records in our database!","#ta#forgotpwdnomatch","","1","forgotpassword.php","Try Again");
		}
	}
	else
	if($forgot=="2")
	{
		$uiobj=new ta_uifriend();
		$secobj=new ta_secureclass();
		$dbobj=new ta_dboperations();
		$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_uemail."='$email' AND ".tbl_user_info::col_udob."='$dob'",tbl_user_info::dbname);
		if($res!=EMPTY_RESULT)
		{
			$emaiobj=new ta_mailclass();
			$dateobj=new ta_dateoperations();
			$msg='
					Dear User,
					<br><br>
					We received a request to retrieve the User Name for your account at our website on '.$dateobj->timestamptodate(time()).' .';
			$msg.='<br>The User Name(s) associated with this account is(are):<hr><br>';
			$co=1;
					for($i=0;$i<count($res);$i++)
					{
						$msg.=$co.") ".$res[$i][changesqlquote(tbl_user_info::col_unm,"")]."<br>";
						$co++;
					}
			$msg.='
					<hr><br>
					If you did not request for a User Name retrieval, please contact the Customer Care immediately.
					';
			$emaiobj->sendmail($email, $msg,"TECH AHOY USER NAME RETRIEVAL","1","1212121");
			echo '
				<script type="text/javascript">
					alert("The User Name associated has been sent successfully to '.$email.'");
					window.location.assign("index.php");
				</script>
				';
		}
		else
		{
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			$errobj->senderror("The entered data does not match with any records in our database!","#ta#forgotunamenomatch","","1","forgotpassword.php","Try Again");
		}
	}
}
?>

<div id="template_content_body">

<form name="taforgotpwdform" id="taforgotpwdform" action="#">
	<fieldset>
		<legend>Forgot your Password or User Name?</legend>
		Your E-Mail Address: <input type="email" class="form-control" name="email" style="width:220px;display:inline-block;" required="required" placeholder="Please Enter Your E-mail Address">
		<br><br>
		Your Date of Birth: <input type="date" class="form-control" name="dob" style="width:220px;display:inline-block;" required="required">
		<br><br>
			<input type="button" class="btn btn-default" value="Reset my Password" id="respwd">
			<input type="button" class="btn btn-default" value="Retrieve my User Name" id="resuname">
			<a href="/index.php"><input type="button" class="btn btn-default" value="Go back"></a>
			<div style="clear:both;"></div>
	</fieldset>
</form>
</div>

<?php require MASTER_TEMPLATE.'/footer.php'?>

<script type="text/javascript">
$(document).ready(function(){

	$("#respwd").click(function(){
		var uname;
		do
		{
			uname=prompt("Please Enter Your User Name");
			if(uname==false)return;
		}while(uname=="");
		var email=document.taforgotpwdform.email.value;
		var dob=document.taforgotpwdform.dob.value;
		window.location.assign("forgotpassword.php?email="+email+"&dob="+dob+"&forgot=1&uname="+uname);
	});

	$("#resuname").click(function(){
		var email=document.taforgotpwdform.email.value;
		var dob=document.taforgotpwdform.dob.value;
		window.location.assign("forgotpassword.php?email="+email+"&dob="+dob+"&forgot=2");
	});

});
</script>