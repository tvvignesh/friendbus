<div id="convbx_media_toolbar" class="btn-group">
    <button type="button" class="btn btn-default" title="Delete the selected contacts">
       <i class="fa fa-clipboard"></i> <span class="hidden-xs hidden-sm hidden-md">Copy to Gallery</span>
    </button>
    
    <button type="button" class="btn btn-default" title="Delete the selected contacts">
       <i class="fa fa-download"></i> <span class="hidden-xs hidden-sm hidden-md">Download</span>
    </button>
</div>
	   
	   
	<table id="convbx_media_tbl">
	<thead>
        <tr>
            <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
            <th data-sortable="true" data-field="id" data-width="75px">Sno.</th>
            <th data-sortable="true" data-field="fname">File Name</th>
            <th data-sortable="true" data-field="ftype">Type</th>
            <th data-sortable="true" data-field="fsize" data-width="100px">Size</th>
            <th data-sortable="true" data-field="fsharedate">Date Shared</th>
            <th data-sortable="true" data-field="fcontrols">Controls</th>
        </tr>
    </thead>
	</table>

<script type="text/javascript">
var utilityobj=new JS_UTILITY();
var tbl=utilityobj.table($('#convbx_media_tbl'),{
    data: [{
        id: 1,
        fname:'<span class="glyphicon glyphicon-picture"></span> Myfile.jpg',
        ftype:'Image (jpeg)',
        fsize:'200 KB',
        fsharedate:'4:00 PM on 30/12/1992',
        fcontrols:'<button type="button" class="btn btn-primary smallbtntxt" title="Open File"><i class="fa fa-eye"></i></button> <button type="button" class="btn btn-primary smallbtntxt" title="Download File"><i class="fa fa-download"></i></button> <button type="button" class="btn btn-primary smallbtntxt" title="More Info"><i class="fa fa-info-circle"></i></button> <button type="button" class="btn btn-primary smallbtntxt" title="Delete this"><i class="fa fa-trash"></i></button>'
        
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
    idForm:'convbx_media_tbl_sform',
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
    toolbar:'#convbx_media_toolbar'
});
</script>