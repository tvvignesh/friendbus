<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();
$galobj=new ta_galleryoperations();

$utilityobj->enablebufferoutput();
$utilityobj->outputbuffercont();

if(!$userobj->checklogin())
{
	setcookie("returnpath",HOST_SERVER."/dash_gallery.php",0,'/');
}
else
{
	$userobj->userinit();
	$uid=$userobj->uid;
}
$galid_vault=$galobj->get_galid_special($userobj->uid,"14");
?>

<div id="galbox_vault_toolbar" class="btn-group">

<div class="dropdown">
    <button type="button" class="btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="gva_addvabtn">
        <span class="glyphicon glyphicon-plus"></span> Add to Vault
    </button>
    
    <ul class="dropdown-menu bullet" aria-labelledby="gva_addvabtn">
    
    <?php 
    	echo '<li><a class="gbx_va_upld box-tog" data-mkey="box_fileupload" data-toggle="modal" data-eltarget="-1" data-galid="'.$galid_vault.'">Upload from device</a></li>';
    ?>
			<li><a href="#2">Attach from Gallery</a></li>
			<li><a href="#3">Add from external source</a></li>
	</ul>
</div>
    <button type="button" class="btn btn-default gva_remvabtn" title="The files checked will be removed from vault but will be available in the gallery">
       <span class="glyphicon glyphicon-minus-sign"></span> Delete
    </button>
    <!-- <button type="button" class="btn btn-default gva_delvabtn" title="The files checked will permanently be deleted">
       <span class="glyphicon glyphicon-remove"></span> Delete Permanently
    </button>-->
</div>

	<table id="galbox_vault">
	<thead>
        <tr>
            <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
            <th data-sortable="true" data-field="sno" data-width="75px">Sno.</th>
            <th data-sortable="true" data-field="name">File Name</th>
            <th data-sortable="true" data-field="type">Type</th>
            <th data-sortable="true" data-field="size">Size</th>
            <th data-sortable="true" data-field="date">Date Added</th>
        </tr>
    </thead>
	</table> 
	
	
	<script type="text/javascript">
	var utilityobj=new JS_UTILITY();

	<?php 
		$res=$galobj->get_children_media($galid_vault);
		$mydata='var mydata=[];';
		for($i=0;$i<count($res);$i++)
		{
			$medid=$res[$i][changesqlquote(tbl_galdb::col_mediaid,"")];
			$medname=$res[$i][changesqlquote(tbl_galdb::col_mediatitle,"")];
			$medext=$res[$i][changesqlquote(tbl_galdb::col_fext,"")];
			$medurl=$res[$i][changesqlquote(tbl_galdb::col_mediaurl,"")];
			$meduid=$res[$i][changesqlquote(tbl_galdb::col_mediauid,"")];
			$meddesc=$res[$i][changesqlquote(tbl_galdb::col_mediadesc,"")];
			$medtime=$res[$i][changesqlquote(tbl_galdb::col_mediatime,"")];
			$medgalid=$res[$i][changesqlquote(tbl_galdb::col_galid,"")];
			
			$mydata.='
				mydata.push({
						id:"'.$medid.'",
						sno:"'.($i+1).'",
						name:"'.$medname.'",
						type:"'.$medext.'",
						size:"'.$utilityobj->formatBytes(filesize($medurl)).'",
						date:"'.$medtime.'",
						url:"'.$utilityobj->pathtourl($medurl).'",
						galid:"'.$medgalid.'"
				});
			';
		}
		echo $mydata;
	?>
	
	var tbl=utilityobj.table($('#galbox_vault'),{
	    data: mydata,
	    classes:'table table-no-bordered',
	    search:true,
	    pagination:true,
	    pageList:'[10, 25, 50, 100, ALL]',
	    idField:'id',
	    uniqueId:'id',
	    showRefresh:true,
	    showColumns:true,
	    showToggle:true,
	    showPaginationSwitch:true,
	    sortable:true,
	    mobileResponsive:true,
	    striped:true,
	    sortName:'id',
	    sortOrder:'asc',
	    cache:true,
	    detailView:true,
	    detailFormatter:function(index, row) {
		    var ret='';
		    ret+='File ID:'+row.id;
		    ret+='<br>File Download: <a href="'+row.url+'">Download</a>';
	    	return ret;
	    },
	    selectItemName:'state',
	    clickToSelect:true,
	    toolbar:'#galbox_vault_toolbar'
	});

	listenevent($(".gva_delvabtn"),"click",function(){
		var ids = $.map($('#galbox_vault').bootstrapTable('getSelections'), function (row) {
            return row.id;
        });
		$('#galbox_vault').bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
	});
	/*<li><a class="ajax-btn" data-mkey="gbx_vid_del" data-mediaid="'.$mediaid.'" data-galid="'.$galid.'" data-suchide=".gbx-vid-thumb-'.$mediaid.'"><i class="fa fa-trash"></i> Delete Video</a></li>*/
	listenevent($(".gva_remvabtn"),"click",function(){
		if(!confirm("Are you sure?"))
		{
			return;
		}
		var ids = $.map($('#galbox_vault').bootstrapTable('getSelections'), function (row) {
			var predata={};
			predata.mkey="gbx_va_del";predata.mediaid=row.id;predata.galid=row.galid;
			ajax_sender(undefined,predata);
			console.log(row);
            return row.id;
        });
		$('#galbox_vault').bootstrapTable('remove', {
            field: 'id',
            values: ids
        });
	});
	
	</script>