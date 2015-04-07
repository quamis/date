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
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.2/underscore-min.js"></script>
	
	<script type="text/javascript" src= "lib/index.js"></script>
	<script type="text/javascript">
		$().ready(function() {
			periodicHandlerSettings.serverDate = new Date("<?=$date->format("Y-m-d\TH:i:s.u")?>");
			periodicHandlerInitialize();
		});
	</script>

</body>
</html>
