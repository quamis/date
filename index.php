<?php
ini_set('display_errors', '1');
error_reporting(-1);
// date_default_timezone_set('UTC');
date_default_timezone_set('Europe/Bucharest');

$date = new DateTime();

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<title>xCh - current date</title>
	<script>document.write('<base href="' + document.location + '" />');</script>
	<script src="lib/moment.js/moment.js"></script>
	
	<script src="lib/jquery/jquery.min.js"></script>
	<script src="lib/underscore.js/underscore.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/index.css" media="only screen and (min-device-width: 100px)" />
</head>

<body>
	<div class='stats'>
		<div class="csdiff">
			<span class="label">c/s diff</span>
			<span class="value">xx.xxxx</span>
		</div>
		
		<div class="drift">
			<span class="label">drift</span>
			<span class="value">xx.xxxx</span>
		</div>
		
	</div>
	<div class='lastDate'>
		<div class='date'><?=$date->format("Y-m-d")?></div>
		<div class='hour'><span class='hour'><?=$date->format("H")?></span><span class='dot'>:</span><span class='minute'><?=$date->format("i")?></span><span class='dot'>:</span><span class='second'><?=$date->format("s")?></span></div>
	</div>
	
	<script type="text/javascript">
		periodicHandlerSettings = {
			
		}
		periodicHandlerInitialize = function() {
			periodicHandlerSettings.localDate = new Date();
			periodicHandlerSettings.serverDate = new Date("<?=$date->format("Y-m-d\TH:i:s.u")?>");
			periodicHandlerSettings.timeout = 1;
			periodicHandlerSettings.csDiff = 0;
			periodicHandlerSettings.drift = null;
			periodicHandlerSettings.drift_compensation = null;
			
			
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
		
		$().ready(function() {
			periodicHandlerInitialize();
		});
	</script>
</body>
</html>
