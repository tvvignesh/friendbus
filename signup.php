<?php
ob_start();
require("header.php");
$userobj=new ta_userinfo();
if($userobj->checklogin())
{
	ob_clean();
	$userobj->logout(HOST_SERVER."/signup.php");
}
?>
<div style="clear:both;"></div>
<div class="signupouterbox">
	<div class="tasignupleftcont">
		<div class="taloginquote">Sign Up is Easy.</div><br>
		<form action="signupprocess.php" method="post" enctype="multipart/form-data" name="tasignupform">
		<div class="tafieldenclose">
			<div class="tasboxlabel">First Name</div>
			<div class="tasboxinput"><input type="text" name="ufname" placeholder="First Name" required="required" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">Last Name</div>
			<div class="tasboxinput"><input type="text" name="ulname" placeholder="Last Name" required="required" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">Choose your Username</div>
			<div class="tasboxinput"><input type="text" name="usrname" placeholder="Choose your Unique User Name" required="required" id="tauname" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">Create a Password</div>
			<div class="tasboxinput"><input type="password" name="usrpass1" placeholder="Please create a Password" required="required" id="tapass1" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">Re-Enter Password</div>
			<div class="tasboxinput"><input type="password" name="usrpass2" placeholder="Please Re-Enter Password" required="required" id="tapass2" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">E-mail Address</div>
			<div class="tasboxinput"><input type="email" name="usremail" placeholder="Please enter your E-mail Address" required="required" class="form-control"></div>
		</div>

		<div class="tafieldenclose">
			<div class="tasboxlabel">Date of Birth</div>
			<div class="tasboxinput"><input type="date" name="usrdob" placeholder="Please enter your E-mail Address" required="required" title="Please Enter date in the form mm-dd-yyyy" class="form-control"></div>
		</div>
		
		<div class="tafieldenclose">
			<div class="tasboxlabel">Mobile Phone</div>
			<div class="tasboxinput"><input type="text" name="usrmobno" placeholder="Please Enter Your Mobile Number" required="required" class="form-control"></div>
		</div>
		
		<div class="tafieldenclose">
			<div class="tasboxlabel">Gender</div>
			<div class="tasboxinput" id="tasradiobtns">
			<br>
				<div style="float:left;">
					<input type="radio" name="usrgender" value="m">Male
					<input type="radio" name="usrgender" value="f">Female
					<input type="radio" name="usrgender" value="o">Other
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>

		<div style="clear:both;"></div>
		<br>
		
		<!-- <div class="checkbox_styled checkbox-primary">
		<input type="checkbox" name="agreementaccept" style="margin-left:10px;" required="required" id="aggterms"> 
		<label for="aggterms">I agree to <a href="/terms.php">Terms of Service</a> laid out by Friendbus</label>
		</div>-->
		
		<div class="checkbox_styled checkbox-primary">
		<input type="checkbox" name="experienceaccept" style="margin-left:10px;" id="signup_acctexp" checked> 
		<label for="signup_acctexp">
		Tech Ahoy may use my account information to enhance user experience and personalize ads and likings
		
		<span style="margin-left:0px;font-weight:bold;font-family:'Calibri';">(RECOMMENDED)</span>
		</label>
		</div>
		
		<div class="checkbox_styled checkbox-primary">
		<input type="checkbox" name="newsletteraccept" id="signup_newsletteraccept" style="margin-left:10px;"> 
		<label for="signup_newsletteraccept">I would like to subscribe to Tech Ahoy Newsletters & Updates</label>
		</div>
		<br><br>

		<div class="g-recaptcha" data-sitekey="6Lf09xMTAAAAAD6SMBJDbYtvAsxOLSs862SMzhWB" style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>
		(This step is just to avoid robots and scripts from hacking into this site)
		<br><br>
		<div class="talboxbtn" align="center"><input type="submit" value="Create my Account" onclick="submitfunc();return false;"></div>
		</form>
		
		<br><br>
		By signing up, you agree to the <a href="/terms.php">Terms of Service</a>, <a href="/privacy.php">Privacy Policy</a> and <a href="/cookiepolicy.php">Cookie Policy</a>
	</div>
	<div class="tasignuprightcont">
		<div class="tarightcontentenclose">
			<div class="tasignuprdesc">
				<ul class="tarsignuplist">
					<li>It is recommended to have a strong Password</li>
					<li>Username must be Unique</li>
					<li>E-mail address must be valid. All important communications are sent to your mail account</li>
					<li>WE DON'T SPAM</li>
					<li>Please Provide Valid information since the same information will be used for all activities</li>
				</ul>
			</div>
		</div>
	</div>

	<div style="clear:both;"></div>
</div>

<?php require MASTER_TEMPLATE.'/footer.php'?>

<script type="text/javascript">
	function submitfunc()
	{
		if(grecaptcha.getResponse()=="")
		{
			alert('Please check the box showing \"I am not a robot\" (To avoid SPAM bots from attacking the site)');
			return;
		}
		if($("input[name=ufname]").val()==""||$("input[name=ulname]").val()==""||$("input[name=usrname]").val()==""||$("input[name=usrpass1]").val()==""||$("input[name=usrpass2]").val()==""||$("input[name=usremail]").val()==""||$("input[name=usrdob]").val()==""||$("input[name=usrgender]").val()==""||$("input[name=usrmobno]").val()==""||$("input[name=pincode]").val()==""||$("input[name=compaddr]").val()==""||$("input[name=usrmobno]").val()=="")
		{
			alert("OOPS! Some required fields were left blank!");return;
		}
		else
		{
			document.tasignupform.submit();
		}
	}

	function checkunameavail(uname)
	{
		$.ajax({
			url: "unameavail.php",
			type:'POST',
			data: {uname:uname},
			cache: false,
			success: function(html){
				if(html=="SUCCESS")
				{
					$("#tauname").css("border-color","lightgreen");
					$("#tauname").css("border-style","solid");
					$("#tauname").popover('destroy');
					$("#tauname").popover({
						title:"This User Name is <span class='text-success'>available</span>",
						content:"This User Name is available. Register it before someone else does.",
						html:true,
						placement:"auto",
						trigger:"click hover focus"
						});
			  	}
				else
				if(html=="FAILURE")
				{
					$("#tauname").css("border-color","#FF0064");
					$("#tauname").css("border-style","solid");
					$("#tauname").popover('destroy');
					$("#tauname").popover({
						title:"This User Name is <span class='text-danger'>NOT</span> available",
						content:"Sorry! This User Name is NOT available. Try something else.",
						html:true,
						placement:"auto",
						trigger:"click hover focus"
						});
				}
				else
				{
					$("#tauname").css("border-color","");
					$("#tauname").css("border-style","");
					$("#tauname").popover('destroy');
					$("#tauname").popover({
						title:"<span class='text-info'>Please Enter your desired Username</span>",
						content:"Fill in the User Name you Desire. This will be used for Logging In",
						html:true,
						placement:"auto",
						trigger:"click hover focus"
						});
				}
			}
			,
			error:function(){
					$("#tauname").css("border-color","yellow");
					$("#tauname").css("border-style","solid");
					$("#tauname").popover('destroy');
					$("#tauname").popover({
						title:"<span class='text-danger'>OOPS!</span>",
						content:"Some error occured in contacting the server!",
						html:true,
						placement:"auto",
						trigger:"click hover focus"
						});
				}
			});
	}

	$(document).ready(function(){
		$("#tauname").popover({
			title:"Please Enter your desired Username",
			content:"Fill in the User Name you Desire. This will be used for Logging In",
			html:true,
			placement:"auto",
			trigger:"click hover focus"
			});

		$("#tauname").keyup(function(){
			var unm=$(this).val();
			checkunameavail(unm);
		});

		$("#tapass2").keyup(function(){
			if($(this).val()=="")
			{
				$(this).css("border-style","");
				$(this).css("border-color","");
				$(this).attr("title","Please Re-Enter the Password");
			}
			if($(this).val()!=$("#tapass1").val())
			{
				$(this).css("border-color","#FF0064");
				$(this).css("border-style","solid");
				$(this).attr("title","OOPS! The two passwords does not match!");
			}
			else
			{
				$(this).css("border-style","");
				$(this).css("border-color","");
				$(this).attr("title","Great! The two passwords match!");
			}
		});
	});
</script>

<script src='https://www.google.com/recaptcha/api.js'></script>