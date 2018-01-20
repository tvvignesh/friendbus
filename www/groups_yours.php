	<?php 
	$noecho="yes";
	require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
	?>
	<!-- <div class="input-group margin-bottom-sm pull-left" style="width:250px;"> <input type="text" id="sbox_input" placeholder="Search for groups" class="form-control"> <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span> </div>
	<div class="pull-right">
		<select class="form-control" title="Sort the Groups">
			<option>Most Visited</option>
			<option>Rating (High to Low)</option>
			<option>Rating (Low to High)</option>
			<option>Ascending</option>
			<option>Descending</option>
		</select>
	</div>
	<div style="clear: both;"></div>-->
	<br>
	
	<ul class="list-group">
	
	<?php 
	$socialobj=new ta_socialoperations();
	$userobj=new ta_userinfo();
	$userobj->userinit();
	$uid=$userobj->uid;
	$gpmemres=$socialobj->getgroups($uid);
	for($i=0;$i<count($gpmemres);$i++)
	{
		$gpid=$gpmemres[$i][changesqlquote(tbl_members_attached::col_gpid,"")];
		$gpres=$socialobj->groups_get($gpid);
		$gpname=$gpres[0][changesqlquote(tbl_groups_info::col_gpname,"")];
		$gpno=$socialobj->group_get_nomem($gpid);
		echo '
			<li class="list-group-item">
			<a href="/social_groups.php?gpid='.$gpid.'">'.$gpname.'</a>
			<span class="pull-right label label-success">'.$gpno.' Members</span>
			<div style="clear: both;"></div>
		</li>
			';
	}
	
	if(count($gpmemres)==0)
	{
		echo 'Looks like there is nothing here to show.';
	}
	?>

	</ul>