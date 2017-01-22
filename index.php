<?php
ob_start();
require("header.php");
$dateobj=new ta_dateoperations();
$dateobj->usrtimezone_get();
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$assetobj=new ta_assetloader();

	if($userobj->checklogin())
	{
		$uiobj=new ta_uifriend();
		$uiobj->returnpath();
	}
	else
	{
		if(isset($_POST["tauuname"])&&isset($_POST["taupass"]))
		{
			$uname=$_POST["tauuname"];
			$pass=$_POST["taupass"];
			$userobj=new ta_userinfo();
			$utilityobj->enablebufferoutput();
			$utilityobj->outputbuffercont();
			if(isset($_POST["taremember"]))
			{
				$userdata=$userobj->login($uname,$pass,"1");
			}
			else
			{
				$userdata=$userobj->login($uname,$pass,"0");
			}
			if($userdata!=SUCCESS)
			{
				$errobj=new ta_errorhandle();
				$errobj->senderror("OOPS! Invalid User Name or Password!","#ta#ulogininvalid","","2","index.php","Try Again!");
				$userobj->sessiondestroy();
				$userobj->cookiedestroy();
			}
			else
			{
				if($_COOKIE["returnpath"]==HOST_SERVER."/index.php")
				{
					setcookie("returnpath",HOST_SERVER."/profile.php",0,'/');
				}
				$uiobj=new ta_uifriend();
				$uiobj->returnpath();
				die("Redirecting...");
			}
		}
		else
		{

		}
	}
?>
<div class="taloginmaincont">

	<div class="taloginrdesc">

		<div class="signupcont">
			<span id="newtechahoytext">New here?</span> <div class="talboxbtn" id="tasignupbtn"><input type="button" value="CREATE AN ACCOUNT" onclick="window.location.assign('signup.php');"></div>
		</div>

		<form name="taloginform" action="index.php" method="post">
			<div class="taloginbox">
				<div class="taloginboxtitle">Sign in</div>
				<div class="talboxlabel">Username:</div>
				<div class="talboxinput"><input type="text" name="tauuname" placeholder="Your User Name" x-webkit-speech class="form-control"></div>
				<div class="talboxlabel">Password:</div>
				<div class="talboxinput"><input type="password" name="taupass" placeholder="Your Password" class="form-control" data-toggle="password"></div>
				<div class="talboxbtn"><input type="submit" value="Sign in" style="float:left;"></div>
				<div style="float:left;margin-top:10px;margin-left:15px;"><input type="checkbox" name="taremember" checked> Stay Signed in</div>
				<div style="clear:both;"></div>
				<div class="talboxforgotpwd"><a href="forgotpassword.php">Forgot your Password or User Name?</a></div>
			</div>
		</form>

	</div>
	
		<div class="taloginldescenclose">
	<div class="taloginldesc">
		<div class="taloginquote">Friendbus - The key to connect</div>
		<div class="taloginhighlights">

			<div class="taloginhenclose">
				<div class="taloginhicon">
					<i class="fa fa-globe fa-2x" style="color:green;"></i>
				</div>
				<div class="taloginhdesc">
					Stay connected globally.
				</div>
			</div>

			<div class="taloginhenclose">
				<div class="taloginhicon">
					<i class="fa fa-users fa-2x" style="color:brown;"></i>
				</div>
				<div class="taloginhdesc">
					Connect to People with Similar Interests as you 
				</div>
			</div>

			<div class="taloginhenclose">
				<div class="taloginhicon">
					<i class="fa fa-newspaper-o fa-2x" style="color:#5F9EA0;"></i>
				</div>
				<div class="taloginhdesc">
					Update yourself with Current affairs, Trending News, Match Fixtures & more
				</div>
			</div>

			<div class="taloginhenclose">
				<div class="taloginhicon">
					<i class="fa fa-gamepad fa-2x" style="color:#6495ED;"></i>
				</div>
				<div class="taloginhdesc">
					Keep yourself entertained with Games, Music &amp; Apps
				</div>
			</div>
		</div>

		<div style="clear:both;"></div>
	</div>

	<div class="taloginupdatebox">
		<marquee onmouseover="stop();" onmouseout="start();" scrollamount="4">
			This is a beta version of the product. We request you to report bugs or technical issues if you encounter any. We will keep working to make you happy & productive.
		</marquee>
	</div>

	</div>

	<div style="clear:both;"></div>
</div>
<?php 
require MASTER_TEMPLATE.'/footer.php';
$assetobj->load_js_product_login();
?>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",function(){
	$(document).ready(function(){
		$(document).prop('title', 'Friendbus');
		$(".sbox_input").remove();
		$("#tbar_login").remove();
		$("#tbar_dashboard").remove();
		$("#tbar_feeds").remove();
	});
});
</script>