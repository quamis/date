periodicHandlerSettings = {
	localDate: new Date(),
	serverDate: null,
	timeout: 1,
	csDiff: 0,
	drift: null,
	drift_compensation: null
}

periodicHandlerInitialize = function() {
	setTimeout(periodicHandlerPing, periodicHandlerSettings.timeout);
	
	var d = (periodicHandlerSettings.localDate.getTime() - periodicHandlerSettings.serverDate.getTime())/1000;
	periodicHandlerSettings.csDiff = d;
	var s = "";
	if (d<10) {
		s = "0"+d;
	}
	else {
		s = d;
	}
	$('div.stats div.csdiff span.value').html(s);
}



periodicHandlerPing = function() {
	var cd = new Date().getTime();
	var dot = $('div.lastDate span.minute+span.dot');
	var diff = (cd - periodicHandlerSettings.localDate.getTime())/1000;
	var s = Math.floor((periodicHandlerSettings.serverDate.getSeconds() + diff/(1)) % 60);
	var m = Math.floor((periodicHandlerSettings.serverDate.getMinutes() + (periodicHandlerSettings.serverDate.getSeconds()*1+diff)/(60)) % 60);  
	var h = Math.floor((periodicHandlerSettings.serverDate.getHours() + (periodicHandlerSettings.serverDate.getMinutes()*60 + periodicHandlerSettings.serverDate.getSeconds()*1 + diff)/(60*60)) % 24);
	
	dot.addClass('_mark');
	
	$('div.lastDate span.second').html(String((s<10?"0"+s:s)));
	$('div.lastDate span.minute').html(String((m<10?"0"+m:m)));
	$('div.lastDate span.hour').html(String((h<10?"0"+h:h)));

	setTimeout(periodicHandlerPong, 500);
	
	if (diff>60 && s % 30==0) {
		periodicHandlerSettings.timeout = 10;
		$('div.lastDate span.second').addClass('slow');
	}
	
	if (s % 10!=0 && $('div.lastDate span.second').hasClass('slow')) {
		periodicHandlerSettings.timeout = 1;
		$('div.lastDate span.second').removeClass('slow');
	}
	
	// update the .0 second drift. we could update this to adjust the setTimeout function call
	var rd = Math.floor((cd - periodicHandlerSettings.serverDate.getTime())/1000);
	var d = (cd - periodicHandlerSettings.serverDate.getTime())/1000;
	var rdrift = d-rd;
	var drift = rdrift;
	
	if (drift>0.15) {
		drift = 0.15;
	}
	else if (drift<-0.15) {
		drift = -0.15;
	}
	
	if (periodicHandlerSettings.drift===null) {
		periodicHandlerSettings.drift = drift;
	}
	
	
	periodicHandlerSettings.drift_compensation = (drift + (3*periodicHandlerSettings.drift))/4;
	periodicHandlerSettings.drift = drift;
	
	var s = "";
	s = drift.toFixed(3);
	$('div.stats div.drift span.value').html(s);


	setTimeout(periodicHandlerPing, (periodicHandlerSettings.timeout-periodicHandlerSettings.drift_compensation)*1000);
}

periodicHandlerPong = function() {
	$('div.lastDate span.minute+span.dot').removeClass('_mark');
}
