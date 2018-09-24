var geolocation = [];

// When the page has loaded
$(function() {

	if (document.location.pathname == "/record-activity/") {

		tomtom.setProductInfo('Runners High', '1');
		var map = new tomtom.L.map('map', {
			key: 'KnsAaGwLdpHmAeEIqvGYOfQXTZxXczGx'
		}).locate({setView: true, maxZoom: 16});

		var d, date, start_time, finish_time, distance;

		// Stopwatch credit: https://jsfiddle.net/Daniel_Hug/pvk6p/
		var time_display = document.getElementById('time_display'),
		  clear = document.getElementById('clear'),
		  seconds = 0, minutes = 0, hours = 0,
		  t;

		function add() {
		  seconds++;
		  if (seconds >= 60) {
		    seconds = 0;
		    minutes++;
		    if (minutes >= 60) {
		      minutes = 0;
		      hours++;
		    }
		  }
		  
		  time_display.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

		  timer();
		}
		function timer() {
		  t = setTimeout(add, 1000);
		}

		/* Clear button */
		$('#record_clear').click(function() {
		  time_display.textContent = "00:00:00";
		  seconds = 0; minutes = 0; hours = 0;
		});

		// Not currently recording a run
		var recording = false;

		// When the record button is clicked
		$('#record_btn').click(function() {

			// Toggle an active class
			$(this).toggleClass('active');

			// Start recording
			if (!recording) {

				recording = true;
				
				// Record today's date and start time
				d = new moment();
				date = d.format('YYYY/MM/DD');
				start_time = new moment();
				//console.log(start_time)

				timer();
			
			} else {
			
				// Record the finish time and duration
				finish_time = new moment();
				duration = finish_time.diff(start_time);
				duration = moment.utc(duration).format('HH:mm:ss');
				//console.log(duration)
				// Stop recording
				clearTimeout(t);

				recording = false;

				getDistance();

				setTimeout(function() {
					$('#distance').text(distance + ' meters');
				}, 1000);
			
			}
		});

		$('#record_submit').click(function() {

			// If recording, stop recording and attempt to submit
			if (recording) $('#record_btn').click();
			getDistance();

			submit_activity.activity = document.getElementById('activity').value;
			submit_activity.date = date;
			submit_activity.distance = distance + ' meters';
			submit_activity.start_time = start_time.format('HH:mm:ss');
			submit_activity.finish_time = finish_time.format('HH:mm:ss');
			submit_activity.duration = duration;
			// Convert the geolocation array into a string
			submit_activity.waypoints = JSON.stringify(geolocation);

			console.log(submit_activity)
			submit_activity.submit();
		})

		function getLocation() {
		    if (navigator.geolocation && recording) {
		      	navigator.geolocation.getCurrentPosition(trackLocations, showError);
		    } else {
		        //console.log("Geolocation is not supported by this browser.");
		    }
		}

		function showError(error) {
		  switch(error.code) {
			  case error.PERMISSION_DENIED:
		      console.log("User denied the request for Geolocation.");
		      break;
			  case error.POSITION_UNAVAILABLE:
		      console.log("Location information is unavailable.");
		      break;
			  case error.TIMEOUT:
		      console.log("The request to get user location timed out.");
		      break;
				case error.UNKNOWN_ERROR:
					console.log("An unknown error occurred.");
					break;
		  }
		}

		var coords;
		var waypoint = [];
		var lastWaypoint;
		var startPoint;
		var finishPoint;

		function trackLocations(coordinates) {
			geolocation.push(coordinates['coords']);
			let waypointCount = waypoint.length + 1;
			waypoint.push('Waypoint ' + waypointCount)
			lastWaypoint = waypoint[waypoint.length - 1]
			console.log(lastWaypoint)
			console.log(geolocation)

			var markerOptions = {
		    icon: tomtom.L.icon({
		      iconUrl: '/tomtom/images/marker-black.png',
		      iconSize: [30, 34],
		      iconAnchor: [15, 34]
		    })
			};
			tomtom.L.marker([geolocation[geolocation.length - 1].latitude, geolocation[geolocation.length - 1].longitude], markerOptions).bindPopup(lastWaypoint).addTo(map);
		}

		function getDistance() {

			//startPoint = geolocation[0].latitude + ',' + geolocation[0].longitude;
			//finishPoint = geolocation[geolocation.length - 1].latitude + ',' + geolocation[0].longitude;
			//var points = '53.431320,-2.433381:53.433481,-2.429637';
			var points = '';

			// Loop through the waypoints
			for( var i = 0; i < geolocation.length; i ++ ) {
				points += geolocation[i].latitude + ',' + geolocation[i].longitude + ':'
			}

			points = points.replace(/\:$/, '');
			console.log(points)

			// locations format = 'start lat','start long':'finish lat','finish long'
			tomtom.routing().locations(points)
		  .go()
		  .then(function(routeGeoJson) {
		  	var route = tomtom.L.geoJson(routeGeoJson, {
		    	style: {color: '#00d7ff', opacity: 0.6, weight: 6}
		    }).addTo(map);
				map.fitBounds(route.getBounds(), {padding: [5, 5]});
		  	console.log(routeGeoJson)
		    distance = routeGeoJson.features[0].properties.summary.lengthInMeters;
		  });
		}

		window.setInterval(getLocation, 5000);

	}

});