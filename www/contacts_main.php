<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$logobj=new ta_logs();

if(!$userobj->checklogin())
{

	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	setcookie("returnpath",HOST_SERVER."/dash_contacts.php",0,'/');
}
else
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	$userobj->userinit();
	$uid=$userobj->uid;
}
?>		  			  		
<div class="row">
  	<div class="col-sm-6 col-md-4 col-lg-2">
		 <ul class="list-group">
			<li class="list-group-item active contacts_all" data-dtype="html" data-eltarget="#contact_mcontent" data-ddemand="html" data-mkey="contacts_load" data-elem="all" data-uid="<?php echo $userobj->uid;?>">All Contacts</li>
			
			<?php 
				$res=$socialobj->get_list_user($userobj->uid,"1");
					for($i=0;$i<count($res);$i++)
					{
						$listname=$res[$i][changesqlquote(tbl_listinfo::col_listname,"")];
						$listid=$res[$i][changesqlquote(tbl_listinfo::col_listid,"")];
						$listdesc=$res[$i][changesqlquote(tbl_listinfo::col_listdesc,"")];
						
						echo '<li class="list-group-item contacts_all" data-eltarget="#contact_mcontent" data-ddemand="html" data-dtype="html" data-mkey="contacts_load" data-elem="'.$listid.'" data-uid="'.$userobj->uid.'" title="'.$listdesc.'"><div style="width:85%;">'.$listname.'</div></li>';
					}
			?>
			
		</ul>
	</div>
	<div class="col-sm-6 col-md-8 col-lg-10" id="contact_mcontent">
<?php 
$elem="all";
require_once 'contacts_all.php';
?>
	</div>		  		 
</div>
