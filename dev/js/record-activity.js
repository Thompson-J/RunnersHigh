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
	
	}
});

$('#record_submit').click(function() {

	// If recording, stop recording and attempt to submit
	if (recording) $('#record_btn').click();

	submit_activity.activity = document.getElementById('activity').value;
	submit_activity.date = date;
	submit_activity.distance = 1;
	submit_activity.start_time = start_time.format('HH:mm:ss');
	submit_activity.finish_time = finish_time.format('HH:mm:ss');
	submit_activity.duration = duration;
	submit_activity.submit();
})

