<?php 
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$logobj=new ta_logs();
$utilityobj=new ta_utilitymaster();
$setobj=new ta_settings();

if(!$userobj->checklogin())
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/dash_settings.php",0,'/');
	echo 'Please <a href="/index.php">Login</a>';
	return;
}
$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

$userobj->userinit();

$proflink=$email_com='';
$setres=$setobj->setting_get($userobj->uid,"set_acct");
for($i=0;$i<count($setres);$i++)
{
	$skey=$setres[$i][changesqlquote(tbl_user_settings::col_subkey,"")];
	$sval=$setres[$i][changesqlquote(tbl_user_settings::col_setval,"")];
	if($skey=="proflink")$proflink=$sval;
	if($skey=="email_com")$email_com=$sval;
}
$uname=$userobj->uname;
?>

<div class="panel panel-default">
	<div class="panel-heading"><i class="fa fa-user"></i> Account Settings</div>
	<div class="panel-body">
	
		<ul class="list-group">
			<li class="list-group-item">
				Profile Link:  https://www.friendbus.com/users/ <a href="#"><?php echo '<a href="#" id="ta_set_edit_proflink">'.$proflink.'</a>'; ?></a>
			</li>
			
			<li class="list-group-item">
				Communication Email: <a href="#"><?php echo '<a href="#" id="ta_set_edit_comemail">'.$email_com.'</a>'; ?></a>
			</li>
			
			<li class="list-group-item">
				User Name: <a href="#"><?php echo '<a href="#" id="ta_set_edit_uname">'.$uname.'</a>'; ?></a>
			</li>
			
			<li class="list-group-item">
				<a href="/changepassword.php"><button class="btn btn-default">Change my Password</button></a>
			</li>
		</ul>
	</div>
	
</div>
					
<script type="text/javascript">
var utilityobj=new JS_UTILITY();

utilityobj.editable($("#ta_set_edit_proflink"),{
	mode:'inline',
	url:sender_editable,
	pk: '<?php echo $userobj->uid;?>',
	type: 'text',
    title: 'Enter Profile Link Key',
    name:'set_edit_proflink'
});

utilityobj.editable($("#ta_set_edit_comemail"),{
	mode:'inline',
	url:sender_editable,
	pk: '<?php echo $userobj->uid;?>',
	type: 'email',
    title: 'Enter Communication email address',
    name:'set_edit_comemail'
});

utilityobj.editable($("#ta_set_edit_uname"),{
	mode:'inline',
	url:sender_editable,
	pk: '<?php echo $userobj->uid;?>',
	type: 'text',
    title: 'Enter User Name',
    name:'set_edit_uname'
});
</script>