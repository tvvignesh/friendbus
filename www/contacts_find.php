<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/filemaster.php';
$utilityobj=new ta_utilitymaster();
$userobj=new ta_userinfo();
$socialobj=new ta_socialoperations();
$uiobj=new ta_uifriend();

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
				  <li class="list-group-item">
				  	 <div class="checkbox_styled">
					  <input type="checkbox"> 
					  <label> Name/Nick: <input type="text" class="form-control" placeholder="Enter the person's name"> </label> 
					  </div>
				  </li>
				 </ul>
				 
				 <h4>Location</h4>
				 <ul class="list-group">
				  <li class="list-group-item">
				  		<div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Enable location filter: 
					  		<br>Staying at:
					  			<input type="text" class="form-control" placeholder="Enter the City Name"> 
					  			Work at:
					  			<input type="text" class="form-control" placeholder="Enter the City Name">
					  			Born at:
					  			<input type="text" class="form-control" placeholder="Enter the City Name">
					  			Schooling at:
					  			<input type="text" class="form-control" placeholder="Enter the City Name">
					  		</label> 
					  	</div>
				  </li>
				  </ul>
				  
				  <h4>Mutual Friends</h4>
				  <ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Enable filter: </label>
					  		
					  		<select class="form-control cbx_find_mutfrnd" name="cbx_find_mutfrnd[]" multiple>
							  	<option>Akon</option>
							  	<option>Anirudh</option>
							  	<option>A.R.Rahman</option>
							  	<option>Harris Jayaraj</option>
							  	<option>Yuvan Shankar Raja</option>
							  </select>
					</div>
				  </li>
				</ul>
				
				  <h4>Education</h4>
				  <ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Institution Name (School/College/Tuition): </label>
					  		
					  		<input type="text" class="form-control" placeholder="Enter institute name">
					</div>
				  </li>
				</ul>
				
				<h4>Work</h4>
				<ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Organization: </label>
					  		<input type="text" class="form-control" placeholder="Enter company or organization name">
					</div>
				  </li>
				</ul>
				
				<h4>Other Networks</h4>
				<ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Social Networking: </label>
					  		<select class="form-control cbx_find_othnetwork" name="cbx_find_othnetwork[]" title="Find contacts from other social networking websites" multiple>
							  	<option>Facebook</option>
							  	<option>Google Plus</option>
							  	<option>Linked In</option>
							  	<option>Twitter</option>
							  </select>
					</div>
				  </li>
				</ul>
				
				<h4>Age Range</h4>
				<ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox">
						  	<label> Filter by Age: </label> 
						  	<br>
					  		<label for="cbx_find_agerange"> Age Range: </label>
							  <input type="text" id="cbx_find_agerange" readonly>
							 
							<div id="cbx_find_agerange_range"></div>
					</div>
				  </li>
				</ul>
					
				<h4>Contact Lists</h4>
				<ul class="list-group">
				  <li class="list-group-item">
				  <div class="checkbox_styled">
						  	<input type="checkbox"> 
					  		<label> Filter by Lists: </label>
					  		<select class="form-control cbx_find_contactlists" name="cbx_find_contlists[]" multiple>
							  	<option>Friends</option>
							  	<option>Family</option>
							  	<option>Work</option>
							  </select>
					</div>
				  </li>
				</ul>
				
				
			</div>
			<div class="col-sm-6 col-md-8 col-lg-10">		  		 
		  		 <div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Find People</h3>
				  </div>
				<div class="panel-body">				 				  	
				  	
				  	
				  	<div class="row">
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Belongs to <span class="label label-success">Friends</span>
					  </div>
					</div>
					  </div>
					  
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Belongs to <span class="label label-primary">Family</span>
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Belongs to <span class="label label-success">Friends</span>
					  </div>
					</div>
					  </div>
					  
					  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 cbx-tabs-contfrnd">
					  <div class="media">
					  <div class="media-left">
					    <a href="#">
					      <img class="media-object" src="https://yt3.ggpht.com/-EP8xF7xkBNk/AAAAAAAAAAI/AAAAAAAAAAA/kZEUO1D53yE/s88-c-k-no/photo.jpg" alt="...">
					    </a>
					  </div>
					  <div class="media-body">
					    <h4 class="media-heading">T.V.Abhinav Viswanaath</h4>
					    150 Mutual Contacts
					    <br>
					    Belongs to <span class="label label-success">Friends</span>
					  </div>
					</div>
					  </div>
					  
					</div>
				  	
				  	
				</div>						
						<div class="panel-footer">200 Contacts in total, Filter Applied:Genre=Rock</div>
				  </div>
				</div>		  		 
			</div>
	
	<script type="text/javascript">
	var utilityobj=new JS_UTILITY();
	utilityobj.multiselect($('.cbx_find_mutfrnd'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true
		});
	
	utilityobj.multiselect($('.cbx_find_contactlists'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true
		});

	utilityobj.multiselect($('.cbx_find_othnetwork'),{
		buttonWidth: '150px',
		enableCaseInsensitiveFiltering: true,
		filterPlaceholder: 'Search',
		enableHTML:true
		})

	$( "#cbx_find_agerange_range" ).slider({
	      range: true,
	      min: 5,
	      max: 100,
	      values: [ 5, 100 ],
	      slide: function( event, ui ) {
	        $( "#cbx_find_agerange" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ]+" years" );
	      }
	    });
	    $( "#cbx_find_agerange" ).val( $( "#cbx_find_agerange_range" ).slider( "values", 0 ) +
	      " - " + $( "#cbx_find_agerange_range" ).slider( "values", 1 )+" years" );

	utilityobj.checkbox($('input[type="checkbox"]'),{
		checkedClass: 'glyphicon glyphicon-ok'
	});
	</script>
