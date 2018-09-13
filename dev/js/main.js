var homeurl = window.location.origin + "/";

// Modaal
$('.inline').modaal({
	before_open: function() {
		// Close the nav sidebar by removing the active class
		$('#site-wrapper, .sidenav, .burger-button, .burger-button div').removeClass('active');
		$('#site-wrapper').addClass('modal-open');
	},
	before_close: function() {
		$('#site-wrapper').removeClass('modal-open');
	}
});
$('.modaal-ajax').modaal({
    type: 'ajax'
});

var cookieUsed = false;
var updateActivity;

function loginCheck() {

	$.ajax({
		url: homeurl + "includes/process_login.php",
		method: "GET",
		dataType: "json",
		success: function(response) {

			//console.log("logged in");
			activity(response);
			return true;

		},
		error: function() {

			//console.log("auto login failed");
			//promptLogin();
			return false;

		}
	});

}

// Do these things after loading the page
$(document).ready(function() {

	// Burger menu button
	$(".burger-button").click(function() {

		$('.sidenav, .burger-button, .burger-button div').toggleClass('active');

	});

	// Read the login cookie and enter details into the form fields
	if ((Cookies.get("email") != undefined) && (Cookies.get("passwordhash") != undefined)) {

		$("#email").val(Cookies.get("email"));
		$("#password").val(Cookies.get("passwordhash"));
		cookieUsed = true;
		$("#remember-user").prop("checked", true);
		//console.log("used a cookie");

	};

	// Table sort
	$("table:not(#leaderboard)").each(function(){

		//console.log(this);

		$(this).find('th').each(function(col) {

			$(this).click(function() {

				$(this).parents('table').find('col').removeClass('selected');
				$(this).parents('table').find('col').eq(col).addClass('selected');

				if ($(this).is('.asc')) {

					$(this).removeClass('asc');
					$(this).addClass('desc selected');
					sortOrder = -1;
				
				} else {
				
					$(this).addClass('asc selected');
					$(this).removeClass('desc');
					sortOrder = 1;
				
				}
				
				$(this).siblings().removeClass('asc selected');
				$(this).siblings().removeClass('desc selected');

				// Make an array of the contents of a clicked column
				var arrData = $(this).parents('table').find('tbody > tr:has(td)').get();

				// Sort this array
				arrData.sort(function(a, b) {

					var val1, val2;

					// For each time/ date cell (a and b) set variables the datetime attribute value e.g 2018:01:31, 00:57:02
					$(a).children('td').eq(col).children('time').each(function() {

						val1 = $(this).attr('datetime');

					});
					$(b).children('td').eq(col).children('time').each(function() {

						val2 = $(this).attr('datetime');

					});

					//console.log(a, b);

					// 
					val1 = $(a).children('td').eq(col).text().toUpperCase();
					val2 = $(b).children('td').eq(col).text().toUpperCase();
					if ($.isNumeric(val1) && $.isNumeric(val2))
						return sortOrder == 1 ? val1 - val2 : val2 - val1;
					else
						return (val1 < val2) ? -sortOrder : (val1 > val2) ? sortOrder : 0;
				});
				//$(table + ' tbody tr').remove()
				$.each(arrData, function(index, row) {
					$(this).parents('tbody').append(row);
				});

			});

		});

		/* $('#modal-activity').change(function() {	

			if ($(this).prop('checked') === true) loginCheck();

		}); */

	});

	leaderboard();
	

});

// Check the login details with the database
function retrieveActivity() {

	//window.alert("hello world");
	var email = document.getElementById("email").value;
	var password = document.getElementById("password").value;
	//console.log(password);

	// Check the user typed an email address
	if (email != "" && password != "") {

		// Hash the password before sending it.
		if (!cookieUsed) password = hex_sha512(password);
		//console.log(p);

		// AJAX communication with login database
		$.ajax({
			url: homeurl + "includes/process_login.php",
			type: "POST",
			data: { email: email, p: password },
			dataType: "json",
			success: function(response) {

				//console.log(response);

				// Remove any login form errors
				$("#bad-email").removeClass("input-error");
				$("#bad-password").removeClass("input-error");

				// Create cookies of the login information
				if ($("#remember-user").is(":checked")) {
					Cookies.set('email', email, { expires: 7, path: '' });
					Cookies.set('passwordhash', password, { expires: 7, path: '' })
				}

				// Redirect the user to the activity page
				window.location.replace(homeurl + "activity/");

				activity(response);

			},
			error: function(jqXHR, textStatus, errorThrown){

				//console.log("error: " + textStatus + " " + errorThrown);
				//console.log(jqXHR);

				var error = jqXHR.responseJSON.result;
				//console.log(error);

				switch(error) {
				// If the email address is unrecognised
					case "user-nonexistant":
						$("#bad-email").addClass("input-error");
						break;

					// If the password is unrecognised
					case "bad-password":
						$("#bad-password").addClass("input-error");
						break;
						
				};

			}
		})
	}
			
}

function activity(response) {

	// Close other open modals and open activity
	//$('.inline').modaal('close');
	//$('.modal-ajax').modaal('close');

	//console.log(response);

	// Add the json response to the activity table
	if (!updateActivity) {

		var activity = response.data.activity;
		// activity.length = how many run sessions the runner's logged

		// Add the runner's runs to the HTML table
		for (i = 0; i < activity.length; i++) {

			// This row will change with each successive loop
			var row = activity[i];

			// The typeface Branding doesn't support fractions, so I've made a hacky fix.
			// With Regular Expressions, jQuery is picking out the numerator,
			// slash and denominator from the string and then using CSS to format each like such.
			var distance = row.distance.replace(/\d(?=\/)/,"<span class='numerator'>$&</span>").replace(/\/(?=<)/,"<span class='slash'>/</span>").replace(/(\d*?)(?=\smile)/,"<span class='denominator'>$&</span>");

			// Format the date
			var d = new Date(row.date);

			// Short day, date with ordinal and short month 
			momentdate = moment(d, 'YYYY-DD-MM', true).format('ddd Do MMM');

			date = momentdate.replace(/(\d+)([a-z]+)/, "$1<sup>$2</sup>");

			var session = i+1;

			// Add the JSON to the HTML markup
			var rowMarkup = "<tr><td>" + session + "</td><td>" + row.activity + "</td><td><time datetime='" + row.date + "'>" + date + "</time></td><td>" + distance + "</td><td>" + row.start_time + "</td><td>"  + row.finish_time + "</td><td>" + row.duration + "</td></tr>";

			// Display the JSON and HTML markup in the console
			//console.log(rowMarkup);

			// Insert the data
			$('#activity tbody').append(rowMarkup);

		}

	}

	// Finished updating the activity table
	updateActivity = true;

}

function passwordReset() {

	var email = $("#reminder-email").val();
	if (email !== "") {
		$.ajax({
			url: homeurl + "password-reminder.php?email=" + email,
			method: "GET",
		}).done(function (response) {
			if (response !== "null") {
				$("#modal-reminder form").css("display", "none");
				//console.log(response);
				$("#password-reminder").css("display", "block");
				$("#password-reminder").text(response);
			} else {
				$("#reminder-bad-email").css("display", "block");
			}
		});
	}

}

function passwordResetModal() {

	

}

function leaderboard() {

	// AJAX communication with leaderboard script
	$.ajax({
		url: homeurl + "leaderboard.php",
		method: "GET",
		dataType: "json",
		success: function(response) {

			//console.log(response);

			var leaderboard = response.data;

			// Add the runner's runs to the HTML table
			for (i = 0; i < leaderboard.length; i++) {

				// This row will change with each successive loop
				var row = leaderboard[i];

				var position = i + 1;
				//console.log(position);

				// Add the JSON to the HTML markup
				var rowMarkup = "<tr><td>" + position + "</td><td>" + row.name + "</td><td>" + row.duration + "</td></tr>";

				// Display the JSON and HTML markup in the console
				//console.log(rowMarkup);

				var temp = $('#leaderboard');
				//console.log(temp);

				// Insert the data
				$('#leaderboard tbody').append(rowMarkup);

			}

		}

	});

}

// Global scope
var form, submit, form_data;

form = document.getElementById('register-form')

// When the submit button is clicked
$('#submit').click( function() {

	// Name
	form_data.fullname = form.fullname.value;
	// Email
	form_data.email = form.email.value;
	// Password
	form_data.password = form.password.value;

	// Test if the passwords have been correctly filled
	form_data.submit();

})

// Manually add new activity
$('#manual_submit').click( function () {
	submit_activity.activity = document.getElementById('manual_activity').value;
	submit_activity.date = document.getElementById('manual_date').value;
	submit_activity.distance = document.getElementById('manual_distance').value;
	submit_activity.start_time = document.getElementById('manual_starttime').value;
	submit_activity.finish_time = document.getElementById('manual_finishtime').value;
	submit_activity.duration = document.getElementById('manual_duration').value;
	submit_activity.submit();
})

// Object oriented
// Create a JavaScript object representing the registration data
submit_activity = {

	set activity(value) {

		this._activity = value;

	},
	get activity() {

		// Get the activity
		return this._activity

	},
	set date(value) {

		this._date = value

	},
	get date() {

		// Get the date
		return this._date

	},
	set distance(value) {

		this._distance = value;

	},
	get distance() {

		// Get the distance
		return this._distance

	},
	set start_time(value) {

		this._start_time = value

	},
	get start_time() {

		// Get the start_time
		return this._start_time

	},
	set finish_time(value) {

		this._finish_time = value;

	},
	get finish_time() {

		// Get the finish_time
		return this._finish_time

	},
	set duration(value) {

		this._duration = value;

	},
	get duration() {

		// Get the duration
		return this._duration

	},
	submit : function submit() {

		// Create a variable in JSON format with the activity, date, distance, starttime, finishtime and duration properties
		var json = JSON.stringify(this, ['activity', 'date', 'distance', 'start_time', 'finish_time', 'duration']);
		console.log(json)

		// Create an AJAX object
		let http = new XMLHttpRequest();
		http.open('POST', window.location.origin + '/includes/manual_entry.php', true);
		http.setRequestHeader("Content-Type", "application/json; charset=utf-8");
		// Send the registation data
		http.send(json)

		// Read the response
		http.onreadystatechange = function() {
			//console.log(this);
			if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
				location.reload();
			} else if (this.status == 400) {
				alert("There's something wrong with the data provided");
			}
		}

	}
}