// Global scope
var homeurl = window.location.origin + "/";
var cookieUsed = false;
let loggedIn = false;
var register_form_data;
var modify_form_data = new FormData();

// Development environment redirect
if(window.location.hostname == "localhost" && window.location.port !== "3000") window.location.port = "3000";
else if( (window.location.hostname !== "localhost" && window.location.hostname !== "127.0.0.1") && window.location.protocol !== 'https:') window.location.protocol = 'https:';

// Modaal
$('.modaal').modaal({
	before_open: function() {
		// Close the nav sidebar by removing the active class
		$('#site-wrapper, .sidenav, .burger-button, .burger-button div').removeClass('active');
		$('#site-wrapper').addClass('modal-open');
	},
	before_close: function() {
		$('#site-wrapper').removeClass('modal-open');
	}
});

function loginCheck(callback) {

	let http = new XMLHttpRequest();

	http.open('POST', homeurl + "includes/process_login.php", true);
	http.responseType = 'json';

	// Read the response
	http.onreadystatechange = function h() {
		//console.log(this)
		if (this.readyState == 4) {
			// When the operation is complete and we are logged in
			if (this.status == 200) {
				loggedIn = true;
				callback(loggedIn);
			}
		}
	}

	http.send()

}

// Do these things after loading the page
$(document).ready(function() {

	// Setup accordians
	$('.accordian_trigger').each(function() {
		$(this).click(function() {
			// Store the accordian
			var accordian = $(this).next('.accordian_box');
			if(!$(accordian).hasClass('open')) {
				// Get the height of the element
				let currentHeight = $(accordian).height();
				// Get the expanded height of the element
				let autoHeight = $(accordian).css('height', 'auto').height();
				// Animate the accordian
				$(accordian)
					.height(currentHeight).animate({
						height: autoHeight
					}, 500);
				// Toggle the collapse class
				$(accordian).toggleClass('open');
			} else {
				// Animate the accordian
				$(accordian).animate({
					height: 0
				}, 500);
				// Toggle the collapse class
				$(accordian).toggleClass('open');
			}
		});
	})

	// Photo upload
	$('#modify_photo').on('change', function() {

		$('#modify_photo_container').removeClass('animated bounceIn')
		// Prepare a FormData object
		let form = new FormData();
		// Pass the photo to it
		form.set('photo', this.files[0]);
		console.log(Array.from(form.entries()))

		// Create a new AJAX request
		let http = new XMLHttpRequest();
		http.open('POST', homeurl + 'includes/modify_photo.php', true);
		// Send the registation data
		http.send(form)

		// Read the response
		http.onreadystatechange = function() {

			console.log(this);

			// When the operation is complete
			if (this.readyState == 4) {

				// If registration is successful
				if (this.status == 200) {
					let img_src = $('#modify_photo_img').attr('src');
					// Reload just this image by adding a parameter to the src
					$('#modify_photo_img').attr('src', img_src + "?a=0");
					// Animate it to the user
					$('#modify_photo_container').addClass('animated bounceIn');
				}

			}

		}

	});

	loginCheck(function(){
		// Do something when we're logged in
	});

	// Add current class to links to the page we're on
	let currentPage = document.location.href.match(/\/\/.+/)
	$('a[href$="' + currentPage + '"]').addClass('current')

	// Burger menu button
	$(".burger-button").click(function() {

		$('.sidenav, .burger-button, .burger-button div').toggleClass('active');

	});

	// Read the login cookie and enter details into the form fields
	if ((Cookies.get("email") != undefined) && (Cookies.get("passwordhash") != undefined)) {

		$("#login_email").val(Cookies.get("email"));
		$("#login_password").val(Cookies.get("passwordhash"));
		cookieUsed = true;
		$("#login_remember_user").prop("checked", true);

	};

	// Submit forms with the ENTER key
	$('input').keydown(function(e) {

		if (e.keyCode == 13) {
			$(this).parent().children('button').click();
		}

	});

	// Allow all tables other than leaderboard to be sorted
	$("table:not(#leaderboard)").each(function(){

		$(this).find('th').each(function(col) {

			// Attach event listeners to each column header
			$(this).click(function() {

				// Remove class from all col elements in colgroup
				$(this).parents('table').find('col').removeClass('selected');
				// Add class to a clicked col element
				$(this).parents('table').find('col').eq(col).addClass('selected');

				// Descending order
				if ($(this).is('.asc')) {

					$(this).removeClass('asc');
					$(this).addClass('desc selected');
					sortOrder = -1;
				
				} else {
				// Ascending order
				
					$(this).addClass('asc selected');
					$(this).removeClass('desc');
					sortOrder = 1;
				
				}
				
				$(this).siblings().removeClass('asc selected');
				$(this).siblings().removeClass('desc selected');

				// Make an array of the contents the table
				var arrData = $(this).parents('table').find('tbody > tr:has(td)').get();

				// Sort this array
				arrData.sort(function(a, b) {

					var val1, val2;
					val1 = $(a).children('td').eq(col).text().toUpperCase();
					val2 = $(b).children('td').eq(col).text().toUpperCase();

					// For each time/ date cell (a and b) set variables the datetime attribute value e.g 2018:01:31, 00:57:02
					$(a).children('td').eq(col).children('time').each(function() {

						val1 = $(this).attr('datetime');

					});
					$(b).children('td').eq(col).children('time').each(function() {

						val2 = $(this).attr('datetime');

					});

					if ($.isNumeric(val1) && $.isNumeric(val2))
						return sortOrder == 1 ? val1 - val2 : val2 - val1;
					else
						return val1 < val2 ? -sortOrder : (val1 > val2) ? sortOrder : 0;
				});
				//$(table + ' tbody tr').remove()
				$.each(arrData, function(index, row) {
					$(this).parents('tbody').append(row);
				});

			});

		});

	});

	leaderboard();
	

});

// When the submit button is clicked
function registerSubmit() {

	let register_form = document.getElementById('register_form');

	// Name
	register_form_data.fullname = register_form.register_fullname.value;
	// Gender
	register_form_data.gender = register_form.register_gender.value;
	// Date of birth
	register_form_data.dob = register_form.register_dob.value;
	// Email
	register_form_data.email = register_form.register_email.value;
	// Password
	register_form_data.password = register_form.register_password.value;
	// Privacy
	register_form_data.privacy = register_form.register_privacy.value;

	// Test if the passwords have been correctly filled
	register_form_data.submit();

}

// Object oriented
// Create a JavaScript object representing the registration data
register_form_data = {

	set fullname(value) {
		// If the name is 3 characters or greater and less than 20 then accept it
		if (value.length >= 3 && value.length < 20) {
			// Replace spaces with underscores
			//value = value.replace(' ', '_');
			this._fullname = value;

			// Remove the error class
			document.getElementById('register_bad_name').classList.remove('input-error')
		} else {
			// Add the error class
			document.getElementById('register_bad_name').classList.add('input-error')
		}
	},
	get fullname() {
		// Get the name
		return this._fullname
	},
	set gender(value) {
		// If this is either male or femail
		if(value == ('male' || 'femail')) {
			this._gender = value;
		}
	},
	get gender() {
		// Get the gender
		return this._gender;
	},
	set dob(value) {
		// If this is a valid date
		var dob_regex = /^\d\d\d\d\-\d\d\-\d\d$/;	
		if(dob_regex.test(value)) this._dob = value;
	},
	get dob() {
		// Get the date of birth
		return this._dob;
	},
	set email(value) {

		// Regular expression credit: https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
		var email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		// If the email is valid then accept it
		if ( email_regex.test(value) && value.length <= 60 ) {
			this._email = value
			// Remove the error class
			document.getElementById('register_bad_email').classList.remove('input-error')
		} else {
			// Add the error class
			document.getElementById('register_bad_email').classList.add('input-error')
		}
	},
	get email() {
		// Get the email
		return this._email
	},
	set password(value) {

		// Check if the passwords match and satisfy security critera
		let password_cnfrm = register_form.register_password_cnfrm.value;
		const password_regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]*$/;

		if( value.length >= 8 && password_regex.test(value) ) {
		
			// This is a valid password
			document.getElementById('register_bad_password').classList.remove('input-error')

			if( value === password_cnfrm ) {

				// Set the passwords because they match and satisfy security critera
				this._password = hex_sha512(value)

				// Remove the error class
				document.getElementById('register_bad_password_cnfrm').classList.remove('input-error')

			} else {
				// Add the error class
				document.getElementById('register_bad_password_cnfrm').classList.add('input-error')
			}

		} else {
			// Add the error class
			document.getElementById('register_bad_password').classList.add('input-error')
		}

	},
	get password() {
		// Get the name
		return this._password
	},
	set privacy(value) {
		if(value == ('public' || 'private')) {
			this._privacy = value;
		}
	},
	get privacy() {
		return this._privacy;
	},
	submit : function submit() {

		if( this.fullname !== (null || undefined) && this.email !== (null || undefined) && this.password !== (null || undefined) ) {

			// Create a variable in JSON format with the fullname, email and password properties
			var json = JSON.stringify(this, ['fullname', 'gender', 'dob', 'email', 'password', 'privacy']);

			// Create an AJAX object
			let http = new XMLHttpRequest();
			http.open('POST', homeurl + 'includes/process_registration.php', true);
			http.setRequestHeader("Content-Type", "application/json; charset=utf-8");
			http.responseType = 'json';
			// Send the registation data
			http.send(json)

			// Read the response
			http.onreadystatechange = function() {

				// When the operation is complete
				if (this.readyState == 4) {

					// If registration is successful
					if (this.status == 200) {
					
						// Close the registration modal and refresh the page
						$('.modaal').modaal('close');
						window.location.reload();
					
					} else {
					
						var error = (http.response)
						switch(error.result) {

							// If the email address is unrecognised
							case "A user with this email already exists. ":
								$("#register_bad_email").addClass("input-error");
								break;

							// If the password is unrecognised
							case "Invalid password configuration. ":
								$("#register_bad_password").addClass("input-error");
								break;
								
						};
					
					}
				}
			}
		
		}

	}
}

// Check the login details with the database
function login() {

	//window.alert("hello world");
	var email = document.getElementById("login_email").value;
	var password = document.getElementById("login_password").value;
	//console.log(password);

	// Check the user typed an email address
	if (email != "" && password != "") {

		// Hash the password before sending it.
		if (!cookieUsed) password = hex_sha512(password);

		// Create a variable in JSON format with the fullname, email and password properties
		var json = JSON.stringify({ 'email': email, 'password': password });

		// Create an AJAX object
		let http = new XMLHttpRequest();
		http.open('POST', homeurl + "includes/process_login.php", true);
		http.setRequestHeader("Content-Type", "application/json; charset=utf-8");
		http.responseType = 'json';
		// Send the registation data
		http.send(json)

		// Read the response
		http.onreadystatechange = function() {
			// When the operation is complete
			if (this.readyState == 4) {

				// If login is successful
				if (this.status == 200) {

					// Remove any login form errors
					$("#login_bad_email").removeClass("input-error");
					$("#login_bad_password").removeClass("input-error");

					// Create cookies of the login information
					if ($("#login_remember_user").is(":checked")) {
						Cookies.set('email', email, { expires: 7, path: '' });
						Cookies.set('passwordhash', password, { expires: 7, path: '' })
					}

					// Redirect the user to the activity page
					window.location.replace(homeurl + "activity/");
				
				} else {

					var error = http.response

					switch(error.result) {
						
						// If the email address is invalid
						case "The email address you entered is not valid. ":
							$("#login_bad_email").addClass("input-error");
							break;

						// If the email address is unrecognised
						case "A user with this email already exists. ":
							$("#login_bad_email").addClass("input-error");
							break;

						// If the password is unrecognised
						case "Invalid password configuration. ":
							$("#login_bad_email").removeClass("input-error");
							$("#login_bad_password").addClass("input-error");
							break;

						// If the password is unrecognised
						case "bad-password":
							$("#login_bad_email").removeClass("input-error");
							$("#login_bad_password").addClass("input-error");
							break;
							
					};

				}
			}
		}

	}
			
}

function leaderboard() {

	// AJAX communication with leaderboard script
	let http = new XMLHttpRequest();
	http.open('GET', homeurl + 'leaderboard.php', true);
	http.responseType = 'json';
	// Send the registation data
	http.send()

	// Read the response
	http.onreadystatechange = function() {

		// When the operation is complete
		if (this.readyState == 4) {

			// If the leaderboard has sent the response
			if (this.status == 200) {

				var leaderboard = this.response.data;

				// Add the runner's runs to the HTML table
				for (i = 0; i < leaderboard.length; i++) {

					// This row will change with each successive loop
					var row = leaderboard[i];

					var position = i + 1;

					// Add the JSON to the HTML markup
					var rowMarkup = "<tr><td>" + position + "</td><td>" + row.name + "</td><td>" + row.duration + "</td></tr>";

					// Insert the data
					$('#leaderboard tbody').append(rowMarkup);

				}

			}

		}

	}

}

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
	set waypoints(value) {

		this._waypoints = value;

	},
	get waypoints() {

		// Get the waypoints
		return this._waypoints

	},
	submit: function submit() {

		// Create a variable in JSON format with the activity, date, distance, starttime, finishtime and duration properties
		var json = JSON.stringify(this, ['activity', 'date', 'distance', 'start_time', 'finish_time', 'duration', 'waypoints']);

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

$('#modify_account_update').click(function() {

	//modify_form_data.set('photo', $('#modify_photo').prop('files')[0]);
	modify_form_data.set('email', $('#modify_email').val());
	//modify_form_data.set('password', hex_sha512($('#modify_email').val()));
	//modify_form_data.set('privacy', $('#modify_privacy input:checked').val());
	//modify_form_data.set('delete_password', hex_sha512($('#modify_delete_password').val()));
	//modify_form_data.set('delete_cnfrm', $('#modify_delete_cnfrm:checked').val());
	modify_account_data.submit();

});

modify_account_data = {
	set photo(value) {
		if (value !== (undefined || null || "")) {
			this._photo = value;
		}
	},
	get photo() {
		return this._photo;
	},
	submit: function submit() {
		
		// Create a variable in JSON format with the activity, date, distance, starttime, finishtime and duration properties

		// Create an AJAX object
		let http = new XMLHttpRequest();
		http.open('POST', window.location.origin + '/includes/modify_account.php', true);
		http.setRequestHeader("Content-Type", "multipart/form-data;");
		// Send the registation data
		http.send(modify_form_data);

		// Read the response
		http.onreadystatechange = function() {
			
			console.log(this);
			if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
				//location.reload();
			} else if (this.status == 400) {
				alert("There's something wrong with the data provided");
			}

		}

	}
}