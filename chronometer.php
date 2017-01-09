<?php
ini_set('display_errors', '1');
error_reporting(-1);
// date_default_timezone_set('UTC');
date_default_timezone_set('Europe/Bucharest');
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<title>Chronometer</title>
	<script>document.write('<base href="' + document.location + '" />');</script>
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/chronometer.css" media="only screen and (min-device-width: 100px)" />
</head>

<body>
	<div class='buttons'>
		<button class='start' onClick="">Start</button>
		<button class='stop' style="display: none;">Stop</button>
	</div>
	
	<div class='screen'>
		<div class='time'><span class='hour'>0</span><span class='dot'>:</span><span class='minute'>00</span><span class='dot'>:</span><span class='second'>00</span><span class='dot'>.</span><span class='hundreds'>00</span></div>
	</div>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/howler/2.0.2/howler.core.min.js"></script>
	
	<script type="text/javascript" src= "cdn/NoSleep.min.js"></script>
	
	<script type="text/javascript" src= "lib/chronometer.js"></script>
	<script type="text/javascript">
		$().ready(function() {
			chronometer = new _chronometer();
			chronometer.attach($('div.screen'));
			
			
			switch (mode) {
				case 'workout:5m':
					chronometer.mute(false).showHundreds(false);
					$('div.screen').on('chronometer:tick:5s', function(evt, extraParameter) {
						var alrm1 = 5*60;
						var alrm2 = 7*60;
						if (extraParameter['diff']>alrm2) {
							extraParameter.source.sounds.volume(1.00).play('alarm2');
						}
						else if (extraParameter['diff']>alrm1) {
							extraParameter.source.sounds.volume(Math.min(10*(extraParameter['diff']-alrm1)/60, 1.00)).play('alarm1');
						}
					});
				break;
				
				case 'scrum:15m':
					chronometer.mute(false).showHundreds(false);
					var alrm1 = 1*60;
					$('div.screen').on('chronometer:tick:1s', function(evt, extraParameter) {
						if (extraParameter['diff']>alrm1) {
							extraParameter.source.sounds.volume(Math.min(1*(extraParameter['diff']-alrm1)/60, 1.00)).play('tick');
						}
					});
				break;
				
				default:
					chronometer.mute(true).showHundreds(false);
					
					$('div.screen').on('chronometer:tick:1s', function(evt, extraParameter) {
									if (Math.ceil(extraParameter['diff'])%5 ==0 ) {
										extraParameter.source.sounds.volume(1.00).play('tick');
									}
									else {
										extraParameter.source.sounds.volume(0.10).play('tick');
									}
								});
					
					$('div.screen').on('chronometer:tick:30s', function(evt, extraParameter) {
						if (Math.ceil(extraParameter['diff'])%60 ==0 ) {
							extraParameter.source.sounds.volume(1).play('tick');
						}
						else {
							extraParameter.source.sounds.volume(0.33).play('tick');
						}
					});
			}
			
			
			
			$('div.buttons button.start').click(function(){
				chronometer.start();
				$('div.buttons button.start').hide();
				$('div.buttons button.stop').show();
			});
			$('div.buttons button.stop').click(function(){
				chronometer.stop();
				$('div.buttons button.start').show();
				$('div.buttons button.stop').hide();
			})
		});
		
		/*
		
		using sounds from freesounds
			42136__fauxpress__old-scots-clock-ring-the-hour-five-o-clock.wav
			174721__drminky__watch-tick
		*/
		
		
		function enableNoSleep() {
			noSleep.enable();
			document.removeEventListener('touchstart', enableNoSleep, false);
			document.removeEventListener('click', enableNoSleep, false);
			noSleep.disable();
		}

		// Enable wake lock.
		// (must be wrapped in a user input event handler e.g. a mouse or touch handler)
		document.addEventListener('touchstart', enableNoSleep, false);
		document.addEventListener('click', enableNoSleep, false);
		
		noSleep = new NoSleep();
		
		mode = '<?=(isset($_GET['mode'])?$_GET['mode']:'default')?>';
	</script>

</body>
</html>
