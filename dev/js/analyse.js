var tomtom;

var geolocation = document.getElementById('geolocation').textContent
geolocation = JSON.parse(geolocation)

$(function() {

	if( document.location.pathname == "/activity/analyse/") {

		tomtom.setProductInfo('Runners High', '1');
		var map = new tomtom.L.map('analyse_map', {
			key: 'KnsAaGwLdpHmAeEIqvGYOfQXTZxXczGx'
		});

		// Create a blank string
		var points = '';

		// Add the first waypoint to the string	
		points += geolocation[0].latitude + ',' + geolocation[0].longitude + ":";
		
		// Add the last waypoint to the string
		points += geolocation[geolocation.length-1].latitude + ',' + geolocation[geolocation.length-1].longitude;

		let supportingPoints = geolocation.slice(1,-1);

		// locations format = 'start lat','start long':'finish lat','finish long'
		tomtom.routing().locations(points).supportingPoints(supportingPoints)
	  .go()
	  .then(function(routeGeoJson) {
	  	var route = tomtom.L.geoJson(routeGeoJson, {
	    	style: {color: '#00d7ff', opacity: 0.6, weight: 6}
	    }).addTo(map);
			map.fitBounds(route.getBounds(), {padding: [5, 5]});

		})

	}

})