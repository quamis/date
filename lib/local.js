_localDate = function() {
	this.target = null;
	this.settings = {};
	this.settings['tickLength'] = 0.25;
	
	this.startDate = null;
	this.lastTick = null;
	
	this.timer = null;
	
	this.stats = {};
	this.stats['ticks'] = 0;
}

_localDate.prototype.attach = function(target)
{
	this.target = target;
	return this;
}

_localDate.prototype.start = function()
{
	this.startDate = new Date();
	this.target.addClass('active');
	this.target.trigger('timer:start');
	
	this.timer = setInterval(  
	(function(self) {         
         return function() {   
             self._tick(); 
         }
     })(this), this.settings['tickLength']*1000);
	 
	 noSleep.enable();
}

_localDate.prototype.stop = function()
{
	clearInterval(this.timer);
	this.timer = null;
	this.target.removeClass('active');
	this.target.trigger('timer:stop');
	
	noSleep.disable();
}

_localDate.prototype._tick = function()
{
	//console.info('tick', this, this.stats['lastTick']);
	this.stats['ticks']++;
	
	var d = new Date();
	
	this.target.trigger('timer:tick', {
		'date': d,
		'ticks': this.stats['ticks'],
		'source': this,
	});
	
	this.lastTick = d;
	this.render(d);
}
_localDate.prototype.render = function(d)
{
	// @from http://stackoverflow.com/questions/10073699/pad-a-number-with-leading-zeros-in-javascript
	function pad (n, width, z) {
		z = z || '0';
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	}
	moment.locale('ro');
	this.target.find('div.date .year').html(pad(1900+d.getYear(), 4));
	// this.target.find('div.date .month').html(pad(1+d.getMonth(), 2));
	this.target.find('div.date .month').html(moment().format('MMM'));
	this.target.find('div.date .day').html(pad(1+d.getDay(), 2));
	
	this.target.find('div.time .second').html(pad(d.getSeconds(), 2));
	this.target.find('div.time .minute').html(pad(d.getMinutes(), 2));
	this.target.find('div.time .hour').html(pad(d.getHours(), 2));
	this.target.find('div.time .minute + .dot').toggleClass('_mark', Math.ceil(d.getTime())-d.getTime()>0.5);
}
