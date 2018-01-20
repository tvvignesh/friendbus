<div id="galbox_trash_toolbar" class="btn-group">
    <button type="button" class="btn btn-default gtr_remtrbtn" title="The files checked will be restored to their original locations">
       <span class="glyphicon glyphicon-repeat"></span> Restore
    </button>
    <button type="button" class="btn btn-default gtr_deltrbtn" title="The files checked will permanently be deleted">
       <span class="glyphicon glyphicon-remove"></span> Delete Permanently
    </button>
</div>

	<table id="galbox_trash">
	<thead>
        <tr>
            <th data-field="state" data-checkbox="true" data-formatter="stateFormatter"></th>
            <th data-sortable="true" data-field="id" data-width="75px">Sno.</th>
            <th data-sortable="true" data-field="name">File Name</th>
            <th data-sortable="true" data-field="type">Type</th>
            <th data-sortable="true" data-field="size">Size</th>
            <th data-sortable="true" data-field="date">Date Deleted</th>
        </tr>
    </thead>
	</table>
	
<script type="text/javascript">
var utilityobj=new JS_UTILITY();
utilityobj.table($('#galbox_trash'),{
    data: [{
        id: 1,
        name: '<span class="glyphicon glyphicon-picture"></span> Myfile.jpg',
        type: 'Image (jpeg)',
        size:'200 KB',
        date:'30/12/1992'
    }, {
    	id: 2,
        name: '<span class="glyphicon glyphicon-facetime-video"></span> Mybday.mov',
        type: 'Video (mov)',
        size:'200 KB',
        date:'30/12/1992'
    }],
    classes:'table table-no-bordered',
    search:true,
    pagination:true,
    pageList:'[10, 25, 50, 100, ALL]',
    idField:'id',
    uniqueId:'id',
    showRefresh:true,
    showColumns:true,
    showToggle:true,
    mobileResponsive:true,
    showPaginationSwitch:true,
    sortable:true,
    striped:true,
    sortName:'id',
    sortOrder:'asc',
    cache:true,
    detailView:true,
    detailFormatter:function(index, row) {
    	return 'test';
    },
    selectItemName:'state',
    clickToSelect:true,
    toolbar:'#galbox_trash_toolbar'
});

listenevent($(".gtr_remtrbtn"),"click",function(){
	var ids = $.map($('#galbox_trash').bootstrapTable('getSelections'), function (row) {
        return row.id;
    });
	$('#galbox_trash').bootstrapTable('remove', {
        field: 'id',
        values: ids
    });
});

//TODO
listenevent($(".gtr_deltrbtn"),"click",function(){
	var ids = $.map($('#galbox_trash').bootstrapTable('getSelections'), function (row) {
        return row.id;
    });
	$('#galbox_trash').bootstrapTable('remove', {
        field: 'id',
        values: ids
    });
});
</script>