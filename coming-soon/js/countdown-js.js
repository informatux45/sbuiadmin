
 
// variables for time units
var days, hours, minutes, seconds;
 
// get tag element
var countdown = document.getElementById('countdown');
 
// update the tag with id "countdown" every 1 second
setInterval(function () {
 
    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
 
    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
     
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
     
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
     
    // format countdown string + set tag value
	var real_days = (seconds_left < 0) ? 0 : (days+1);
	if (real_days < 2)
		var dday = 'jour';
	else
		var dday = 'jours';
    countdown.innerHTML = '<div class="days"><br><h2>Lancement<br>dans <span>' + real_days + '</span> ' + dday + '</div> ';  
 
}, 1000);