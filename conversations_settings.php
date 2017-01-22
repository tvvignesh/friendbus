<?php 
$noecho="yes";
require $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$userobj=new ta_userinfo();
if(!$userobj->checklogin())
{
	$utilityobj->enablebufferoutput();
	$utilityobj->outputbuffercont();
	die('Please <a href="/index.php">login</a> to access this section</div>');
}
$userobj->userinit();
?>

<ul class="list-group convbx_settings_lgp">

<!-- <li class="list-group-item">
Follow this Conversation?
<span class="pull-right">
<input type="checkbox" checked data-toggle="toggle" data-on="<i class='fa fa-check cbx_togglebx'></i> Yes" data-off="<i class='fa fa-times cbx_togglebx'></i> No" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>

<li class="list-group-item">
Allow participants to add users to this conversation?
<span class="pull-right">
<input type="checkbox" checked data-toggle="toggle" data-on="<i class='fa fa-check cbx_togglebx'></i> Yes" data-off="<i class='fa fa-times cbx_togglebx'></i> No" data-width="100" data-style="ios">
</span>
<div style="clear: both;"></div>
</li>-->

<li class="list-group-item">
Labels
<span class="pull-right">

<select class="cbx_settings_label" multiple="multiple">
<?php 
	$msgobj=new ta_messageoperations();
	$dbobj=new ta_dboperations();
	$tagres=$msgobj->tagpost_get($userobj->uid);
	for($i=0;$i<count($tagres);$i++)
	{
		$tagid=$tagres[$i][changesqlquote(tbl_tags_post::col_tagid,"")];
		$tagname=$tagres[$i][changesqlquote(tbl_tags_post::col_tagname,"")];
		echo '<option value="'.$tagid.'">'.$tagname.'</option>';
	}
	
?>
</select>

</span>
<div style="clear: both;"></div>
</li>


<li class="list-group-item">
Add New Users to this conversation
<span class="pull-right">
	<button class="btn btn-primary box-tog convbx_set_addp" data-mkey="box_participants_add" data-threadid="" data-eltarget="-1"><i class="fa fa-user-plus"></i> Add</button>
</span>
<div style="clear: both;"></div>
</li>


<!-- <li class="list-group-item">
Manage Participants & Previliges
<span class="pull-right">
	<button class="btn btn-primary"><i class="fa fa-users"></i> Manage</button>
</span>
<div style="clear: both;"></div>
</li>-->


</ul>

<script type="text/javascript">
var utilityobj=new JS_UTILITY();
var loadobj=new JS_LOADER();
var tid=$(".convbx_tabcont").attr("data-threadid");
var i,tagid;

loadobj.ajax_call({
	  url:"/request_process.php",
	  method:"POST",
	  data:{mkey:"tbx_getlbl",tid:tid},
	  dataType:"json",
	  cache:false,
	  success:function(data){
		  console.log("OPS");
		  console.log(data);
		  for(i=0;i<data.length;i++)
		  {
			  tagid=data[i];
			  console.log("LOOP TAG:"+tagid);
			  $('.cbx_settings_label option[value='+tagid+']').prop('selected', true);
			  $(".cbx_settings_label").select2({
					width:200
				});
		  }

		  $(".cbx_settings_label").change(function(){
				var newlabels=$(this).val();
				var nlbl=JSON.stringify(newlabels);
				loadobj.ajax_call({
					  url:"/request_process.php",
					  method:"POST",
					  data:{mkey:"tbx_editlbl",tid:tid,tagid:nlbl},
					  cache:false,
					  success:function(data){
					  }
				});
			});
	  }
});
	
utilityobj.toggle($('[data-toggle=toggle]'),{});

$(".convbx_set_addp").attr("data-threadid",tid);

function applycbox()
{
	utilityobj.checkbox($('.multiselect-container input[type="checkbox"]'), {
		checkedClass: 'glyphicon glyphicon-ok'
	});
}

setTimeout(applycbox,100);
</script>