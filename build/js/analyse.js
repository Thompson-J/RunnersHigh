var tomtom;

var geolocation = document.getElementById('geolocation').textContent
geolocation = JSON.parse(geolocation)

$(function() {

	if( document.location.pathname == "/activity/analyse/") {

		tomtom.setProductInfo('Runners High', '1');
		var map = new tomtom.L.map('map', {
			key: 'KnsAaGwLdpHmAeEIqvGYOfQXTZxXczGx'
		});

		//startPoint = geolocation[0].latitude + ',' + geolocation[0].longitude;
		//finishPoint = geolocation[geolocation.length - 1].latitude + ',' + geolocation[0].longitude;
		//var points = '53.431320,-2.433381:53.433481,-2.429637';
		var points = '';

		// Loop through the waypoints
		for( var i = 0; i < geolocation.length; i ++ ) {
			points += geolocation[i].latitude + ',' + geolocation[i].longitude + ':'
		}

		points = points.replace(/\:$/, '');
		//console.log(points)

		// locations format = 'start lat','start long':'finish lat','finish long'
		tomtom.routing().locations(points)
	  .go()
	  .then(function(routeGeoJson) {
	  	var route = tomtom.L.geoJson(routeGeoJson, {
	    	style: {color: '#00d7ff', opacity: 0.6, weight: 6}
	    }).addTo(map);
			map.fitBounds(route.getBounds(), {padding: [5, 5]});
	  	//console.log(routeGeoJson)

		})

	}

})