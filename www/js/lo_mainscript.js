$(document).ready(function(){
	load_elem_tour();
	//load_elem_notifications();
	load_elem_galtabs();
	load_elem_cbxtabs();
		    		    
$(".btn-pref .btn").click(function () {
    $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
    // $(".tab").addClass("active"); // instead of this do the below 
    $(this).removeClass("btn-default").addClass("btn-primary");   
});
	
	var utilityobj=new JS_UTILITY();
	utilityobj.checkbox($('input[type="checkbox"]'), {
		checkedClass: 'glyphicon glyphicon-ok'
	});

	
	listenevent($('.ga-tabs-cont ul li[data-toggle=collapse]'),'click',function(){
		$(this).toggleClass('active');
	});
	
	listenevent($('.list-group-item > .show-menu'),"click", function(event){
		event.preventDefault();
		$(this).closest('li').toggleClass('open');});
	
	$(document).prop('title', 'Friendbus');
 });

/*var utilityobj=new JS_UTILITY();
utilityobj.clippy('Merlin',function(agent){
	 agent.show();
	 agent.play('Greet');
	 agent.speak("How are you today? How can I help you?");
});*/
