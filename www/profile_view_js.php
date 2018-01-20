<script type="text/javascript">

$(document).ready(function() {

	var utilityobj=new JS_UTILITY();
	
	var hashval=window.location.hash;
	if(hashval!="")
	{
		utilityobj.hashlisten();
	}
	
	listenevent($(".ta_s_addfriend"),"click",function(){
		var fuid=$(this).attr("data-fuid");
		var stat=$(this).attr("data-togtype");
		var mykey;
		if(stat=="1")
			mykey="s_addfriend";
		else
			mykey="s_removefriend";
			
		$.ajax({
		      method:"POST",
			  url: "/profile_edit_process.php",
			  dataType:"json",
			  data:{pk:'<?php echo $userobj->uid;?>',name:mykey,value:fuid},
			}).done(function(data) {
				if(data.returnval=="1"&&stat=="1")
				{
					$(".ta_s_addfriend").html('<i class="fa fa-times"></i> Cancel Request');
					$(".ta_s_addfriend").attr("data-togtype","2");
					$(".ta_s_addfriend").removeClass("btn-primary").addClass("btn-default");
					mykey="s_removefriend";
				}
				else
				if(data.returnval=="1"&&stat=="2")
				{
					$(".ta_s_addfriend").html('<i class="fa fa-user-plus"></i> Add Friend');
					$(".ta_s_addfriend").attr("data-togtype","1");
					$(".ta_s_addfriend").removeClass("btn-default").addClass("btn-primary");
					mykey="s_addfriend";
				}
				else
				{
					alert("OOPS! An error occured while sending friend request! Try Again! \n\n Reason: "+data.message);
				}
			}).fail(function( jqXHR, textStatus ) {
					alert("OOPS! Unable to send friend request at this time. Please Try Again later!");
				  console.log( "Request failed: " + textStatus );
			});
	});
	
});

var map;
function initMap() {
	var myLatLng = {lat: 21.1311084, lng: 82.7792231};
  map = new google.maps.Map(document.getElementById('map'), {
    center: myLatLng,
    zoom: 1
  });

  var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    title: 'Hello World!'
	  });
}
</script>