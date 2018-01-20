   <div class="row">
   <?php 
   $ajaxchk=false;
   //require 'elem_feeditem.php';
   require 'user_feeds.php';
   
   echo '<div class="col-lg-6 col-md-4 col-sm-6 usrfd_col1">'.$mypres_1.'</div>';
   echo '<div class="col-lg-6 col-md-4 col-sm-6 usrfd_col2">'.$mypres_2.'</div>';
   
   
   ?>
  </div>
  
  <?php
  	echo '<div align="center"><button class="btn btn-primary btn-lg usr_fdload_more" st="'.$GLOBALS["mys"].'" tot="'.$GLOBALS["mye"].'" lvl="'.$GLOBALS["myl"].'">Load More</button></div>';
  ?>