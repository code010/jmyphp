/*
 * Date Format 1.2.3
 * (c) 2007-2009 Steven Levithan <stevenlevithan.com>
 * MIT license
 *
 * Includes enhancements by Scott Trenda <scott.trenda.net>
 * and Kris Kowal <cixar.com/~kris.kowal/>
 *
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */
function strpad(val){
	return (!isNaN(val) && val.toString().length==1)?"0"+val:val;
} 
function cambiaSalida(entrada){
	var auxDate = entrada.split("/");
	var chosendate = new Date();
	chosendate.setFullYear(auxDate[2],auxDate[1]-1,auxDate[0]);
	chosendate.setDate(chosendate.getDate()+1);
	$('#salida').attr('value',strpad(chosendate.getDate()) + "/" + strpad(chosendate.getMonth() + 1) + "/" + chosendate.getFullYear());
}
var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab",
		"Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"
	],
	monthNames: [
		"Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic",
		"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"



		
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

/**
	the script only works on "input [type=text]"

**/

// don't declare anything out here in the global namespace

(function($) { // create private scope (inside you can use $ instead of jQuery)

    // functions and vars declared here are effectively 'singletons'.  there will be only a single
    // instance of them.  so this is a good place to declare any immutable items or stateless
    // functions.  for example:

	var today = new Date(); // used in defaults
    var months = 'January,February,March,April,May,June,July,August,September,October,November,December'.split(',');   
	var monthlengths = '31,28,31,30,31,30,31,31,30,31,30,31'.split(',');
  	var dateRegEx = /^\d{1,2}\/\d{1,2}\/\d{2}|\d{4}$/;
	var yearRegEx = /^\d{4,4}$/;

    // next, declare the plugin function
    $.fn.simpleDatepicker = function(options) {

        // functions and vars declared here are created each time your plugn function is invoked

        // you could probably refactor your 'build', 'load_month', etc, functions to be passed
        // the DOM element from below

		var opts = jQuery.extend({}, jQuery.fn.simpleDatepicker.defaults, options);
		
		// replaces a date string with a date object in opts.startdate and opts.enddate, if one exists
		// populates two new properties with a ready-to-use year: opts.startyear and opts.endyear
		
		setupYearRange();
		/** extracts and setup a valid year range from the opts object **/
		function setupYearRange () {
			
			var startyear, endyear;  
			if (opts.startdate.constructor == Date) {
				startyear = opts.startdate.getFullYear();
			} else if (opts.startdate) {
				if (yearRegEx.test(opts.startdate)) {
				startyear = opts.startdate;
				} else if (dateRegEx.test(opts.startdate)) {
					opts.startdate = new Date(opts.startdate);
					startyear = opts.startdate.getFullYear();
				} else {
				startyear = today.getFullYear();
				}
			} else {
				startyear = today.getFullYear();
			}
			opts.startyear = startyear;
			
			if (opts.enddate.constructor == Date) {
				endyear = opts.enddate.getFullYear();
			} else if (opts.enddate) {
				if (yearRegEx.test(opts.enddate)) {
					endyear = opts.enddate;
				} else if (dateRegEx.test(opts.enddate)) {
					opts.enddate = new Date(opts.enddate);
					endyear = opts.enddate.getFullYear();
				} else {
					endyear = today.getFullYear();
				}
			} else {
				endyear = today.getFullYear();
			}
			opts.endyear = endyear;	
		}
		
		/** HTML factory for the actual datepicker table element **/
		// has to read the year range so it can setup the correct years in our HTML <select>
		function newDatepickerHTML () {
			
			var years = [];
			
			// process year range into an array
			for (var i = 0; i <= opts.endyear - opts.startyear; i ++) years[i] = opts.startyear + i;
	
			// build the table structure
			var table = jQuery('<div class="datepck"><table class="datepicker" cellpadding="0" cellspacing="0"></table></div>');
			table.append('<thead></thead>');
			table.append('<tfoot></tfoot>');
			table.append('<tbody></tbody>');
			
				// month select field
				var monthselect = '<select name="month">';
				for (var i in months) monthselect += '<option value="'+i+'">'+months[i]+'</option>';
				monthselect += '</select>';
			
				// year select field
				var yearselect = '<select name="year">';
				for (var i in years) yearselect += '<option value="'+years[i]+'">'+years[i]+'</option>';
				yearselect += '</select>';
			
			jQuery("thead",table).append('<tr class="controls"><th colspan="7"><span class="prevMonth">&laquo;</span>&nbsp;'+monthselect+yearselect+'&nbsp;<span class="nextMonth">&raquo;</span></th></tr>');
			jQuery("thead",table).append('<tr class="days"><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>');
			jQuery("tfoot",table).append('<tr><td colspan="2"><span class="today">today</span></td><td colspan="3">&nbsp;</td><td colspan="2"><span class="close">close</span></td></tr>');
			for (var i = 0; i < 6; i++) jQuery("tbody",table).append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');	
			return table;
		}
		
		/** get the real position of the input (well, anything really) **/
		//http://www.quirksmode.org/js/findpos.html
		function findPosition (obj) {
			var curleft = curtop = 0;
			if (obj.offsetParent) {
				do { 
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
					curtop += -25;
				} while (obj = obj.offsetParent);
				return [curleft,curtop];
			} else {
				return false;
			}
		}
		
		/** load the initial date and handle all date-navigation **/
		// initial calendar load (e is null)
		// prevMonth & nextMonth buttons
		// onchange for the select fields
		function loadMonth (e, el, datepicker, chosendate) {
			
			// reference our years for the nextMonth and prevMonth buttons
			var mo = parseInt(jQuery("select[name=month]", datepicker).get(0).selectedIndex);
			var yr = jQuery("select[name=year]", datepicker).get(0).selectedIndex;
			var yrs = jQuery("select[name=year] option", datepicker).get().length;
			
			// first try to process buttons that may change the month we're on
			if (e && jQuery(e.target).hasClass('prevMonth')) {				
				if (0 == mo && yr) {
					yr -= 1; mo = 11;
					jQuery("select[name=month]", datepicker).get(0).selectedIndex = 11;
					jQuery("select[name=year]", datepicker).get(0).selectedIndex = yr;
				} else {
					mo -= 1;
					jQuery("select[name=month]", datepicker).get(0).selectedIndex = mo;
				}
			} else if (e && jQuery(e.target).hasClass('nextMonth')) {
				if (11 == mo && yr + 1 < yrs) {
					yr += 1; mo = 0;
					jQuery("select[name=month]", datepicker).get(0).selectedIndex = 0;
					jQuery("select[name=year]", datepicker).get(0).selectedIndex = yr;
				} else { 
					mo += 1;
					jQuery("select[name=month]", datepicker).get(0).selectedIndex = mo;
				}
			}
			
			// maybe hide buttons
			if (0 == mo && !yr) jQuery("span.prevMonth", datepicker).hide(); 
			else jQuery("span.prevMonth", datepicker).show(); 
			if (yr + 1 == yrs && 11 == mo) jQuery("span.nextMonth", datepicker).hide(); 
			else jQuery("span.nextMonth", datepicker).show(); 
			
			// clear the old cells
			var cells = jQuery("tbody td", datepicker).unbind().empty().removeClass('date');
			
			// figure out what month and year to load
			var m = jQuery("select[name=month]", datepicker).val();
			var y = jQuery("select[name=year]", datepicker).val();
			var d = new Date(y, m, 1);
			var startindex = d.getDay()-1;
			if(startindex < 0 ) startindex = 6;
			var numdays = monthlengths[m];
			
			// http://en.wikipedia.org/wiki/Leap_year
			if (1 == m && ((y%4 == 0 && y%100 != 0) || y%400 == 0)) numdays = 29;
			
			
			// test for end dates (instead of just a year range)
			if (opts.startdate.constructor == Date) {
				var startMonth = opts.startdate.getMonth();
				var startDate = opts.startdate.getDate();
			}
			if (opts.enddate.constructor == Date) {
				var endMonth = opts.enddate.getMonth();
				var endDate = opts.enddate.getDate();
			}
			
			
			
			// walk through the index and populate each cell, binding events too
			for (var i = 0; i < numdays; i++) {
				
				var cell = jQuery(cells.get(i+startindex)).removeClass('chosen');
				
				// test that the date falls within a range, if we have a range
				if ( 
					(yr || ((!startDate && !startMonth) || ((i+1 >= startDate && mo == startMonth) || mo > startMonth))) &&
					(yr + 1 < yrs || ((!endDate && !endMonth) || ((i+1 <= endDate && mo == endMonth) || mo < endMonth)))) {
				
					cell
						.text(i+1)
						.addClass('date')
						.hover(
							function () { jQuery(this).addClass('over'); },
							function () { jQuery(this).removeClass('over'); })
						.click(function () {
							var chosenDateObj = new Date(jQuery("select[name=year]", datepicker).val(), jQuery("select[name=month]", datepicker).val(), jQuery(this).text());
							closeIt(el, datepicker, chosenDateObj);
						});
						
					// highlight the previous chosen date
					if (i+1 == chosendate.getDate() && m == chosendate.getMonth() && y == chosendate.getFullYear()) cell.addClass('chosen');
				}
			}
		}
		
		/** closes the datepicker **/
		// sets the currently matched input element's value to the date, if one is available
		// remove the table element from the DOM
		// indicate that there is no datepicker for the currently matched input element
		function closeIt (el, datepicker, dateObj) { 
			if (dateObj && dateObj.constructor == Date)
				el.val(jQuery.fn.simpleDatepicker.formatOutput(dateObj));
			datepicker.remove();
			datepicker = null;
			
			if(el.get(0).name == "entrada") cambiaSalida(el.get(0).value);
			
			jQuery.data(el.get(0), "simpleDatepicker", { hasDatepicker : false });
		}

        // iterate the matched nodeset
        return this.each(function() {
			
            // functions and vars declared here are created for each matched element. so if
            // your functions need to manage or access per-node state you can defined them
            // here and use $this to get at the DOM element
			
			if ( jQuery(this).is('input') && 'text' == jQuery(this).attr('type')) {

				var datepicker; 
				jQuery.data(jQuery(this).get(0), "simpleDatepicker", { hasDatepicker : false });
				
				// open a datepicker on the click event
				jQuery(this).click(function (ev) {
											 
					var $this = jQuery(ev.target);
					
					if (false == jQuery.data($this.get(0), "simpleDatepicker").hasDatepicker) {
						
						// store data telling us there is already a datepicker
						jQuery.data($this.get(0), "simpleDatepicker", { hasDatepicker : true });
						
						// validate the form's initial content for a date
						var initialDate = $this.val();
						var auxDate = initialDate.split("/");
						initialDate = auxDate[0] + "/" + auxDate[1] + "/" +auxDate[2];		
						if (initialDate && dateRegEx.test(initialDate)) {
							var chosendate = new Date();
							chosendate.setFullYear(auxDate[2],auxDate[1]-1,auxDate[0]);
						} else if (opts.chosendate.constructor == Date) {
							var chosendate = opts.chosendate;
						} else if (opts.chosendate) {
							var chosendate = new Date(opts.chosendate);
						} else {
							var chosendate = today;
						}
						
						//chosendate = new Date(chosendate.format("dd/mm/yyyy"));
						
						// insert the datepicker in the DOM
						datepicker = newDatepickerHTML();
						jQuery("body").prepend(datepicker);
						
						// position the datepicker
						var elPos = findPosition($this.get(0));
						var x = (parseInt(opts.x) ? parseInt(opts.x) : 0) + elPos[0];
						var y = (parseInt(opts.y) ? parseInt(opts.y) : 0) + elPos[1];
						jQuery(datepicker).css({ position: 'absolute', left: x, top: y });
					
						// bind events to the table controls
						jQuery("span", datepicker).css("cursor","pointer");
						jQuery("select", datepicker).bind('change', function () { loadMonth (null, $this, datepicker, chosendate); });
						jQuery("span.prevMonth", datepicker).click(function (e) { loadMonth (e, $this, datepicker, chosendate); });
						jQuery("span.nextMonth", datepicker).click(function (e) { loadMonth (e, $this, datepicker, chosendate); });
						jQuery("span.today", datepicker).click(function () { closeIt($this, datepicker, new Date()); });
						jQuery("span.close", datepicker).click(function () { closeIt($this, datepicker); });
						
						// set the initial values for the month and year select fields
						// and load the first month
						jQuery("select[name=month]", datepicker).get(0).selectedIndex = chosendate.getMonth();
						
						jQuery("select[name=year]", datepicker).get(0).selectedIndex = Math.max(0, chosendate.getFullYear() - opts.startyear);
						loadMonth(null, $this, datepicker, chosendate);
					}
					
				});
			}

        });

    };

    // finally, I like to expose default plugin options as public so they can be manipulated.  one
    // way to do this is to add a property to the already-public plugin fn

	jQuery.fn.simpleDatepicker.formatOutput = function (dateObj) {
//		return (dateObj.getMonth() + 1) + "/" + dateObj.getDate() + "/" + dateObj.getFullYear();
			
		return strpad(dateObj.getDate()) + "/" + strpad(dateObj.getMonth() + 1) + "/" + dateObj.getFullYear();	
	};
	
	jQuery.fn.simpleDatepicker.defaults = {
		// date string matching /^\d{1,2}\/\d{1,2}\/\d{2}|\d{4}$/
		chosendate : today,
		
		// date string matching /^\d{1,2}\/\d{1,2}\/\d{2}|\d{4}$/
		// or four digit year
		startdate : today.getFullYear(), 
		enddate : today.getFullYear() + 1,
		
		// offset from the top left corner of the input element
		x : 18, // must be in px
		y : 18 // must be in px
	};

})(jQuery);
