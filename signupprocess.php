<?php
ob_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/adjustment.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/header.php';
$assetobj->load_css_theme_default();
$assetobj->load_css_product_login();
$themeobj->template_load_left();

$user=new ta_userinfo();
$uifriend=new ta_uifriend();
$secobj=new ta_secureclass();
$dbobj=new ta_dboperations();
$errobj=new ta_errorhandle();
$userobj=new ta_userinfo();
$mailobj=new ta_mailclass();
$setobj=new ta_settings();
$galobj=new ta_galleryoperations();
$logobj=new ta_logs();
$utilityobj=new ta_utilitymaster();

$user->cookiedestroy();
$user->sessiondestroy();
if(!isset($_POST["ufname"])||!isset($_POST["g-recaptcha-response"])||!isset($_POST["ulname"])||!isset($_POST["usrname"])||!isset($_POST["usrpass1"])||!isset($_POST["usrpass2"])||!isset($_POST["usremail"])||!isset($_POST["usrdob"])||!isset($_POST["usrgender"])||!isset($_POST["usrmobno"]))
{
	$uifriend->redirectjs("signup.php");
}
$errormsg="";$errcount=0;

$captcha_response=$_POST["g-recaptcha-response"];
$captcha_secret="6Lf09xMTAAAAAO3L11u7msdSDEVx9f7MbnWybEaN";

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array('secret' => $captcha_secret, 'response' => $captcha_response);

// use key 'http' even if you send the request to https://...
$options = array(
		'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
		),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$jsonobj=$utilityobj->json_to_object($result);
if($jsonobj->success!="true")
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	die('<div id="template_content_body">Please check the captcha box to verify that you are not a robot!</div>');
}


$user->fname=$_POST["ufname"];
$user->lname=$_POST["ulname"];
$user->uname=$_POST["usrname"];
$user->pass=$_POST["usrpass1"];
$pass2=$_POST["usrpass2"];
$user->email=$_POST["usremail"];
$user->dob=$_POST["usrdob"];
$user->gender=$_POST["usrgender"];
$user->mobno=$_POST["usrmobno"];

$user->uname = preg_replace("/[^a-zA-Z0-9_]+/", "", $user->uname);

$user->aggraccept=1;

if(isset($_POST["experienceaccept"]))
{
	$user->expaccept=1;
}
else
{
	$user->expaccept=2;
}
if(isset($_POST["newsletteraccept"]))
{
	$user->newsaccept=1;
}
else
{
	$user->newsaccept=2;
}

//VALIDATION HERE
if($user->fname=="")
{
	$errormsg.="<li>First Name field cannot be blank</li>";
	$errcount++;
}
if($user->lname=="")
{
	$errormsg.="<li>Last Name field cannot be blank</li>";
	$errcount++;
}
if($user->uname=="")
{
	$errormsg.="<li>User Name field cannot be blank</li>";
	$errcount++;
}
if($user->email=="")
{
	$errormsg.="<li>E-mail field cannot be blank</li>";
	$errcount++;
}
if($user->gender=="")
{
	$errormsg.="<li>Gender field cannot be blank</li>";
	$errcount++;
}
if($user->mobno=="")
{
	$errormsg.="<li>Mobile Number field cannot be blank</li>";
	$errcount++;
}
if($user->pass=="")
{
	$errormsg.="<li>Password field cannot be blank</li>";
	$errcount++;
}
if($user->pass!=$pass2)
{
	$errormsg.="<li>The passwords you entered don't match</li>";
	$errcount++;
}

$res=$dbobj->dbquery("SELECT * FROM ".tbl_user_info::tblname." WHERE ".tbl_user_info::col_unm."='$user->uname'",tbl_user_info::dbname);
if($res!=EMPTY_RESULT)
{
	$errormsg.="<li>The User Name you selected is not available. Please choose a different User Name.</li>";
	$errcount++;
}

$user->pass=$secobj->encryptpass($user->pass);
$user->uid=$secobj->useridgen();
$secobj="";

$user->verifyid=$uifriend->randomstring(15,tbl_user_info::dbname,tbl_user_info::tblname,tbl_user_info::col_uverifyid);
$user->uflag=1;

$user->docroot=ROOT_SERVER."/media/usermedia/".$user->uname."_".$user->verifyid;
if(!mkdir($user->docroot,0777,BOOL_SUCCESS))
{
	$errormsg.="<li>Failed to Create Document Gallery for the User! Please contact the Server Administrator!</li>";
	$errcount++;
}
else
{
	if(!mkdir($user->docroot."/att",0777,BOOL_SUCCESS))
	{
		$errormsg.="<li>Failed to Create Attachment Gallery for the User! Please contact the Server Administrator!</li>";
		$errcount++;
	}
	else
	{
		if(!mkdir($user->docroot."/gal",0777,BOOL_SUCCESS))
		{
			$errormsg.="<li>Failed to Create Attachment Gallery for the User! Please contact the Server Administrator!</li>";
			$errcount++;
		}
		else
		{
			
		}
	}
}

if($errcount>0)
{
	$msg="We encountered ".$errcount." error(s) in your signup process! Please <a href='javascript:history.go(-1);'>Go Back</a> and correct it before Sign Up!";
	$msg.="<br><br>The errors are as follows:<br><br><ul>".$errormsg."</ul>";
	$errobj->senderror($msg,"ta#signup#1");
}

$status=$dbobj->dbinsert("INSERT INTO ".tbl_user_info::tblname." (".tbl_user_info::col_usrid.",".tbl_user_info::col_unm.",".tbl_user_info::col_upass.",".tbl_user_info::col_uemail.",".tbl_user_info::col_ugender.",".tbl_user_info::col_ufname.",".tbl_user_info::col_ulname.",".tbl_user_info::col_udob.",".tbl_user_info::col_umobno.",".tbl_user_info::col_uaggreementaccept.",".tbl_user_info::col_usubscribe.",".tbl_user_info::col_uuserexp.",".tbl_user_info::col_uloginstatus.",".tbl_user_info::col_uipaddr.",".tbl_user_info::col_uverifyid.",".tbl_user_info::col_uflag.",".tbl_user_info::col_docroot.")
VALUES ('$user->uid','$user->uname','$user->pass','$user->email','$user->gender','$user->fname','$user->lname','$user->dob','$user->mobno','$user->aggraccept','$user->newsaccept','$user->expaccept','0','".$user::getip()."','$user->verifyid','$user->uflag','$user->docroot')",tbl_user_info::dbname);

if($status!=SUCCESS)
{	
	$errobj=new ta_errorhandle();
	$errobj->senderror("OOPS! An error occured in Signing You Up! Please Try Again or ". $uifriend->contactfunc("webadmin","Contact the Server Administrator"),$status->errno,$status);
}
else
{
	
	do
	{
		$resb=$dbobj->dbinsert("INSERT INTO ".tbl_user_extras::tblname." (".tbl_user_extras::col_uid.") VALUES ('$user->uid')", tbl_user_extras::dbname);
	}while($resb!=SUCCESS);
	
	$setobj->setting_defaults($user->uid);
	
	
	
	$galid_default=$galobj->creategallery("My World","This is the gallery which has all default content on joining Friendbus",$user->uid,"0");
	$galid_thumb=$galobj->creategallery("Thumbs","This is the gallery which has autogenerated thumbnails stored",$user->uid,"8");
	$galid_vids=$galobj->creategallery("Processed Videos","This is the gallery which has the videos processed",$user->uid,"9");
	$galid_docs=$galobj->creategallery("Processed Documents","This is the gallery which has the documents processed",$user->uid,"10");
	$galid_audio=$galobj->creategallery("Processed Audio","This is the gallery which has the audio processed",$user->uid,"11");
	$galid_scan=$galobj->creategallery("Scan Reports","This is the gallery which has the scan reports of files",$user->uid,"12");
	$galid_meta=$galobj->creategallery("Metadata","This is the gallery which has metadata of files",$user->uid,"13");
	$galid_vault=$galobj->creategallery("Vault","This is the vault gallery",$user->uid,"14");
	$galid_sbx=$galobj->creategallery("Sandbox","This is the sanbox gallery",$user->uid,"15");
	$galid_att=$galobj->creategallery("Attachments","This is the attachment gallery",$user->uid,"16");
	
	$msg='Dear '.$user->fname.',<br><br>
	Welcome to Tech Ahoy! We have successfully registered you as one of our previliged users.
	<br><br>
	All you have to do now is, activate your account by clicking the activation link below which will lead you to the activation page of Tech Ahoy.
	<br>
	<b>Activation Link</b>: <a href="http://www.friendbus.com/verifyaccount.php?uname='.$user->uname.'&email='.$user->email.'&verifyid='.$user->verifyid.'">http://www.friendbus.com/verifyaccount.php?uname='.$user->uname.'&email='.$user->email.'&verifyid='.$user->verifyid.'</a>
	<br><br>
	If clicking the link does not work, either copy the link and paste it in your browser (or) visit http://www.friendbus.com/verifyaccount.php and enter the verification given below:<br><br>
	<b>Verification Code:</b>'.$user->verifyid.'
	<br><br>
	If you did not register at Tech Ahoy, then this mail would have been sent to you by mistake. Please ignore if that is the case.
	';
	if($mailobj->sendmail($user->email,$msg,"TECH AHOY ACCOUNT ACTIVATION LINK")=="SUCCESS")
	{
		echo
		'
				<script type="text/javascript">
				alert("Sign Up Successful! Please check your E-mail ('.$user->email.') for activation link and instructions for Logging into the account!");
				window.location.assign("/verifyaccount.php?email='.$user->email.'&uname='.$user->uname.'");
				</script>
		';
	}
}
$themeobj->template_load_right();
require MASTER_TEMPLATE.'/footer.php';
?>