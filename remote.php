<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<title>Server date</title>
	<script>document.write('<base href="' + document.location + '" />');</script>
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/remote.css" media="only screen and (min-device-width: 100px)" />
</head>

<body>
	<div class='info'>
		<div class="offset"><span class="label">c/s offset </span><span class="value">?</span><span class='um'>s</span></div>
		<div class="resync"><span class="value">?</span><span class="label">s since resync</span></div>
		<div class="totalResyncs"><span class="label">total </span><span class="value">?</span><span class="um"> resyncs</span></div>
		<div class="resyncPeriod"><span class="label">resync period </span><span class="value">?</span><span class='um'>s</span></div>
		<div class="roundtrip"><span class="label">roundtrip </span><span class="value">?</span><span class='um'>s</span></div>
	</div>
	
	<div class='screen'>
		<div class='date'><span class='day'>??</span> <span class='month'>??</span><span class='year'>????</span></div>
		<div class='time'><span class='hour'>??</span><span class='dot'>:</span><span class='minute'>??</span><span class='dot'>:</span><span class='second'>??</span></div>
	</div>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js"></script>
	
	<script type="text/javascript" src= "cdn/NoSleep.min.js"></script>
	
	<script type="text/javascript" src= "lib/remote.js"></script>
	<script type="text/javascript">
		$().ready(function() {
			remoteDate = new _remoteDate();
			remoteDate.attach($('div.screen'), $('div.info'));
			remoteDate.start();
		});
		
		
		
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
	</script>

</body>
</html>
