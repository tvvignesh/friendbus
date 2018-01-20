	<div align="center">
	   
	   <div class="well well-sm"> 
	   	<h3 class="pull-left">Calling: <span class="text-success">044-2224-3697</span></h3>
	   	<span class="pull-right bg-primary" style="padding:15px;">
	   		Duration: 00:00:00
	   	</span>
	   	<div style="clear:both;"></div>
		</div>
		
<div id="convbx_call_toolbar" class="btn-group">
    <button type="button" class="btn btn-default" title="Delete the selected contacts">
       <i class="fa fa-trash"></i> Delete History
    </button>
</div>
	   
	   
	<table id="convbx_audcall_tbl">
	<thead>
        <tr>
            <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
            <th data-sortable="true" data-field="id" data-width="75px">Sno.</th>
            <th data-sortable="true" data-field="medium">Medium</th>
            <th data-sortable="true" data-field="cnumber">Contact Number</th>
            <th data-sortable="true" data-field="ccharge" data-width="100px">Charge/min</th>
            <th data-sortable="true" data-field="ccustname">Label</th>
            <th data-sortable="true" data-field="clastcall">Last Called</th>
            <th data-sortable="true" data-field="ccall" data-searchable="false">Call</th>
        </tr>
    </thead>
	</table>
	   
   </div>
   
<script type="text/javascript">
	var utilityobj=new JS_UTILITY();
	var tbl=utilityobj.table($('#convbx_audcall_tbl'), {
	    data: [{
	        id: 1,
	        medium:'Landline',
	        cnumber:'044-22243697',
	        ccharge:'0.2 $',
	        ccustname:'Work Phone',
	        clastcall:'4:00 PM on 30/12/1992',
	        ccall:'<button type="button" class="btn btn-primary"><i class="fa fa-phone"></i> Call</button>'
	    },
	    {
	        id: 2,
	        medium:'Mobile',
	        cnumber:'+91-9535679593',
	        ccharge:'0.5 $',
	        ccustname:'Peronal Phone',
	        clastcall:'9:00 PM on 30/12/1992',
	        ccall:'<button type="button" class="btn btn-primary"><i class="fa fa-phone"></i> Call</button>'
	    }
	    ],
	    classes:'table table-no-bordered',
	    search:true,
	    pagination:true,
	    pageList:'[10, 25, 50, 100, ALL]',
	    idField:'id',
	    uniqueId:'id',
	    showRefresh:true,
	    showColumns:true,
	    showToggle:true,
	    showExport:true,
	    advancedSearch:true,
	    idForm:'convbx_audcall_tbl_sform',
	    exportTypes:['pdf','json', 'xml', 'csv', 'txt', 'sql', 'excel'],
	    exportDataType:'all',
	    showPaginationSwitch:true,
	    sortable:true,
	    striped:true,
	    sortName:'id',
	    sortOrder:'asc',
	    cache:true,
	    mobileResponsive:true,
	    detailView:true,
	    detailFormatter:function(index, row) {
	    	return 'test';
	    },
	    selectItemName:'state',
	    clickToSelect:true,
	    toolbar:'#convbx_call_toolbar'
	});
</script>