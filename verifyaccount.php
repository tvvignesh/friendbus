<?php
require 'header.php';
if(isset($_GET["email"]))
{
	$email=$_GET["email"];
}
else
{
	$email='';
}
if(isset($_GET["uname"]))
{
	$uname=$_GET["uname"];
	$dbobj=new ta_dboperations();
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$uname'",tbl_user_info::dbname);
	if($res!=EMPTY_RESULT)
	{
		if($res[0][changesqlquote(tbl_user_info::col_uflag,"")]=="2")
		{
			echo '
					<script type="text/javascript">
							alert("Your account was already verified!");window.location.assign("index.php");
					</script>
					';
		}
	}
}
else
{
	$uname='';
}

if(isset($_GET["verifyid"])&&$email!=''&&$uname!='')
{
	$verifyid=$_GET["verifyid"];
	$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_uverifyid."='$verifyid' AND ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_uemail."='$email'",tbl_user_info::dbname);
	if($res!=EMPTY_RESULT)
	{
		if($res[0][changesqlquote(tbl_user_info::col_uflag,"")]=="1")
		{
			$randobj=new ta_uifriend();
			$rand=$randobj->randomstring(15);
			if($dbobj->dbupdate("UPDATE ".tbl_user_info::tblname." SET ".tbl_user_info::col_uverifyid."='$rand',".tbl_user_info::col_uflag."='2' WHERE ".tbl_user_info::col_unm."='$uname' AND ".tbl_user_info::col_uemail."='$email'",tbl_user_info::dbname)=="SUCCESS")
			{
				echo '
						<script type="text/javascript">
							alert("Verification Successful!");window.location.assign("index.php");
						</script>
					';
			}
		}
		else
		{
			echo '
						<script type="text/javascript">
							alert("Verification Unsuccessful! Your Account might have been blocked!");window.location.assign("index.php");
						</script>
					';
		}
	}
	else
	{
		echo '</div><div align="center"><font color="red">OOPS! Verification Unsuccessful! Try Again!</font></div>';
	}
}
echo '
		<div id="template_content_body">
		<div style="clear:both;"></div>
		<br><br>
		<div id="verifyfieldset">
			<form name="verifyaccform" method="get" action="verifyaccount.php">
			<fieldset>
			<legend>Verify Your Account</legend>
			E-mail: <input type="email" name="email" placeholder="Please Enter your E-mail Address" value="'.$email.'" style="width:230px;" class="form-control">
			<br>
			User Name: <input type="text" name="uname" placeholder="Please Enter your User Name" value="'.$uname.'" style="width:230px;" class="form-control">
			<br>
			 Verification Code: <input type="text" name="verifyid" placeholder="Please Enter the Verification Code" style="width:230px;" class="form-control">
			 <br>
			 <div class="talboxbtn" align="center">
					<input type="submit" value="Verify my Account">
					<input type="button" onclick="window.location.assign(\'index.php\');" value="Verify Later">
			</div>
			</fieldset>
			</form>
		<div><br><br>
		</div>
	';

require MASTER_TEMPLATE.'/footer.php';
?>