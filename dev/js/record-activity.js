var geolocation = [];

// When the page has loaded
$(function() {

	// If this is the record activity page
	if (document.location.pathname == "/record-activity/") {

		// Setup TomTom Maps For Web
		tomtom.setProductInfo('Runners High', '1');
		// Create a map for plotting points on
		var map = new tomtom.L.map('record_map', {
			key: 'KnsAaGwLdpHmAeEIqvGYOfQXTZxXczGx'
		}).locate({setView: true, maxZoom: 16});

		var d, date, start_time, finish_time, distance, duration;

		// Stopwatch credit: https://jsfiddle.net/Daniel_Hug/pvk6p/
		var time_display = document.getElementById('time_display'),
		  clear = document.getElementById('record_clear'),
		  seconds = 0, minutes = 0, hours = 0,
		  t;

		// Add a second to the counter
		function add() {
			// Iterate the second by 1
		  seconds++;
		  // Iterate the minutes by 1
		  if (seconds >= 60) {
		    seconds = 0;
		    minutes++;
		    // Iterate the hours by 1
		    if (minutes >= 60) {
		      minutes = 0;
		      hours++;
		    }
		  }
		  
		  // Update the time display
		  time_display.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

		  // Ask for the time
		  timer();
		}
		// Calling timer() starts the clock
		function timer() {
			// Run the timer every second
		  t = setTimeout(add, 1000);
		}

		/* Clear button */
		$('#record_clear').click(function() {

		  // Reset the timer display
		  time_display.textContent = "00:00:00";
		  // Reset the timer to 00:00:00
		  seconds = 0; minutes = 0; hours = 0;
		
		});

		// Not currently recording a run
		var recording = false;
		var locationTimer;

		// When the record button is clicked
		$('#record_btn').click(function() {

			// Toggle an active class
			$(this).toggleClass('active');

			if (!recording) {

				// Start recording
				recording = true;
				
				// Record today's date and start time
				d = new moment();
				date = d.format('YYYY/MM/DD');
				start_time = new moment();

				// Ask for the browser for the location every 5 seconds
				locationTimer = window.setInterval(getLocation, 5000);

				// Start the timer
				timer();
			
			} else {
			
				// Store the finish time and duration
				finish_time = new moment();
				duration = finish_time.diff(start_time);
				duration = moment.utc(duration).format('HH:mm:ss');
				//console.log(duration)

				// Stop the timer
				clearTimeout(t);
				recording = false;
				clearInterval(locationTimer);

				// This is the end of the run so calculate the distance
				getDistance();

				setTimeout(function() {
					
					$('#distance').text(distance + ' meters');
				
				}, 1000);
			
			}
		});

		// When the submit button is clicked
		$('#record_submit').click(function() {

			// If recording, stop recording and attempt to submit
			if (recording) $('#record_btn').click();

			// We've finished the run so ask TomTom to plot a route and calculate the distance
			getDistance();

			submit_activity.activity = document.getElementById('record_title').value;
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

		// Ask the browser for the current location
		function getLocation() {
			
			// Check if the browser has geolocation enabled
	    if (navigator.geolocation) {
	    	// Ask the browser for geolocation data and pass it to trackLocations()
	    	// If an error occurs pass the error to showError()
      	navigator.geolocation.getCurrentPosition(trackLocations, showError);
	    } else {
	    	alert("The browser has geolocation disabled");
        //console.log("Geolocation is not supported by this browser.");
	    }

		}

		// There are 4 difference types of error responses from the browser geolocation
		function showError(error) {
		  switch(error.code) {
		  	// If the user denied permission for geolocation
			  case error.PERMISSION_DENIED:
		      console.log("User denied the request for Geolocation.");
		      break;
		    // The browser is not reporting the position
			  case error.POSITION_UNAVAILABLE:
		      console.log("Location information is unavailable.");
		      break;
	      // The GPS request timed out
			  case error.TIMEOUT:
		      console.log("The request to get user location timed out.");
		      break;
				case error.UNKNOWN_ERROR:
					console.log("An unknown error occurred.");
					break;
		  }
		}

		var coords;
		var waypointCount = 0;
		var startPoint;
		var finishPoint;

		// Callback function to run when the browser has the current location
		// Store the realtime geolocation information and plot it on the map
		function trackLocations(coordinates) {

			// Store the position
			//console.log(coordinates);
			geolocation.push(coordinates.coords);
			// Add the timestamp
			let time = new Date(coordinates.timestamp);
			geolocation[geolocation.length-1].timestamp = time.toUTCString();
			
			// Iterate the waypoint count by 1
			waypointCount ++;

			// Settings for how the marker should look
			var markerOptions = {
		    icon: tomtom.L.icon({
		      iconUrl: '/tomtom/images/marker-black.png',
		      iconSize: [30, 34],
		      iconAnchor: [15, 34]
		    })
			};

			// Plot the point on the map
			tomtom.L.marker([geolocation[geolocation.length - 1].latitude, geolocation[geolocation.length - 1].longitude], markerOptions)
			.bindPopup("Waypoint: " + waypointCount).addTo(map);
		}

		// Requires network access
		function getDistance() {

			// Create a blank string
			var points = '';

			// Add the first waypoint to the string	
			points += geolocation[0].latitude + ',' + geolocation[0].longitude + ":";
			
			// Add the last waypoint to the string
			points += geolocation[geolocation.length-1].latitude + ',' + geolocation[geolocation.length-1].longitude;

			let supportingPoints = geolocation.slice(1,-1);

			// Send the geolocation to TomTom and ask for a calculated route
			// eg. points = '53.431320,-2.433381:53.433481,-2.429637';
			tomtom.routing().locations(points).supportingPoints(supportingPoints).go()
		  .then(function(routeGeoJson) {
		  	// routeGeoJson is TomTom's route
		  	var route = tomtom.L.geoJson(routeGeoJson, {
		    	style: {color: '#00d7ff', opacity: 0.6, weight: 6}
		    })
		    // Highlight the route on the map
		    .addTo(map);
				map.fitBounds(route.getBounds(), {padding: [5, 5]});
		  	console.log(routeGeoJson)
		  	// Store the distance of the route
		    distance = routeGeoJson.features[0].properties.summary.lengthInMeters;
		  });
		}

	}

});