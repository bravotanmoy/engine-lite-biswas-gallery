/* php.js */

function urlencode(str) {
	str = (str + '')
			.toString();

	return encodeURIComponent(str)
			.replace(/!/g, '%21')
			.replace(/'/g, '%27')
			.replace(/\(/g, '%28')
			.
			replace(/\)/g, '%29')
			.replace(/\*/g, '%2A')
			.replace(/%20/g, '+');
}

window.urlencode = urlencode;

function http_build_query(formdata, numeric_prefix, arg_separator) {
	var value, key, tmp = [],
			that = this;

	var _http_build_query_helper = function (key, val, arg_separator) {
		var k, tmp = [];
		if (val === true) {
			val = '1';
		} else if (val === false) {
			val = '0';
		}
		if (val != null) {
			if (typeof val === 'object') {
				for (k in val) {
					if (val[k] != null) {
						tmp.push(_http_build_query_helper(key + '[' + k + ']', val[k], arg_separator));
					}
				}
				return tmp.join(arg_separator);
			} else if (typeof val !== 'function') {
				return that.urlencode(key) + '=' + that.urlencode(val);
			} else {
				throw new Error('There was an error processing for http_build_query().');
			}
		} else {
			return '';
		}
	};

	if (!arg_separator) {
		arg_separator = '&';
	}
	for (key in formdata) {
		value = formdata[key];
		if (numeric_prefix && !isNaN(key)) {
			key = String(numeric_prefix) + key;
		}
		var query = _http_build_query_helper(key, value, arg_separator);
		if (query !== '') {
			tmp.push(query);
		}
	}

	return tmp.join(arg_separator);
}

function parse_str(str, array) {
	var strArr = String(str)
			.replace(/^&/, '')
			.replace(/&$/, '')
			.split('&'),
			sal = strArr.length,
			i, j, ct, p, lastObj, obj, lastIter, undef, chr, tmp, key, value,
			postLeftBracketPos, keys, keysLen,
			fixStr = function (str) {
				return decodeURIComponent(str.replace(/\+/g, '%20'));
			};

	if (!array) {
		array = this.window;
	}

	for (i = 0; i < sal; i++) {
		tmp = strArr[i].split('=');
		key = fixStr(tmp[0]);
		value = (tmp.length < 2) ? '' : fixStr(tmp[1]);

		while (key.charAt(0) === ' ') {
			key = key.slice(1);
		}
		if (key.indexOf('\x00') > -1) {
			key = key.slice(0, key.indexOf('\x00'));
		}
		if (key && key.charAt(0) !== '[') {
			keys = [];
			postLeftBracketPos = 0;
			for (j = 0; j < key.length; j++) {
				if (key.charAt(j) === '[' && !postLeftBracketPos) {
					postLeftBracketPos = j + 1;
				} else if (key.charAt(j) === ']') {
					if (postLeftBracketPos) {
						if (!keys.length) {
							keys.push(key.slice(0, postLeftBracketPos - 1));
						}
						keys.push(key.substr(postLeftBracketPos, j - postLeftBracketPos));
						postLeftBracketPos = 0;
						if (key.charAt(j + 1) !== '[') {
							break;
						}
					}
				}
			}
			if (!keys.length) {
				keys = [key];
			}
			for (j = 0; j < keys[0].length; j++) {
				chr = keys[0].charAt(j);
				if (chr === ' ' || chr === '.' || chr === '[') {
					keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
				}
				if (chr === '[') {
					break;
				}
			}

			obj = array;
			for (j = 0, keysLen = keys.length; j < keysLen; j++) {
				key = keys[j].replace(/^['"]/, '')
						.replace(/['"]$/, '');
				lastIter = j !== keys.length - 1;
				lastObj = obj;
				if ((key !== '' && key !== ' ') || j === 0) {
					if (obj[key] === undef) {
						obj[key] = {};
					}
					obj = obj[key];
				} else { // To insert new dimension
					ct = -1;
					for (p in obj) {
						if (obj.hasOwnProperty(p)) {
							if (+p > ct && p.match(/^\d+$/g)) {
								ct = +p;
							}
						}
					}
					key = ct + 1;
				}
			}
			lastObj[key] = value;
		}
	}
}


// --> productFilter
(function(options) {
	
	// Default'in?? konfig??racija
	this.opt = {
		replaceTemplate: 'content_types/products/listing.tpl',
		replaceContainer: 'div.pf-replace-container',
		filterContainer: '#filter',
		filterMenu: '#filter_menu',
		filterToggleButton: '#filter .pf-toggle-menu',
		filterClearAllButton: '#filter .pf-clear-all',
		filterClearButton: '#filter .pf-clear',
		filterRemoveButton: '#filter .pf-remove',
		filterSetButton: '#filter .pf-set'
	};
	
	this.filterHash = '';
	
	this.initialized = false;
	
	this.uri = document.location.search;
	
	this.dopopstate = false;
	
	// Atidaro/u??daro filtr?? meniu
	this.toggleMenu = function (show, transition) {
		if (show === undefined) {
			show = !$(this.opt.filterContainer).hasClass('is_shown');
		}
		if (transition === undefined) {
			transition = true;
		}
		if (transition) {
			$(this.opt.filterContainer).removeClass('no-transition');
		} else {
			$(this.opt.filterContainer).addClass('no-transition');
		}
		if (show) {
			$(this.opt.filterContainer).addClass('is_shown');
		} else {
			$(this.opt.filterContainer).removeClass('is_shown');
		}
	};
	
	// Perkrauna 'filterContainer'
	this.reload = function (url, params) {
		if (url === undefined) url = this.uri;
		if (params === undefined) params = {};
		params.display = this.opt.replaceTemplate;
		var pf = this;
		var mobile_collapse_opened;
		$.ajax({
			url: url ? url : '?',
			data: params,
			beforeSend: function() {
				$('#ajax_loader').fadeIn();
				//$(pf.opt.replaceContainer).css('opacity',0.5);
				// atsimenam visas isskleistas filtru grupes
				mobile_collapse_opened = [];
				$(pf.opt.filterContainer).find('.list-collapse-mobile.open').each(function(){
					mobile_collapse_opened.push(this.id);
				});
			},
			success: function(data){
				var show_menu = $(pf.opt.filterContainer).hasClass('is_shown');
				$(pf.opt.replaceContainer).replaceWith(data).show();
				if (typeof init_components != 'undefined') {
					init_components($(pf.opt.replaceContainer));
				}
				for (i in mobile_collapse_opened) {
					$('#'+mobile_collapse_opened[i]).addClass('open');
				}
				pf.toggleMenu(show_menu, false);
				//$(pf.opt.replaceContainer).css('opacity',1);
				$('#ajax_loader').fadeOut();
			}
		});
	}

	// Pagal 'filterHash' gr????ina filtr?? reik??mes JSON formatu.
	this.read = function () {
		// filtrai atskirti ';'
		var filter = {};
		var vars = this.filterHash ? this.filterHash.split(";") : [];
		for (var i = 0; i < vars.length; i++) {
			// filtro ID nuo reik??mi?? atskirtas ':'
			var vars2 = vars[i].split(":");
			if (vars2[1] !== undefined && vars2[1]!=='') {
				// kelios reik??m??s atskirtos ','
				filter[vars2[0]] = vars2[1].split(",");
			}
		}
		return filter;
	};
	
	this.updateURI = function (params) {
		if (typeof params === "string") {
			this.uri = params;
		} else
		if (typeof params === "object") {
			var vars = {};
			parse_str(this.uri.replace(/^\?/,''), vars);
			$.extend(vars, params);
			this.uri = '?'+http_build_query(vars);
		}
		
		var url = window.location.href.replace(/\?.*$/, '') + uri;
		window.history.pushState({}, '', url);
		//window.history.replaceState({}, '', url);
		if ('state' in window.history && window.history.state !== null) {	
			this.dopopstate = true;
			this.popstate();
		}
	}

	// Pagal paduotas filtr?? reik??mes perra??o 'filterHash'.
	// filter:	Filtr?? reik??m??s (JSON)
	// reload:	Ar i??kart vykdysim ajax request'???
	//			default: true
	this.write = function (filter, reload) {
		if (reload === undefined) reload = true;
		
		// perra??om 'filterHash', pagal paduotas filtr?? reik??mes ('filter')
		var vars = new Array();
		for (i in filter) {
			if (filter[i].length) {
				vars.push(i+':'+filter[i].join(','));
			}
		}
		this.filterHash = vars.join(';');
		
		// Pakeiciam reik??m?? address bar'e
		var uri = this.uri
			.replace(/([&?])filter=.*?(&|$)/,'$1')
			.replace(/([&?])page=.*?(&|$)/,'$1')
			.replace(/&$/,'')
			.replace(/^\?$/,'');
		if (filterHash) {
			uri += (uri ? '&' : '?') + 'filter=' + filterHash;
		}
		this.updateURI(uri);
		// Jei reikia, vykdom ajax request'??
		if (reload) this.reload();
	};
	
	// Nustato konkretaus filtro reik??m??
	// type:	Filtro ID
	// values:	Reik??m??s atskirtos kableliais
	// reload:	Ar i??kart vykdysim ajax request'???
	//			default: true
	this.set = function (type, values, reload) {
		if (reload === undefined) reload = true;
		var filter = this.read();
		if (filter[type] === undefined) {
			filter[type] = new Array();
		}
		filter[type] = values ? values.toString().split(',') : [];
		this.write(filter, reload);
	};

	// Papildo konkret?? filtr?? viena reik??me
	// type:	filtro ID
	// val:		Reik??m??
	// reload:	Ar i??kart vykdysim ajax request'??? 
	//			Turi reik??m?? tik jeigu nenurodytas 'filter'. 
	//			Default: true.
	// filter:	Filtras kuri kei??iam. Jeigu nenurodytas, imam i?? filterHash
	this.add = function (type, val, reload, filter) {
		var write;
		if (reload === undefined) reload = true;
		if (filter === undefined) {
			// gaunam ir paskui nustatom filtr?? reik??mes per 'filterHash'
			filter = this.read();
			write = true;
		} else {
			// jeigu filtras paduotas per parametr??, 'filterHash' nekeisim.
			write = false;
		}
		if (filter[type] === undefined) {
			filter[type] = new Array();
		}
		filter[type].push(val);
		
		// Jeigu filtro reik??mes ??m??me i?? 'filterHash'...
		if (write) {
			// kei??iam 'filterHash', address bar reik??m??, ir (jei reikia) vykdom ajax request'??
			this.write(filter, reload);
		}
		
		return filter;
	};

	// Pa??alina i?? 'filterHash' vien?? nurodyto filtro nurodyt?? reik??m??.
	// type:	filtro ID
	// val:		reik??m??
	// reload:	ar i??kart vykdysim ajax request'???
	//			default: true
	this.remove = function(type, val, reload) {
		if (reload === undefined) reload = true;
		
		// gaunam filtr?? reik??mes (JSON) pagal 'filterHash'
		var filter = this.read();
		
		// ar egzistuoja nurodytas filtras?
		if (filter[type] == undefined) {
			return;
		}
		
		// pa??alinam nurodyt?? reik??m??
		for (i in filter[type]) {
			if (filter[type][i]==val) {
				filter[type].splice(i,1);
			}
		}
		
		// kei??iam 'filterHash', address bar reik??m??, ir (jei reikia) vykdom ajax request'??
		this.write(filter, reload);
	};

	// Pa??alina i?? 'filterHash' nurodyt?? filtr??.
	// type:	filtro ID
	//			default: true
	// reload:	ar i??kart vykdysim ajax request'???
	this.clear = function (type, reload) {
		if (reload === undefined) reload = true;
		
		// gaunam filtr?? reik??mes (JSON) pagal 'filterHash'
		var filter = this.read();
		if (filter[type] === undefined) {
			return;
		}
		
		// pa??alinam nurodyt?? filtr??
		delete filter[type];
		
		// kei??iam 'filterHash', address bar reik??m??, ir (jei reikia) vykdom ajax request'??
		this.write(filter, reload);
	};

	// I??valo visus filtrus
	// reload:	ar i??kart vykdysim ajax request'???
	//			default: true
	this.clearAll = function (reload) {
		if (reload === undefined) reload = true;
		var filter = {};
		this.write(filter, reload);
	};

	this.popstate = function() {		
		if (this.dopopstate) {
			window.addEventListener("popstate", function(e) {	
				var matches = document.location.search.match(/(?:\?|&)?filter=(.*?)(?:&|$)/);
				window.productFilter.filterHash = matches ? decodeURIComponent(matches[1]) : '';
				window.productFilter.reload(document.location.search);
			});
		}
	}

	// inicializuojam filtr??
	this.init = function(options) {
		if (this.initialized) return;
		this.initialized = true;
		
		// konfiguracija
		$.extend(this.opt, options);
		
		// nuskaitom 'filterHash' i?? address bar'o
		var matches = window.location.search.match(/(?:\?|&)?filter=(.*?)(?:&|$)/);
		this.filterHash = matches ? decodeURIComponent(matches[1]) : '';
		
		// filtru meniu
		var pf = this;
		$(document).on('click', this.opt.filterToggleButton, function(e){
			e.preventDefault();
			pf.toggleMenu();
		});
		
		var pf = this;
		
		// filtro nustatymo mygtukai
		$(document).on('click', this.opt.filterSetButton, function(e){
			e.preventDefault();
			var $this = $(this);
			var type = $this.data('ftype');
			var value = $this.data('fvalue');
			if ($this.is('.active')) {
				pf.remove(type, value);
			} else {
				pf.add(type, value);
			}
		});
		
		// filtro i??valymo mygtukai
		$(document).on('click', this.opt.filterRemoveButton, function(e){
			e.preventDefault();
			var $this = $(this);
			var type = $this.data('ftype');
			var value = $this.data('fvalue');
			pf.remove(type, value);
		});
		
		// filtro i??valymo mygtukai
		$(document).on('click', this.opt.filterClearButton, function(e){
			e.preventDefault();
			var type = $(this).data('ftype');
			if (type === undefined) return;
			if (type=='price') {
				pf.clear('price0', false);
				pf.clear('price1', false);
				pf.reload();
			} else {
				pf.clear(type, true);
			}
		});
		
		// filtro i??valymo mygtukai
		$(document).on('click', this.opt.filterClearAllButton, function(e){
			e.preventDefault();
			pf.clearAll(true);
		});
		
		this.popstate();
		
	};
	
	window.productFilter = this;
})();
// <-- productFilter