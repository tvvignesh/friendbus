<?php 
	$noecho="yes";
	require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	
	$userobj=new ta_userinfo();
	if(!$userobj->checklogin())
	{
		echo 'Please <a href="/index.php">Login</a> to see notifications';return;
	}
	$userobj->userinit();
	
	$logobj=new ta_logs();
	$utilityobj=new ta_utilitymaster();
	$socialobj=new ta_socialoperations();
	$uiobj=new ta_uifriend();
	
?>


<ul class="list-group notify-ul">
<?php 
echo $uiobj->disp_notifications($userobj->uid,"0","10");
$n_tot_unread=$socialobj->notifications_getcount($userobj->uid);
echo '<script type="text/javascript">
$(document).ready(function(){
	$(".cnt-notif").html("'.$n_tot_unread.'");
	if($(".cnt-notif").html()=="0")
	{
		$(".cnt-notif").css("display","none");
	}
	else
	{
		$(".cnt-notif").css("display","block");
		$(".notification-none").css("display","none");
	}
});
</script>';
?>
</ul>

<div align="center"><button class="btn btn-default ld_notif_more" data-st="10" data-tot="10">Load More</button></div>