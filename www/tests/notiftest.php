<html><head><title>WebSocket</title>
<style type="text/css">
html,body {
	font:normal 0.9em arial,helvetica;
}
#log {
	width:600px; 
	height:300px; 
	border:1px solid #7F9DB9; 
	overflow:auto;
}
#msg {
	width:400px;
}
</style>
<script type="text/javascript">
var socket;

function init() {
	var host = "ws://127.0.0.1:9000/echobot"; // SET THIS TO YOUR SERVER
	try {
		socket = new WebSocket(host);
		log('WebSocket - status '+socket.readyState);
		socket.onopen    = function(msg) { 
							   log("Welcome - status "+this.readyState); 
						   };
		socket.onmessage = function(msg) { 
							   log("Received: "+msg.data); 
						   };
		socket.onclose   = function(msg) { 
							   log("Disconnected - status "+this.readyState); 
						   };
	}
	catch(ex){ 
		log(ex); 
	}
	$("msg").focus();
}

function send(mykey){
	var msg;
	if(mykey==1)
	{
		msg={"uid":"uid","mtype":"3","msg":{},"target":"-1"};
	}
	
	try { 
		socket.send(msg); 
		log('Sent: '+msg); 
	} catch(ex) { 
		log(ex); 
	}
}
function quit(){
	if (socket != null) {
		log("Goodbye!");
		socket.close();
		socket=null;
	}
}

function reconnect() {
	quit();
	init();
}

// Utilities
function $(id){ return document.getElementById(id); }
function log(msg){ console.log(msg); }
function onkey(event){ if(event.keyCode==13){ send(); } }
</script>

</head>
<body onload="init()">
<h3>My WebSocket v2.00</h3>
<div id="log"></div>
<input id="msg" type="textbox" onkeypress="onkey(event)"/>
<button onclick="send(1);">Send Notification to Abhinav</button>
<button onclick="send(2);">Send Notification to Venkat</button>
<button onclick="quit()">Quit</button>
<button onclick="reconnect()">Reconnect</button>
</body>
</html>