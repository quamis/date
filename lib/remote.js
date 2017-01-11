_remoteDate = function() {
	this.target = null;
	this.info = null;
	this.settings = {};
	this.settings['tickLength'] = 0.5;
	this.settings['resyncPeriod'] = 5;
	
	this.startDate = null;
	this.lastTick = null;
	
	this.timer = null;
	
	this.stats = {};
	this.stats['ticks'] = 0;
	this.stats['resyncs'] = 0;
	this.stats['roundtrip'] = 0;
	
	this.clientOffset = null;
	
	this.request = {
		request: null,
		startDate: null,
		endDate: null,
	};
}

_remoteDate.prototype.attach = function(target, info)
{
	this.target = target;
	this.info = info;
	return this;
}

_remoteDate.prototype.start = function()
{
	this.startDate = new Date();
	
	this._resync();
	
	this.timer = setInterval(  
	(function(self) {         
         return function() {   
             self._tick(); 
         }
     })(this), this.settings['tickLength']*1000);
	 
	 noSleep.enable();
}

_remoteDate.prototype.stop = function()
{
	clearInterval(this.timer);
	this.timer = null;
	this.target.removeClass('active');
	this.target.trigger('timer:stop');
	
	noSleep.disable();
}

_remoteDate.prototype._getDate = function()
{
	if (this.clientOffset===null) {
		var d = new Date();
	}
	else {
		var d = new Date();
		d.setSeconds(d.getSeconds() + this.clientOffset);
	}
	
	return d;
}

_remoteDate.prototype._tick = function()
{
	this.stats['ticks']++;
	
	var d = this._getDate();
	
	this.target.trigger('timer:tick', {
		'date': d,
		'ticks': this.stats['ticks'],
		'source': this,
	});
	
	if (this.lastTick && d.getTime()%this.settings['resyncPeriod']==0 && (d.getSeconds()-this.lastTick.getSeconds())!=0) {
		this._resync();
		this.settings['resyncPeriod'] = Math.min(Math.round(this.settings['resyncPeriod']*2), 60*60);
	}
	
	this.lastTick = d;
	this.render(d);
}

_remoteDate.prototype._resync = function()
{
	if (this.request['request']==null) {
		this.request['startDate'] = new Date();
		this.request['endDate'] = null;
		this.request['request'] = $.getJSON("remote.ajax.php", {}, (function(self) {         
			 return function(data) {
				 self._resync_success(data); 
			 }
		 })(this));
	}
}

_remoteDate.prototype._resync_success = function(data)
{
	if (this.clientOffset===null) {
		this.target.addClass('active');
		this.target.trigger('timer:start');
	}
	
	this.request['endDate'] = new Date();
	
	var rqDuration = (this.request['endDate'].getTime() - this.request['startDate'].getTime())/1000;
	var serverDate = new Date(data['components']['year'], data['components']['month'], data['components']['day'], data['components']['hour'], data['components']['minute'], data['components']['second'], data['components']['milisecond']);
	
	var clientDate = new Date();
	var clientOffset = (serverDate.getTime() - this.request['startDate'].getTime())/1000;
	
	this.stats['roundtrip'] = rqDuration;
	
	this.clientOffset = clientOffset - (rqDuration/2);
	
	this.stats['resyncs']++;
	this.request['request'] = null;
	this.target.trigger('timer:resync');
}


_remoteDate.prototype.render = function(d)
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
	
	if (this.clientOffset) {
		this.info.find('div.offset .value').html((this.clientOffset/(60*60*24)).toFixed(3));
	}
	if (this.request['endDate']) {
		this.info.find('div.resync .value').html((((new Date()).getTime() - this.request['endDate'].getTime())/1000).toFixed(1));
	}
	else {
		this.info.find('div.resync .value').html('WIP');
	}
	
	this.info.find('div.resyncPeriod .value').html(this.settings['resyncPeriod']);
	this.info.find('div.totalResyncs .value').html(this.stats['resyncs']);
	this.info.find('div.roundtrip .value').html(this.stats['roundtrip'].toFixed(3));
	
	
}
