<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	
	<title>Local date</title>
	<script>document.write('<base href="' + document.location + '" />');</script>
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/local.css" media="only screen and (min-device-width: 100px)" />
</head>

<body>
	<div class='buttons'>
		<button class='menu' onClick="$('ul.menu').toggleClass('invisible');">menu</button>
		
		<ul class="menu invisible">
			<li class="item chronometer"><a href="chronometer.php">chronometer:default</a></li>
			<li class="item chronometer"><a href="chronometer.php?mode=workout:5m">chronometer:workout:5m</a></li>
			<li class="item chronometer"><a href="chronometer.php?mode=scrum:15m">chronometer:scrum:15m</a></li>
			<li class="item time"><a href="local.php">localtime</a></li>
			<li class="item time"><a href="remote.php">servertime</a></li>
		</ul>
	</div>
	
	<div class='screen'>
		<div class='date'><span class='day'>??</span> <span class='month'>??</span><span class='year'>????</span></div>
		<div class='time'><span class='hour'>??</span><span class='dot'>:</span><span class='minute'>??</span><span class='dot'>:</span><span class='second'>??</span></div>
	</div>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js"></script>
	
	<script type="text/javascript" src= "cdn/NoSleep.min.js"></script>
	
	<script type="text/javascript" src= "lib/local.js"></script>
	<script type="text/javascript">
		$().ready(function() {
			localDate = new _localDate();
			localDate.attach($('div.screen'));
			localDate.start();
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
