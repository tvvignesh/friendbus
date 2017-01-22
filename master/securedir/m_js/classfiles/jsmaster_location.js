function JS_LOCATION(){};

window.locerror=0;

JS_LOCATION.prototype.locationinfo=function(pos){
	var obj={"latitude":pos.coords.latitude,"longitude":pos.coords.longitude,"altitude":pos.coords.altitude,"accuracy":pos.coords.accuracy,"altitudeAccuracy":pos.coords.altitudeAccuracy,"heading":pos.coords.heading,"speed":pos.coords.speed,"city":"","region":"","country":"","countrycode":""};
	return obj;
};

JS_LOCATION.prototype.error=function(error)
{
	switch(error.code)
	  {
	  case error.PERMISSION_DENIED:
		  window.locerror=1;
	    //alert("User denied the request for Geolocation.");
	    break;
	  case error.POSITION_UNAVAILABLE:
		  window.locerror=2;
		//alert("Location information is unavailable.");
	    break;
	  case error.TIMEOUT:
		  window.locerror=3;
	    //alert("The request to get user location timed out.");
	    break;
	  case error.UNKNOWN_ERROR:
		  window.locerror=4;
	    //alert("An unknown error occurred.");
	    break;
	  }
};

JS_LOCATION.prototype.getlocation=function(){
	if(google.loader.ClientLocation)
	{
	    visitor_lat = google.loader.ClientLocation.latitude;
	    visitor_lon = google.loader.ClientLocation.longitude;
	    visitor_city = google.loader.ClientLocation.address.city;
	    visitor_region = google.loader.ClientLocation.address.region;
	    visitor_country = google.loader.ClientLocation.address.country;
	    visitor_countrycode = google.loader.ClientLocation.address.country_code;
	    var obj={"latitude":visitor_lat,"longitude":visitor_lon,"altitude":"","accuracy":"","altitudeAccuracy":"","heading":"","speed":"","city":visitor_city,"region":visitor_region,"country":visitor_country,"countrycode":visitor_countrycode};
	    return obj;
	}
	else
	{
		if(navigator.geolocation)
		{
			var geoobj=new JS_LOCATION();
			navigator.geolocation.getCurrentPosition(geoobj.locationinfo,geoobj.error);
		}
		else
		{
			alert("OOPS! Geolocation feature is not supported by your browser.");
		}
	}
};