function load_elem_galtabs()
{
	/*$("#galbox_cont_tabs").sortable({
	      placeholder: "ui-state-highlight",
	      axis: "x"
	    });*/
}

function load_elem_cbxtabs()
{
	var tabid="#cbx-tabcont";
	$(tabid).tabs({
		create:function(event,ui){
			if(window.location.hash=="")
			{
				window.location.hash=$(tabid+" ul li a:first").attr('href');
			}
		},
		beforeLoad: function( event, ui ) {
	        ui.jqXHR.fail(function() {
	          ui.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible.");
	        });
	      },
	    hide: {
	        effect: "fade",
	        duration: 500
	    },
	    show:{
			effect: "fade",
	        duration: 500
		},
		activate: function(event, ui) 
	   { 
	      window.location.hash= ui.newTab.context.hash;
	   }
	});
}

function load_elem_tour()
{
	var notifyobj=new JS_NOTIFICATIONS();
	tour=notifyobj.tour_gallery();

	tour.init();
	tour.start();
}

/*function load_elem_notifications()
{
	var notifyobj=new JS_NOTIFICATIONS();
	var stack_topleft = {"dir1": "down", "dir2": "right", "push": "top"};
	var stack_topright = {"dir1": "down", "dir2": "left", "push": "top"};
	var stack_bottomleft = {"dir1": "right", "dir2": "up", "push": "top"};
	var stack_custom = {"dir1": "right", "dir2": "down"};
	var stack_custom2 = {"dir1": "left", "dir2": "up", "push": "top"};
	var stack_bar_top = {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0};
	var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
	
	var notif=notifyobj.notification_push("123",{
	    title: 'Regular Notice',
	    text: 'Check me out! I\'m a notice.',
	    opacity: 0.9,
	    icon: 'glyphicon glyphicon-envelope',
	    width:'auto',
	    desktop: {
	        desktop: true
	    }
	},"1",stack_topright);
}*/