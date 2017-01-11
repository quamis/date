_chronometer = function() {
	this.target = null;
	this.settings = {};
	this.settings['tickLength'] = 0.25;
	
	this.startDate = null;
	this.endDate = null;
	this.lastTick = null;
	this.lastDiff = null;
	
	this.timer = null;
	
	this.stats = {};
	this.stats['ticks'] = 0;
	
	this.sounds = null;
}

// _chronometer.prototype.initialize = function()
// chronometer.setTickLenght = function(interval)
// chronometer.on = function(evt, handler)
// chronometer.start = function()
// chronometer.stop = function(evt, handler)

_chronometer.prototype.showHundreds = function(doShow)
{
	if (doShow) {
		this.settings['tickLength'] = 0.01;
		this.target.addClass('show-hundreds');
	}
	else {
		this.settings['tickLength'] = 0.2;
		this.target.removeClass('show-hundreds');
	}
	
	return this;
}

_chronometer.prototype.mute = function(doMute)
{
	if (doMute) {
		this.sounds = new Howl({
                        src: [
                            'css/chronometer/silence.mp3', 
                            'css/chronometer/silence.m4a', 
                            'css/chronometer/silence.flac', 
                            'css/chronometer/silence.wav'],
                        sprite: {
                            tick:  [0.0*1000, 0.3*1000],
                            start: [0.3*1000, 0.6*1000],
                            stop:  [0.6*1000, 1.0*1000]
                        }
                });
	}
	else {
		this.sounds = new Howl({
			src: [
                            'css/chronometer/sprite.mp3', 
                            'css/chronometer/sprite.m4a', 
                            'css/chronometer/sprite.flac', 
                            'css/chronometer/sprite.wav'],
			sprite: {
                            tick:  [0.0*1000, 0.3*1000],
                            start: [0.3*1000, 2.0*1000],
                            stop:  [2.3*1000, 7.5*1000]
                        }
                });
	}	
	return this;
}

_chronometer.prototype.attach = function(target)
{
	this.target = target;
	
	return this;
}

_chronometer.prototype.start = function()
{
	this.startDate = new Date();
	this.target.addClass('active');
	this.target.trigger('chronometer:start');
	
	this.timer = setInterval(  
	(function(self) {         
         return function() {   
             self._tick(); 
         }
     })(this), this.settings['tickLength']*1000);
	 
	 this.sounds.play('start');
	 noSleep.enable();
}

_chronometer.prototype.stop = function()
{
	clearInterval(this.timer);
	this.timer = null;
	this.target.removeClass('active');
	this.endDate = new Date();
	this.target.trigger('chronometer:stop');
	
	this.sounds.play('stop');
	noSleep.disable();
}

_chronometer.prototype._tick = function()
{
	//console.info('tick', this, this.stats['lastTick']);
	this.stats['ticks']++;
	
	var d = new Date();
	
	var diff = (d.getTime() - this.startDate.getTime())/1000;
	
	this.target.trigger('chronometer:tick', {
		'diff': diff,
		'ticks': this.stats['ticks'],
		'source': this,
	});
	
	// once second passed
	if (this.lastDiff && (Math.floor(this.lastDiff) - Math.floor(diff))!=0) {
		var s = Math.ceil(diff);
		this.target.trigger('chronometer:tick:1s', {
			'diff': diff,
			'ticks': this.stats['ticks'],
                        'source': this,
		});
		
		if (s%5==0) {
			this.target.trigger('chronometer:tick:5s', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%10==0) {
			this.target.trigger('chronometer:tick:10s', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%15==0) {
			this.target.trigger('chronometer:tick:15s', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%30==0) {
			this.target.trigger('chronometer:tick:30s', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%60==0) {
			this.target.trigger('chronometer:tick:1m', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%(5*60)==0) {
			this.target.trigger('chronometer:tick:5m', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%(10*60)==0) {
			this.target.trigger('chronometer:tick:10m', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%(15*60)==0) {
			this.target.trigger('chronometer:tick:15m', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
		
		if (s%(30*60)==0) {
			this.target.trigger('chronometer:tick:30m', {
				'diff': diff,
				'ticks': this.stats['ticks'],
				'source': this,
			});
		}
	}
	
	this.lastTick = d;
	this.lastDiff = diff;
	this.render(diff);
	// this.sounds['tick'].play();
}
_chronometer.prototype.render = function(diff)
{
	// @from http://stackoverflow.com/questions/10073699/pad-a-number-with-leading-zeros-in-javascript
	function pad (n, width, z) {
		z = z || '0';
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	}
	
	var d,s,m,h;
	d = Math.ceil(diff);
	rdiff = d;
	
	s = d % 60;
	d = (d-s)/60;
	
	m = d % 60;
	d = (d-m)/60;
	
	h = d;
	
	this.target.find('div.time .hundreds').html(pad(Math.min(Math.round((rdiff - diff)*(100)), 99), 2));
	this.target.find('div.time .second').html(pad(s, 2));
	this.target.find('div.time .minute').html(pad(m, 2));
	this.target.find('div.time .hour').html(h);
	this.target.find('div.time .minute + .dot').toggleClass('_mark', Math.ceil(diff)-diff>0.5);
	
	this.target.removeClass('has-seconds');
	this.target.removeClass('has-minutes');
	this.target.removeClass('has-hours');
	if (s>0 || m>0 || h>0 ) {
		this.target.addClass('has-seconds');
	}
	if (m>0 || h>0 ) {
		this.target.addClass('has-minutes');
	}
	if (h>0 ) {
		this.target.addClass('has-hours');
	}

	
}
