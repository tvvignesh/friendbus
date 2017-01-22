<div align="center"><a class="btn btn-default box-tog" data-mkey="box_audience" data-toggle="modal" data-autoset="1" data-toggle="modal" data-pelem="cust_audience"><i class="fa fa-users"></i> Set Audience for Custom Feed</a></div>
<br><br>
   <div class="row">
   <?php 
   $ajaxchk=false;
   //require 'elem_feeditem.php';
   require 'user_customfeeds.php';
   
   echo '<div class="col-lg-6 col-md-4 col-sm-6 usrcfd_col1">'.$mypres_1.'</div>';
   echo '<div class="col-lg-6 col-md-4 col-sm-6 usrcfd_col2">'.$mypres_2.'</div>';
   
   
   ?>
  </div>
  
  <?php
  	echo '
   	<div align="center"><button class="btn btn-primary btn-lg usr_fdcustload_more" st="'.$GLOBALS["mys1"].'" tot="'.$GLOBALS["mye1"].'" lvl="'.$GLOBALS["myl1"].'">Load More</button></div>';
  ?>