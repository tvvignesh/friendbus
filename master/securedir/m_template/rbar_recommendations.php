<?php
$noecho="yes";
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';

$userobj=new ta_userinfo();
$uobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$utilityobj=new ta_utilitymaster();
$msgobj=new ta_messageoperations();

if($userobj->checklogin())
{
	$userobj->userinit();
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	
}
else
{
	echo 'Please <a href="/index.php">Login</a> to view recommendations.';
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	return;
}
$recobj=new ta_recommendations();
$recres=$recobj->get_user_friend_rec($userobj->uid);
$recres_gp=$recobj->get_user_group_rec($userobj->uid);
?>

<span class="pull-right"><a href="/dash_contacts.php#cbx-tabs-suggestions">View More</a></span>
<b>People You May Know</b>
<div style="clear:both;"></div>
<ul class="list-group">

	<?php 
	$c=0;
		foreach($recres as $key=>$val)
		{
			$c++;
			if($c>10)break;
			$profpic=$utilityobj->pathtourl($uobj->getprofpic($key));
			$fullname=$uobj->user_getfullname($key);
			echo
			'
		<li class="list-group-item cbx-frcont-'.$key.'">
		<img src="'.$profpic.'" width="30" height="30"> <a href="/users.php?uid='.$key.'">'.$fullname.'</a>
		<div class="btn-group pull-right">
			<span class="s_fshipstat_'.$key.'" style="display:none;"></span>
			<button class="btn btn-sm btn-primary ajax-btn" data-mkey="s_togfriend" data-fuid="'.$key.'" data-suchide=".cbx-frcont-'.$key.'" data-sucfunc="notif_read" data-eltarget=".s_fshipstat_'.$key.'">
			Send Request
			</button>
			<button class="btn btn-default btn-sm ajax-btn" data-mkey="rec_ignore" data-fuid="'.$key.'" data-suchide=".cbx-frcont-'.$key.'" data-eltarget=".s_fshipstat_'.$key.'">Ignore</button>
		</div>
		<div style="clear:both;"></div>
	</li>
			';
		}
		
		if($c==0)
		{
			echo 'We have no recommendations yet. You will start seeing recommendations when you add atleast 1 friend on your own.';
		}
	?>
	
</ul>


<span class="pull-right"><!-- <a href="#">View More</a>--></span>
<b>Groups you may Like</b>
<div style="clear:both;"></div>
<ul class="list-group">


<?php 
$d=0;
foreach($recres_gp as $key=>$val)
{
	$d++;
	if($d>10)break;
	$gppic=$socialobj->group_get_pic($key);
	$gpres=$socialobj->groups_get($key);
	$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
	echo '
	<li class="list-group-item cbx-gpcont-'.$key.'">
	<img src="'.$gppic.'" width="30" height="30"> '.$gpname.'
	<div class="pull-right">
		<span class="s_gpstat_'.$key.'" style="display:none;"></span>
		<a href="/social_groups.php?gpid='.$key.'"><button class="btn btn-primary btn-sm">Visit</button></a>
		<button class="btn btn-default btn-sm ajax-btn" data-mkey="rec_ignore_gp" data-gpid="'.$key.'" data-suchide=".cbx-gpcont-'.$key.'" data-eltarget=".s_gpstat_'.$key.'">Ignore</button>
	</div>
	<div style="clear:both;"></div>
</li>
		';
}

if($d==0)
{
	echo 'We have no recommendations on groups yet!';
}
?>
</ul>

<!-- <b>More Recommendations</b>
<ul class="list-group">
	<li class="list-group-item"><i class="fa fa-map-marker"></i> Places you can Visit</li>
	<li class="list-group-item"><i class="fa fa-heart"></i> Like Minded People</li>
	<li class="list-group-item"><i class="fa fa-asterisk"></i> Products You May Want</li>
	<li class="list-group-item"><i class="fa fa-film"></i> Movies You Can Watch</li>
	<li class="list-group-item"><i class="fa fa-television"></i> Series You Can Watch</li>
	<li class="list-group-item"><i class="fa fa-music"></i> Songs You Can Hear</li>
	<li class="list-group-item"><i class="fa fa-briefcase"></i> Career You Can Pursue</li>
	<li class="list-group-item"><i class="fa fa-star"></i> Celebrities You May Like</li>
	<li class="list-group-item"><i class="fa fa-headphones"></i> Bands You May Like</li>
	<li class="list-group-item"><i class="fa fa-building"></i> Companies You May Like</li>
	<li class="list-group-item"><i class="fa fa-link"></i> Websites You May Like</li>
	<li class="list-group-item"><i class="fa fa-cutlery"></i> Food You May Like</li>
	<li class="list-group-item"><i class="fa fa-futbol-o"></i> Sports You May Like</li>
	<li class="list-group-item"><i class="fa fa-book"></i> Books You May Like</li>
	<li class="list-group-item"><i class="fa fa-life-ring"></i> Causes You Can Support</li>
	<li class="list-group-item"><i class="fa fa-money"></i> Investments You Can Do</li>
	<li class="list-group-item"><i class="fa fa-graduation-cap"></i> Things You Can Learn</li>
	<li class="list-group-item"><i class="fa fa-gamepad"></i> Games You May Like</li>
	<li class="list-group-item"><i class="fa fa-calendar"></i> Events You May Like</li>
	<li class="list-group-item"><i class="fa fa-question"></i> Mysteries You May Not Know</li>
	<li class="list-group-item"><i class="fa fa-user-secret"></i> Inspirations You May Admire</li>
	<li class="list-group-item"><i class="fa fa-paint-brush"></i> Art & Designs You May Like</li>
	<li class="list-group-item"><i class="fa fa-star-o"></i> Matches You May Like</li>
	<li class="list-group-item"><i class="fa fa-globe"></i> Needs You May Have</li>
</ul>-->