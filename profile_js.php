<script type="text/javascript">
function sender_editable(params)
{
    var d = new $.Deferred;
        $.ajax({
	      method:"POST",
		  url: "/profile_edit_process.php",
		  data:params,
		  dataType:"json",
		}).done(function(data) {
			if(data.returnval!=1)
			{
				return d.reject(data.message);
			}
			console.log(data);
			d.resolve();
		});
        return d.promise();
}

function rebinder_profile(itemkey)
{
	var utilityobj=new JS_UTILITY();
	switch(itemkey)
	{
		case "work_popover":
			utilityobj.editable($(".ta_edit_work_stime"),{
				type: 'text',
				mode:'inline',
			    title: 'Start Time of Your Work',
			    name:'work_stime',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_work_etime"),{
				type: 'text',
				mode:'inline',
			    title: 'End Time of Your Work',
			    name:'work_etime',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_work_notes"),{
				type: 'textarea',
				mode:'inline',
				rows: 2,
			    title: 'End Time of Your Work',
			    name:'work_notes',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_work_minsal"),{
				type: 'text',
				mode:'inline',
			    title: 'Your Minimum Salary',
			    name:'work_minsal',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_work_maxsal"),{
				type: 'text',
				mode:'inline',
			    title: 'Your Minimum Salary',
			    name:'work_maxsal',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_work_orgurl"),{
				type: 'url',
				mode:'inline',
			    title: 'Your Organization URL',
			    name:'work_orgurl',
			    pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});
			break;

		case "education_popover":
			utilityobj.editable($(".ta_edit_edu_stime"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'Start Time of your Education',
			    mode:'inline',
			    name:'edu_stime',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_edu_etime"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'End Time of your Education',
			    mode:'inline',
			    name:'edu_etime',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_edu_notes"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'Notes on your Education',
			    mode:'inline',
			    name:'edu_notes',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_edu_insturl"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'Notes on your Education',
			    mode:'inline',
			    name:'edu_insturl',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_edu_loc"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'Location of your Education',
			    mode:'inline',
			    name:'edu_loc',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});

			utilityobj.editable($(".ta_edit_edu_batchmate"),{
				type: 'text',
				pk: '<?php echo $userobj->uid;?>',
			    url:sender_editable,
			    title: 'Your Batchmates',
			    mode:'inline',
			    name:'edu_batchmate',
			    params:function(params){
			    	var elemid=$(this).attr("data-elemid");
			    	params["elemid"]=elemid;
			    	return params;
			    }
			});
		break;
	}
}

$(document).ready(function() {
	
	var hashval=window.location.hash;
	if(hashval!="")
	{
		var utilityobj=new JS_UTILITY();
		utilityobj.hashlisten();
	}

	var utilityobj=new JS_UTILITY();
	var loadobj=new JS_LOADER();
	
	loadobj.jsload_datetimepicker(function(){
		utilityobj.editable($("#ta_edit_dob"),{
			type: 'combodate',
			format:'YYYY-MM-DD',
			viewformat:'DD/MM/YYYY',
			template:'D / MMM / YYYY',
			pk: '<?php echo $userobj->uid;?>',
		    url:sender_editable,
		    name:'dob',
		    title: 'Select Date of Birth',
		    mode:'inline',
		    value:$("#ta_edit_dob").text()
		});
	});
	
	utilityobj.editable($("#ta_edit_gender"),{
		value: '<?php echo $userobj->gender;?>',
        source: [
              {value: 'm', text: 'Male'},
              {value: 'f', text: 'Female'},
              {value: 'o', text: 'Others'}
           ],
		type: 'select',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Select your Gender',
	    mode:'inline',
	    name:'gender'
	});

	utilityobj.editable($("#ta_edit_contactphone"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $userobj->uid;?>',
		type: 'text',
	    title: 'Enter phone number',
	    name:'phone'
	});
	
	utilityobj.editable($("#ta_edit_contactmobile"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $userobj->uid;?>',
		type: 'text',
	    title: 'Enter mobile number',
	    name:'mobile'
	});

	utilityobj.editable($("#ta_edit_fname"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $userobj->uid;?>',
		type: 'text',
	    title: 'Enter First Name',
	    name:'fname'
	});

	utilityobj.editable($("#ta_edit_mname"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $userobj->uid;?>',
		type: 'text',
	    title: 'Enter Middle Name',
	    name:'mname'
	});

	utilityobj.editable($("#ta_edit_lname"),{
		mode:'inline',
		url:sender_editable,
		pk: '<?php echo $userobj->uid;?>',
		type: 'text',
	    title: 'Enter Last Name',
	    name:'lname'
	});

	utilityobj.editable($("#ta_edit_contactemail"),{
		type: 'email',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Enter your email address',
	    mode:'inline',
	    name:'email'
	});

	utilityobj.editable($("#ta_edit_contactaddress"),{
		type: 'textarea',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Enter your address',
	    mode:'inline',
	    rows:3,
	    name:'address'
	});

	utilityobj.editable($("#ta_edit_aliases"),{
		type: 'textarea',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your aliases',
	    mode:'inline',
	    rows:3,
	    name:'aliases'
	});

	utilityobj.editable($("#ta_edit_relationshipstat"),{
		value: '<?php echo $userobj->extras->relstat;?>',    
        source: [
              {value: '1', text: 'Single'},
              {value: '2', text: 'Married'},
              {value: '3', text: 'In a Relationship'},
              {value: '4', text: 'Engaged'},
              {value: '5', text: 'It is Complicated'},
              {value: '6', text: 'Separated'},
              {value: '7', text: 'Divorced'},
              {value: '8', text: 'Widowed'}
           ],
		type: 'select',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Choose your Relationship Status from the list',
	    mode:'inline',
	    name:'relationship_stat'
	});
	
	utilityobj.editable($(".ta_edit_educelem"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Education',
	    mode:'inline',
	    name:'educelem',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($(".ta_edit_educeleminst"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Education',
	    mode:'inline',
	    name:'educinst',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($(".ta_edit_work"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Work',
	    mode:'inline',
	    name:'workelem',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($(".ta_edit_work_inst"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Work',
	    mode:'inline',
	    name:'workinst',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
		});
	
	utilityobj.editable($(".ta_edit_social"),{
		type: 'url',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Social Profile URL',
	    mode:'inline',
	    name:'socialurl',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($(".ta_edit_social_label"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Social Profile Label',
	    mode:'inline',
	    name:'sociallabel',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($("#ta_edit_politics"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your political view',
	    mode:'inline',
	    name:'politicalview'
	});

	utilityobj.editable($(".ta_edit_deviceelem"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Enter device name',
	    mode:'inline',
	    name:'devices',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});

	utilityobj.editable($("#ta_edit_scribbles"),{
		type: 'textarea',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Scribble',
	    mode:'inline',
	    rows:3,
	    inputclass:'ta_edit_scribbles_ip',
	    name:'scribbles'
	});
	
	utilityobj.editable($("#ta_edit_religion"),{
		source:"/item_getter.php",
		sourceOptions:{
			 data: {key:"religions"},
			 method:"POST",
			 dataType:"json"
		},
		type: 'select',
		pk: '<?php echo $userobj->uid;?>',
		value:'<?php echo $userobj->extras->religid?>',
	    url:sender_editable,
	    title: 'Your religion',
	    mode:'inline',
	    name:'religion',
	    inputclass:'prof_religip'
	});

	$('#ta_edit_religion').on('shown', function(e, editable) {
		utilityobj.multiselect($('.prof_religip'),{
			enableCaseInsensitiveFiltering: true,
			filterPlaceholder: 'Search'
		});
	});

	utilityobj.editable($("#ta_edit_pincode"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Enter pincode',
	    mode:'inline',
	    name:'pincode'
	});

	utilityobj.editable($(".ta_edit_achieveelem"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url:sender_editable,
	    title: 'Your Achievement',
	    mode:'inline',
	    name:'achievement',
	    params:function(params){
	    	var elemid=$(this).attr("data-elemid");
	    	params["elemid"]=elemid;
	    	return params;
	    }
	});
		
		utilityobj.editable($(".ta_edit_skillelem"),{
			type: 'text',
			pk: '<?php echo $userobj->uid;?>',
		    url:sender_editable,
		    title: 'Your Achievement',
		    mode:'inline',
		    name:'skills',
		    inputclass:'ta_edit_skillelem_ip'
		});

		$('.ta_edit_skillelem').on('shown', function(e, editable) {
			utilityobj.typeahead($(".ta_edit_skillelem_ip"),{
				source: function (query, process) {
					return $.ajax({
					      method:"POST",
						  url: "/item_getter.php",
						  data:{key:"skills",query:query},
						  dataType:"json",
						  success:function(data){
							  console.log(data);
							  return process(data);
							}
						});
		        },
		        minLength: 2
			});
		});
		
		$('#prof_skill_add').on('shown.bs.modal', function () {
			console.log("SHWN");
			utilityobj.typeahead($("#prof_skill_addip"),{
				source: function (query, process) {
					return $.ajax({
					      method:"POST",
						  url: "/item_getter.php",
						  data:{key:"skills",query:query},
						  dataType:"json",
						  success:function(data){
							  console.log(data);
							  return process(data);
							}
						});
		        },
		        minLength: 2
			});
		});
	
	utilityobj.editable($("#ta_edit_contactphone"),{
		type: 'text',
		pk: '<?php echo $userobj->uid;?>',
	    url: "#",
	    title: 'Enter phone number',
	    mode:'inline',
	    name:'phone'
	});

	listenevent($(".ta_newaddable"),"mouseenter",function(){
		if($(this).find(".btnadder").length==0)
		{
			$(this).append('<button class="btn btn-default btn-xs btnadder pull-right"><i class="fa fa-plus"></i></button>');
		}
		listenevent($(this),"mouseleave",function(){
			$(this).find(".btnadder").remove();
		});
		
	});

	listenevent($(".ta_profcog"),"mouseenter tap",function(){
		$(".dropdown.open").toggle();
		$(".dropdown.open").removeClass("open");
		var elem=$(this).attr("data-elemtype");
		if($(this).attr("data-elemid")!=""&&$(this).attr("data-elemid")!="undefined")
		{
			var elemid=$(this).attr("data-elemid");
		}
		else
		{
			var elemid=$(this).find("[data-elemid]:first").attr("data-elemid");
		}
		
		if($(this).find(".btnadder").length==0)
		{
			var coghtml='<div style="clear:both;" class="dropdown pull-right">'+
			'<button class="btn btn-default btn-xs btnpcog dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
			'<i class="fa fa-cog"></i></button><ul class="dropdown-menu">';

			if(elem=="prof_weburl"||elem=="prof_education"||elem=="prof_achievements"||elem=="prof_skills"||elem=="prof_work")
			{
				coghtml+='<li><a class="btnpcog_remove">Remove</a></li>';
			}
			
			coghtml+='<li><a class="btnpcog_privacy box-tog" data-toggle="modal" data-mkey="box_audience" data-autoset="1" data-pelem="prof_elem" data-elname="'+elem+'" data-elemid="'+elemid+'">Privacy</a></li>'+
			'</ul></div>';
			$(this).append(coghtml);
		}
		listenevent($(this),"mouseleave tap",function(){
			$(this).find(".btnpcog").remove();
		});
	});

	/*listenevent_future(".btnpcog",$("#prof_cont_enclose"),"click", function(){
		$(this).parents(".dropdown:first").dropdown('toggle');
	});*/
	
	listenevent_future(".btnpcog_remove",$("#prof_cont_enclose"),"click", function(){
		if(!confirm("Are you sure you want to delete this field?"))
		{
			return;
		}
		var elem=$(this).parents(".ta_profcog:first").attr("data-elemtype");
		var elemid=$(this).parents(".ta_profcog:first").find("[data-elemid]:first").attr("data-elemid");

		$.ajax({
		      method:"POST",
			  url: "/profile_remove_process.php",
			  dataType:"json",
			  data:{elemtype:elem,elemid:elemid,uid:'<?php echo $userobj->uid;?>'},
			}).done(function(data) {
				window.location.reload();
			}).fail(function( jqXHR, textStatus ) {
					alert("OOPS! Unable to submit data");
				  console.log( "Request failed: " + textStatus );
			});
	});

	listenevent($(".btnelemadder"),"click",function(){
		var randnum=Math.floor((Math.random() * 1000) + 1);
		$(this).parents(".panel-default:first").find(".list-group:first").append('<li class="list-group-item curaddedelem" data-addedelem="'+randnum+'"><select class="form-control"><option>Phone</option><option>Mobile</option><option>E-Mail</option></select> <input type="text" class="form-control" placeholder="Type in the value here"><br><button class="btn btn-default pull-right ta-rmv-profbtn" data-addedelem="'+randnum+'">Cancel</button> <button class="btn btn-primary pull-right">Submit</button><div style="clear:both;"></div></li>');
		listenevent($(".ta-rmv-profbtn"),"click",function(){
			var elemval=$(this).attr("data-addedelem");
			$(this).parent(".curaddedelem[data-addedelem="+elemval+"]").remove();
		});
	});

	listenevent($(".profile_newdata"),"click",function(){
		var myform=$(this).parents(".modal:first").find("form:first");
		var mydata=myform.serializeArray();
		var elemtype=$(this).attr("data-elemtype");
		mydata.push({name: "elemtype", value: elemtype});
		mydata.push({name: "uid", value: "<?php echo $userobj->uid;?>"});
		mydata=$.param(mydata);
		console.log(mydata);
		$.ajax({
		      method:"POST",
			  url: "/profile_add_process.php",
			  dataType:"json",
			  data:mydata,
			}).done(function(data) {
				window.location.reload();
			}).fail(function( jqXHR, textStatus ) {
					alert("OOPS! Unable to submit data");
				  console.log( "Request failed: " + textStatus );
			});
	});

	/*listenevent($("#ta_ppign"),"mouseover",function(){
		$(".profimg-uploader").css("display","block");
	});

	listenevent($("#ta_ppign"),"mouseleave",function(){
		$(".profimg-uploader").css("display","none");
	});*/

	utilityobj.editable($("#ta_edit_aliases"),{
		type: 'textarea',
		pk: '<?php echo $userobj->uid;?>',
	    url: '#',
	    title: 'Enter your aliases one per line',
	    mode:'inline',
	    rows:3
	});

	

	loadobj.jsload_select2(function(){
		
		$(".prof_lang").select2();
		listenevent_future(".prof_lang",$("body"),"change",function(){
			var lang=$(this).val();
			console.log(lang);
			var lang_s=JSON.stringify(lang);
			console.log(lang_s);

			loadobj.ajax_call({
				  url:"/request_process.php",
				  method:"POST",
				  data:{mkey:"prof_lang_edit",lang:lang_s},
				  cache:false,
				  success:function(data){					
				  }
			});
		});

		country_init($(".country_input"));
		state_init($(".country_input"),$(".state_input"));

		
		$(".prof_country_input").on("change",function(){
			var cntry=$(this).val();
			$.ajax({
			      method:"POST",
				  url: "/request_process.php",
				  data:{mkey:"prof_country_edit",cnt:cntry},
				  dataType:"json",
				  success:function(data){}
				});
		});

		$(".prof_state_input").on("change",function(){
			var state=$(this).val();
			$.ajax({
			      method:"POST",
				  url: "/request_process.php",
				  data:{mkey:"prof_state_edit",state:state},
				  dataType:"json",
				  success:function(data){}
				});
		});

		
	rebind_imgerror();

	});

	
});

var map;
function initMap() {
	var myLatLng = {lat: 21.1311084, lng: 82.7792231};
  map = new google.maps.Map(document.getElementById('map'), {
    center: myLatLng,
    zoom: 1
  });

  var infoWindow = new google.maps.InfoWindow({map: map});

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      infoWindow.setPosition(pos);
      infoWindow.setContent('Your current Location');
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
  
  /*var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    title: 'Hello World!'
	  });*/
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
	  infoWindow.setPosition(pos);
	  infoWindow.setContent(browserHasGeolocation ?
	                        'Unable to retrieve location! Try switching on the GPS in your device and allow access to your location for this website when prompted!' :
	                        'Error: Your browser doesn\'t support geolocation.');
	}
</script>
