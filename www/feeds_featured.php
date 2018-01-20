  <?php 
 $noecho="yes";
 require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
 $GLOBALS["feed_curcount"]=0;
 $totcol=2;
 $mypres_1=$mypres_2='';
 echo '<div class="row">';
 
 $socialobj=new ta_socialoperations();
 $msgobj=new ta_messageoperations();
 $userobj=new ta_userinfo();
 $userobj->userinit();
 
 $fres=$msgobj->get_threads_featured("0","7");
 
 for($i=0;$i<count($fres);$i++)
 {
 	$mytid=$fres[$i][changesqlquote(tbl_message_featured::col_tid,"")];
 	
 	$pres=require 'post_display.php';
 	if($totcol==2)
 	{
 		if($GLOBALS["feed_curcount"]%2==0)
 		{
 			$mypres_1.=$pres;
 		}
 		else
 		{
 			$mypres_2.=$pres;
 		}
 	}
 	
 	$GLOBALS["feed_curcount"]++;
 }

 
 echo '<div class="col-lg-6 col-md-4 col-sm-6 fdft_col1">'.$mypres_1.'</div>';
 echo '<div class="col-lg-6 col-md-4 col-sm-6 fdft_col2">'.$mypres_2.'</div>';
 
 if($GLOBALS["feed_curcount"]==0)
 {
 	echo 'Your Feed seems to be empty! Seems like there are no featured posts.';
 }
 
 echo ' </div>
 		<script type="text/javascript">
 		process_tarea($(".statusinput,.status-c-input"));
 		</script>		
 		';
 
 echo '<div align="center"><button class="fd_ldmore_featured btn btn-default" data-st="7" data-tot="8">Load More</button></div>';
 ?>
