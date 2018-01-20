<?php

include_once ('config.php');
require_once ('Yahoo.inc');
$session = YahooSession::requireSession($consumer_key,$consumer_secret,$app_id);


if ($_GET['flag']=='sendMail'){
	foreach($_POST['check'] as $email){
		$to_mail = $email;
		//SEND YOUR MAIL	
	}
}	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="robots" content="noindex" />
<title>Invite Friends From Yahoo</title>

<style type="text/css">
body{
	 margin:0;
	 padding:0;
	 font-family:Arial, Helvetica, sans-serif;
	 font-size:13px;
	 background-color: #FFFFFF;
}
.number{
	font-size: 60px;
	font-family:Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #E1AE24;
}
.msg{
	font-size: 12px;
	font-family:Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #E1AE24;
}

.delete a{
	font-size: 12px;
	font-family:Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #E1AE24;
	text-decoration: none;
}
.text{
	color: #333333;
    font-size: 12px;
    font-weight: normal;
    line-height: 17px;
}

.text a{
	color: #333333;
    font-size: 12px;
    font-weight: normal;
    line-height: 17px;
    text-decoration: underline;
}

.text a:hover{
	color: #333333;
    font-size: 12px;
    font-weight: normal;
    line-height: 17px;
    text-decoration: none;
}

.errormsg{
	color: #FF0000;
    font-size: 12px;
    font-weight: normal;
    line-height: 17px;
}
</style>

<script type='text/javascript'>
	function toggleAll(element) 
	{
	var form = document.forms.form1, z = 0;
	for(z=0; z<form.length;z++)
		{
		if(form[z].type == 'checkbox')
			form[z].checked = element.checked;
	   	}
	}
</script>
    
</head>
<body>
<form method="POST" name="form1" action="?flag=sendMail" enctype="multipart/form-data">
<div align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="contain_connected_name" bgcolor="#C0C0C0" height="40" style="padding-left: 10px; padding-top: 5px; padding-bottom: 5px" align="left">&nbsp;</td>
</tr>
<?php
	if($_GET['msg']==""){
?>
	<tr>
		<td height="40" style="padding-left: 20px" align="left"><b>&nbsp;Enter Your Message</b></td>
	</tr>
	<tr>
		<td align="center"><textarea rows="6" name="S1" cols="56" style="width:400px"></textarea>
		<input type='hidden' name='step' value='send_invites' id="step">
</td>
	</tr>
	<tr>
		<td align="center" style="padding-top: 10px"><input type="submit" value="Invite Friends" name="B3"> </td>
	</tr>
<?php
}else{
?>	
	<tr>
		<td align="center" height="200" style="font-family: Verdana; font-size: 9pt; color: #FF0000; font-weight: bold"><?php print $_GET['msg']; ?></td>
	</tr>
<?php } ?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<?php
			if($_GET['msg']==""){
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100" align="center" bgcolor="#041338" height="28">
				<input type='checkbox' onChange='toggleAll(this)' name='toggle_all' title='Select/Deselect all' checked></td>
				<td bgcolor="#041338" height="28" align="left"><font color="#FFFFFF"><b>Email</b></font></td>
			</tr>
			<?php
				if (is_object($session)){
					$user = $session->getSessionedUser();
					$profile = $user->getProfile();
					$name = $profile->nickname; // Getting user name
					$guid = $profile->guid; // Getting Yahoo ID
					$contacts=$user->getContacts()->contacts;
				
					//echo "Hi! ".$name."<br />";
					
					for ($i=0,$k=0;$i<count($contacts->contact);$i++){
						for($j=0;$j<count($contacts->contact[$i]->fields);$j++){
							$url_data = $contacts->contact[$i]->fields[$j]->uri;
							$url_pie=explode("/user/", $url_data);
							$url_end=substr($url_pie[1], stripos($url_pie[1], "/")+1);
							$data=explode("/", $url_end);
								if ($data[2]==="email"){
									$email_fr[$k]=$user->getDatafrom($url_end)->email->value;
									$k++;
								}
						}
					}
					
					//echo "You have ".$k." contacts.<br />";
					for($i=0;$i<$k;$i++){			
					?>

			<tr>
				<td width="100" align="center" height="26">
				<input name='check[]' value='<?php echo $email_fr[$i]; ?>' type='checkbox' checked></td>
				<td height="26" align="left"><?php echo $email_fr[$i]; ?></td>
			</tr>
			<?php 
				}
					}
					else
					{
					//header("Location :".$re_url);
					
					print "No Email Found!";
					}
			?>
			<tr>
				<td width="100" align="center" style="padding-top: 10px">&nbsp;</td>
				<td style="padding-top: 10px" align="left"><input type="submit" value="Invite Friends" name="B4"> </td>
			</tr>
		</table>
		<?php
		}
		?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
</div>
</form>

</body></html>