// When the page has loaded
$(function() {
	// Submit forms with the ENTER key
	$('input').keydown(function(e) {

		if (e.keyCode == 13) {
			$(this).parent().children('button').click();
		}

	});

});

// Global scope
var form, submit, form_data;

form = document.getElementById('register-form')
submit = document.getElementById('submit')

// When the submit button is clicked
submit.addEventListener('click', function() {

	// Name
	form_data.fullname = form.fullname.value;
	// Email
	form_data.email = form.email.value;
	// Password
	form_data.password = form.password.value;

	// Test if the passwords have been correctly filled
	form_data.submit();

})

// Object oriented
// Create a JavaScript object representing the registration data
form_data = {

	set fullname(value) {
		// If the name is 3 characters or greater and less than 20 then accept it
		if (value.length >= 3 && value.length < 20) {
			// Replace spaces with underscores
			//value = value.replace(' ', '_');
			this._fullname = value;

			// Remove the error class
			document.getElementById('bad-name').classList.remove('input-error')
		} else {
			// Add the error class
			document.getElementById('bad-name').classList.add('input-error')
		}
	},
	get fullname() {
		// Get the name
		return this._fullname
	},
	set email(value) {

		// Regular expression credit: https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
		var email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		// If the email is valid then accept it
		if ( email_regex.test(value) && value.length <= 60 ) {
			this._email = value
			// Remove the error class
			document.getElementById('bad-email').classList.remove('input-error')
		} else {
			// Add the error class
			document.getElementById('bad-email').classList.add('input-error')
		}
	},
	get email() {
		// Get the email
		return this._email
	},
	set password(value) {

		// Check if the password match and satisfy security critera
		let password_confirmation = form.password_confirmation.value;
		const password_regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]*$/;

		// Store a variable to use in the valid password test
		let password_valid = false;

		if( value.length >= 8 && password_regex.test(value) ) {
		
			// This is a valid password
			password_valid = true

		} else {
			// Add the error class
			document.getElementById('bad-password').classList.add('input-error')
		}

		if( password_valid && value === password_confirmation ) {

			// Set the passwords because they match and satisfy security critera
			this._password = hex_sha512(value)

			// Remove the error class
			document.getElementById('bad-password').classList.remove('input-error')
			document.getElementById('bad-password-confirmation').classList.remove('input-error')

		} else {
			// Add the error class
			document.getElementById('bad-password-confirmation').classList.add('input-error')
		}

	},
	get password() {
		// Get the name
		return this._password
	},
	submit : function submit() {

		//console.log(this.email)

		if( this.fullname !== (null || undefined) && this.email !== (null || undefined) && this.password !== (null || undefined) ) {

			// Create a variable in JSON format with the fullname, email and password properties
			var json = JSON.stringify(this, ['fullname', 'email', 'password']);
			console.log(json)

			// Create an AJAX object
			let http = new XMLHttpRequest();
			http.open('POST', window.location.origin + '/includes/process_registration.php', true);
			http.setRequestHeader("Content-Type", "application/json; charset=utf-8");
			// Send the registation data
			http.send(json)

			// Read the response
			http.onreadystatechange = function() {
				//console.log(this);
				if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
					console.log(http.responseText)
					retrieveActivity();
				} else {
					if (this.status === 403) {
						alert('Check the details you entered');
					}
				}
			}
		
		}

	}
}