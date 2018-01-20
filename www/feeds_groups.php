  <?php 
 $noecho="yes";
 require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
 
 $dbobj=new ta_dboperations();
 $socialobj=new ta_socialoperations();
 $userobj=new ta_userinfo();
 $uiobj=new ta_uifriend();
 
 if(!$userobj->checklogin())
 {
 	echo 'Please <a href="/index.php">Login</a>';return;
 }
 
 $userobj->userinit();
 $mypres_1=$mypres_2='';
 
 if(isset($_GET["lvl"]))
 {
 	$lvl=$_GET["lvl"];
 }
 else
 {
 	$lvl=0;
 }

 echo '<div class="row">';
 
 $totcol=2;
 $GLOBALS["feed_curcount"]=0;
 $gpres=$socialobj->getgroups($userobj->uid);
 for($i=0;$i<count($gpres);$i++)
 {
 	$gpid=$gpres[$i][changesqlquote(tbl_members_attached::col_gpid,"")];
 	$gp=$socialobj->groups_get($gpid);
 	$gpname=$gp[0][changesqlquote(tbl_groups_info::col_gpname,"")];
 	
 	$pres=$uiobj->disp_group_post($gpid,$lvl,"1");
 	if($pres['col1']=="")continue;
 	
 	if($totcol==2)
 	{
 		if($GLOBALS["feed_curcount"]%2==0)
 		{
 			$mypres_1.='(<a href="/social_groups.php?gpid='.$gpid.'">'.$gpname.'</a>)'.$pres['col1'];
 		}
 		else
 		{
 			$mypres_2.='(<a href="/social_groups.php?gpid='.$gpid.'">'.$gpname.'</a>)'.$pres['col1'];
 		}
 		$GLOBALS["feed_curcount"]++;
 	}
 }
 

 echo '<div class="col-lg-6 col-md-4 col-sm-6 fd_gppost_col1">'.$mypres_1.'</div>';
 echo '<div class="col-lg-6 col-md-4 col-sm-6 fd_gppost_col2">'.$mypres_2.'</div>';
 
 if(count($gpres)==0)
 {
 	echo 'Looks like you are not a member of any group. You can create your own group <a href="/dash_groups.php">here</a>';
 }
 
 
 echo ' </div>';
 
 echo '<div align="center"><button class="fd_ldmore_gppost btn btn-default" data-lvl="1">Load More</button></div>';
 ?>