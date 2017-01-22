<div id="gv-tabs-stats">
   <canvas id="gv_stats_graph" width="400" height="400"></canvas>
</div>

<script type="text/javascript">
var loadobj=new JS_LOADER();
loadobj.jsload_chart(load_elem_vidchart);

function loadstats_galvid()
{
	var data = [
	            {
	                value: 300,
	                color:"#F7464A",
	                highlight: "#FF5A5E",
	                label: "Red"
	            },
	            {
	                value: 50,
	                color: "#46BFBD",
	                highlight: "#5AD3D1",
	                label: "Green"
	            },
	            {
	                value: 100,
	                color: "#FDB45C",
	                highlight: "#FFC870",
	                label: "Yellow"
	            },
	            {
	                value: 40,
	                color: "#949FB1",
	                highlight: "#A8B3C5",
	                label: "Grey"
	            },
	            {
	                value: 120,
	                color: "#4D5360",
	                highlight: "#616774",
	                label: "Dark Grey"
	            }
	
	        ];

		var ctx = document.getElementById("gv_stats_graph").getContext("2d");
		Chart.defaults.global.responsive = true;
		var myNewChart = new Chart(ctx).PolarArea(data);
}

function load_elem_vidchart()
{
	$("#galbox_vidguidetab").on( "tabsactivate", function( event, ui ) {
		loadstats_galvid();
	});
}
</script>