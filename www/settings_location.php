<div class="panel panel-default">
	<div class="panel-heading"><i class="fa fa-user"></i> Location Settings</div>
	<div class="panel-body">
	
		<div id="settingtbl_location_tbr" class="btn-group">
		    <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		        <i class="fa fa-refresh"></i> Reset all to Defaults
		    </button>
		</div>
	
		<table id="settingtbl_location">
		<thead>
	        <tr>
	            <th data-sortable="true" data-field="setting">Setting</th>
	            <th data-sortable="true" data-field="curstate">Current State</th>
	            <th data-sortable="true" data-field="manage" data-searchable="false">Manage</th>
	        </tr>
	    </thead>
		</table> 
	</div>
	
</div>
					
<script type="text/javascript">
var utilityobj=new JS_UTILITY();
var tbl=utilityobj.table($('#settingtbl_location'),{
    data: [{
    	setting: "Profile Link",
        curstate: '<a href="#">http://www.techahoy.in/users/tvvignesh</a>',
        manage: '<button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</button> <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>'
    },
    {
    	setting: "Email",
        curstate: '<a href="#">vigneshviswam@gmail.com</a>',
        manage: '<button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</button> <button type="button" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</button>'
    }],
    classes:'table table-no-bordered',
    search:true,
    pagination:true,
    pageList:'[10, 25, 50, 100, ALL]',
    showRefresh:true,
    showColumns:true,
    showToggle:true,
    showPaginationSwitch:true,
    sortable:true,
    mobileResponsive:true,
    striped:true,
    cache:true,
    detailView:true,
    detailFormatter:function(index, row) {
    	return 'test';
    },
    toolbar:'#settingtbl_location_tbr'
});
</script>