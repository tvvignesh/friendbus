  <?php 
 $noecho="yes";
 require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
 if(!isset($_GET["gpid"]))
 {
 	echo "Please select the group to view its posts!";return;
 }
 echo '<div class="row">';
 
 $gpid=$_GET["gpid"];
 $uiobj=new ta_uifriend();
 
 $colres=$uiobj->disp_group_post($gpid,"0","15");

 if($colres!=FAILURE)
 {
 	echo '<div class="col-lg-6 col-md-4 col-sm-6 gppost_col1">'.$colres["col1"].'</div>';
 	echo '<div class="col-lg-6 col-md-4 col-sm-6 gppost_col2">'.$colres["col2"].'</div>';
 }
 else
 {
 	echo 'Your Feed seems to be empty! Try adding some members to this group! You will be able to see when they make posts.';
 }
 
 echo ' </div>';
 
 echo '<div align="center"><button class="ldmore_gppost btn btn-default" data-gpid="'.$gpid.'" data-st="15" data-tot="10">Load More</button></div>';
 ?>