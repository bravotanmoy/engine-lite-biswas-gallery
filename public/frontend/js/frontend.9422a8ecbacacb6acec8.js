/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/scss/frontend.scss":
/*!**************************************!*\
  !*** ./resources/scss/frontend.scss ***!
  \**************************************/
/***/ (() => {

throw new Error("Module build failed (from ./node_modules/mini-css-extract-plugin/dist/loader.js):\nModuleBuildError: Module build failed (from ./node_modules/sass-loader/dist/cjs.js):\nSassError: Expected identifier.\n    ╷\n156 │       .\n    │        ^\n    ╵\n  resources/scss/frontend/components/products/_detailed.scss 156:8  @import\n  resources/scss/frontend/_products.scss 5:9                        @import\n  resources/scss/frontend/main.scss 10:9                            @import\n  resources/scss/frontend.scss 3:9                                  root stylesheet\n    at processResult (/opt/lampp/htdocs/engine-lite-biswas/node_modules/webpack/lib/NormalModule.js:758:19)\n    at /opt/lampp/htdocs/engine-lite-biswas/node_modules/webpack/lib/NormalModule.js:860:5\n    at /opt/lampp/htdocs/engine-lite-biswas/node_modules/loader-runner/lib/LoaderRunner.js:399:11\n    at /opt/lampp/htdocs/engine-lite-biswas/node_modules/loader-runner/lib/LoaderRunner.js:251:18\n    at context.callback (/opt/lampp/htdocs/engine-lite-biswas/node_modules/loader-runner/lib/LoaderRunner.js:124:13)\n    at /opt/lampp/htdocs/engine-lite-biswas/node_modules/sass-loader/dist/index.js:62:7\n    at Function.call$2 (/opt/lampp/htdocs/engine-lite-biswas/node_modules/sass/sass.dart.js:99051:16)\n    at render_closure1.call$2 (/opt/lampp/htdocs/engine-lite-biswas/node_modules/sass/sass.dart.js:84557:12)\n    at _RootZone.runBinary$3$3 (/opt/lampp/htdocs/engine-lite-biswas/node_modules/sass/sass.dart.js:29579:18)\n    at _FutureListener.handleError$1 (/opt/lampp/htdocs/engine-lite-biswas/node_modules/sass/sass.dart.js:28099:21)");

/***/ }),

/***/ "./resources/js/ajax_submit.js":
/*!*************************************!*\
  !*** ./resources/js/ajax_submit.js ***!
  \*************************************/
/***/ (() => {

/**
 * Submit'ina forma, ir jeigu buvo klaidu - jas parodo, jei ne - redirektina. Daugiausiai naudojama backende.
 */ 

function ajaxLoaderShow(selector) {
	if ($('#ajax_loader').length) {
		$('#ajax_loader').show();
	} else {
		height = $(selector).innerHeight();
		width = $(selector).innerWidth();
		$(selector).prepend('<div style="width:'+width+'px; height:'+height+'px;" class="ajax_overlay">&nbsp;<\/div>');
	}
}
window.ajaxLoaderShow = ajaxLoaderShow;

function ajaxLoaderHide(selector) {
	$('#ajax_loader').hide();
	$(selector+' .ajax_overlay').remove();
}

window.ajaxLoaderHide = ajaxLoaderHide;


function setAjaxSubmit(selector, config) {
	
	if (typeof(config)=='undefined') {
		var config = new Array();
	}
	if (typeof(config['error_tag'])=='undefined') {
		config['error_tag'] = 'span';
	}
	if (typeof(config['error_class'])=='undefined') {
		config['error_class'] = 'field_error_message';
	}
	
	$(document).ready(function() { 
		if (typeof(tinyMCE)=='object') {
			$(selector).bind('form-pre-serialize', function(e) {
			    tinyMCE.triggerSave();
			});		
		}
		
		form_url = $(selector).attr('action');
		if (form_url.match(/\?/)) {
			form_url += '&';
		} else {
			form_url += '?';
		}
		form_url += 'ajax_submit';
		
		var options = { 
			beforeSubmit : function() {
				ajaxLoaderShow(selector);
			},
			success: function(responseText, statusText) {
				ajaxLoaderHide(selector);
				if (responseText) {
				 	$(config['error_tag']+'.'+config['error_class']).remove();
				 	$(selector+' .alert-danger').remove();
					$(selector+' .error').removeClass('error');
					var errors = Array();
					try{
						errors = $.evalJSON(responseText);
					}catch(error){
						alert(validationErrorMsg +'\r\n'+ responseText);
					}
				 	error_str = "";
				 	if (typeof(errors.return_url)!='undefined') {
				 		document.location = errors.return_url;
			 		} else
				 	for (i in errors) {
				 		el = $(selector+' [name='+i+']:first');
			 			if (!el.length) {
			 				msg = '';
			 				if (errors[i].label) {
			 					msg += errors[i].label+': ';
			 				}
			 				msg += errors[i].message;
			 				error_str += (msg+'<br/>');
			 			} else {
					 		el.addClass('error');
					 		el.parent().append('<'+config['error_tag']+' class="'+config['error_class']+'">'+errors[i].message+'<\/'+config['error_tag']+'>');
				 		}
				 	}
				 	$(selector+' .error:first').focus();
				 	if (error_str) {
				 		$(selector).prepend('<div class="alert alert-danger">'+error_str+'</div>');
				 	}
			 	} else {
			 		document.location = $(selector+' input[name=return_url]').val();
			 	}
			}, 
			url: form_url  // override for form's 'action' attribute 
		}; 
		// bind form using 'ajaxForm' 
		$(selector).ajaxForm(options);
	}); 
}

window.setAjaxSubmit = setAjaxSubmit;

/**
 * Submit'ina forma, ir atvaizduoja rezultata (template'a).
 */
 
function submitAndReplace(form_selector, area_selector, template) {
	if ($(form_selector).length) {
		var url = $(form_selector)[0].action;
		if (url.match(/\?/)) {
			url += '&display='+template;
		} else {
			url += '?display='+template;
		}
		var options = {
			beforeSubmit : function() {
				ajaxLoaderShow(area_selector);
			},
			success : function(responseText){
				ajaxLoaderHide(area_selector);
				$(area_selector).empty().before(responseText).remove();
				// $(area_selector).html(responseText);
				//$(area_selector).empty().html( $(responseText).find(area_selector).html() );
			},
			url : url
		};
		$(form_selector).ajaxForm(options);
	}
}

window.submitAndReplace = submitAndReplace;

function ajaxLinks(link_selector, area_selector, template) {
	$(function(){
		$(link_selector).click(function(){
			url = this.href;
			url = url.replace(/#.*$/,'');
			if (url.match(/\?/)) {
				url += '&';
			} else {
				url += '?';
			}
			url += 'display='+template;
			$.ajax({
				beforeSend : function() {
					ajaxLoaderShow(area_selector);
				},
				url: url,
				success: function(html){
					ajaxLoaderHide(area_selector);
					$(area_selector).empty().before(html).remove();
				}
			});
			return false;
		});
	});	
}

window.ajaxLinks = ajaxLinks;

/*
 * Dinamiskai atnaujinima (perkrauna) puslapio dali
 */
 
function ajaxReplace(area_selector, template) {
	$(function(){
		$.ajax({
			beforeSend : function() {
				ajaxLoaderShow(area_selector);
			},
			url : '?display='+template,
			success: function(html){
				ajaxLoaderHide(area_selector);
			}
		});
		return false;
	});	
}

window.ajaxReplace = ajaxReplace;

/***/ }),

/***/ "./resources/js/frontend/filters.js":
/*!******************************************!*\
  !*** ./resources/js/frontend/filters.js ***!
  \******************************************/
/***/ (() => {

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
	
	// Default'inė konfigūracija
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
	
	// Atidaro/uždaro filtrų meniu
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

	// Pagal 'filterHash' grąžina filtrų reikšmes JSON formatu.
	this.read = function () {
		// filtrai atskirti ';'
		var filter = {};
		var vars = this.filterHash ? this.filterHash.split(";") : [];
		for (var i = 0; i < vars.length; i++) {
			// filtro ID nuo reikšmių atskirtas ':'
			var vars2 = vars[i].split(":");
			if (vars2[1] !== undefined && vars2[1]!=='') {
				// kelios reikšmės atskirtos ','
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

	// Pagal paduotas filtrų reikšmes perrašo 'filterHash'.
	// filter:	Filtrų reikšmės (JSON)
	// reload:	Ar iškart vykdysim ajax request'ą?
	//			default: true
	this.write = function (filter, reload) {
		if (reload === undefined) reload = true;
		
		// perrašom 'filterHash', pagal paduotas filtrų reikšmes ('filter')
		var vars = new Array();
		for (i in filter) {
			if (filter[i].length) {
				vars.push(i+':'+filter[i].join(','));
			}
		}
		this.filterHash = vars.join(';');
		
		// Pakeiciam reikšmę address bar'e
		var uri = this.uri
			.replace(/([&?])filter=.*?(&|$)/,'$1')
			.replace(/([&?])page=.*?(&|$)/,'$1')
			.replace(/&$/,'')
			.replace(/^\?$/,'');
		if (filterHash) {
			uri += (uri ? '&' : '?') + 'filter=' + filterHash;
		}
		this.updateURI(uri);
		// Jei reikia, vykdom ajax request'ą
		if (reload) this.reload();
	};
	
	// Nustato konkretaus filtro reikšmę
	// type:	Filtro ID
	// values:	Reikšmės atskirtos kableliais
	// reload:	Ar iškart vykdysim ajax request'ą?
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

	// Papildo konkretų filtrą viena reikšme
	// type:	filtro ID
	// val:		Reikšmė
	// reload:	Ar iškart vykdysim ajax request'ą? 
	//			Turi reikšmę tik jeigu nenurodytas 'filter'. 
	//			Default: true.
	// filter:	Filtras kuri keičiam. Jeigu nenurodytas, imam iš filterHash
	this.add = function (type, val, reload, filter) {
		var write;
		if (reload === undefined) reload = true;
		if (filter === undefined) {
			// gaunam ir paskui nustatom filtrų reikšmes per 'filterHash'
			filter = this.read();
			write = true;
		} else {
			// jeigu filtras paduotas per parametrą, 'filterHash' nekeisim.
			write = false;
		}
		if (filter[type] === undefined) {
			filter[type] = new Array();
		}
		filter[type].push(val);
		
		// Jeigu filtro reikšmes ėmėme iš 'filterHash'...
		if (write) {
			// keičiam 'filterHash', address bar reikšmę, ir (jei reikia) vykdom ajax request'ą
			this.write(filter, reload);
		}
		
		return filter;
	};

	// Pašalina iš 'filterHash' vieną nurodyto filtro nurodytą reikšmę.
	// type:	filtro ID
	// val:		reikšmė
	// reload:	ar iškart vykdysim ajax request'ą?
	//			default: true
	this.remove = function(type, val, reload) {
		if (reload === undefined) reload = true;
		
		// gaunam filtrų reikšmes (JSON) pagal 'filterHash'
		var filter = this.read();
		
		// ar egzistuoja nurodytas filtras?
		if (filter[type] == undefined) {
			return;
		}
		
		// pašalinam nurodytą reikšmę
		for (i in filter[type]) {
			if (filter[type][i]==val) {
				filter[type].splice(i,1);
			}
		}
		
		// keičiam 'filterHash', address bar reikšmę, ir (jei reikia) vykdom ajax request'ą
		this.write(filter, reload);
	};

	// Pašalina iš 'filterHash' nurodytą filtrą.
	// type:	filtro ID
	//			default: true
	// reload:	ar iškart vykdysim ajax request'ą?
	this.clear = function (type, reload) {
		if (reload === undefined) reload = true;
		
		// gaunam filtrų reikšmes (JSON) pagal 'filterHash'
		var filter = this.read();
		if (filter[type] === undefined) {
			return;
		}
		
		// pašalinam nurodytą filtrą
		delete filter[type];
		
		// keičiam 'filterHash', address bar reikšmę, ir (jei reikia) vykdom ajax request'ą
		this.write(filter, reload);
	};

	// Išvalo visus filtrus
	// reload:	ar iškart vykdysim ajax request'ą?
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

	// inicializuojam filtrą
	this.init = function(options) {
		if (this.initialized) return;
		this.initialized = true;
		
		// konfiguracija
		$.extend(this.opt, options);
		
		// nuskaitom 'filterHash' iš address bar'o
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
		
		// filtro išvalymo mygtukai
		$(document).on('click', this.opt.filterRemoveButton, function(e){
			e.preventDefault();
			var $this = $(this);
			var type = $this.data('ftype');
			var value = $this.data('fvalue');
			pf.remove(type, value);
		});
		
		// filtro išvalymo mygtukai
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
		
		// filtro išvalymo mygtukai
		$(document).on('click', this.opt.filterClearAllButton, function(e){
			e.preventDefault();
			pf.clearAll(true);
		});
		
		this.popstate();
		
	};
	
	window.productFilter = this;
})();
// <-- productFilter

/***/ }),

/***/ "./resources/js/frontend/frontend.js":
/*!*******************************************!*\
  !*** ./resources/js/frontend/frontend.js ***!
  \*******************************************/
/***/ (() => {

// Media queries breakpoints
var screen_xs = 480;
var screen_xs_min = screen_xs;
var screen_phone = screen_xs_min;
var screen_sm = 768;
var screen_sm_min = screen_sm;
var screen_tablet = screen_sm_min;
var screen_md = 992;
var screen_md_min = screen_md;
var screen_desktop = screen_md_min;
var screen_lg = 1200;
var screen_lg_min = screen_lg;
var screen_lg_desktop = screen_lg_min;
var screen_xs_max = (screen_sm_min - 1);
var screen_sm_max = (screen_md_min - 1);
var screen_md_max = (screen_lg_min - 1);

function get_vw() {
	return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
}

function get_vh() {
	return Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
}

function ajaxnav(params) {
    // ajaxnav(url, container, template, update_address, append_history)
    if (typeof params !== 'object') {
        params = {
            url: arguments[0],
            container: arguments[1],
            template: arguments[2],
            update_address: arguments[3] !== undefined ? arguments[3] : true,
            append_history: arguments[4] !== undefined ? arguments[4] : true,
        }
    }

	// uzpidom default'iniais parametrais
	params = $.extend({
		update_address: true,
		append_history: true,
		method: 'GET',
		data: {},
		error: function(data, textStatus, jqXHR) {
			$('#ajax_loader').hide();
			if (params.callback !== undefined) {
				params.callback(data, textStatus, jqXHR);
			}
		},
		success: function(data, textStatus, jqXHR) {
			$('#ajax_loader').hide();
			if (typeof data == 'object') {
				if (data.reload === true) {
					$('#ajax_loader').show();
					window.location.href = data.url;
				} else {
					ajaxnav($.extend({
						container: params.container,
						template: params.template,
						update_address: params.update_address,
						append_history: params.append_history
					}, data));
				}
			} else
			if (typeof data == 'string') {
				if (params.container !== undefined) {
					$data = $(data);
					if (!$data.attr('id')) {
						$data.attr('id', 'id-' + Math.random().toString(36).substr(2, 16));
					}
					$previous = $(params.container).replaceWith($data);
					init_components($data);
                    if (params.update_address) {
                        var pso = {
                            template: params.template,
                            container: params.container
                        }
                        var final_url = jqXHR.getResponseHeader('X-AJAXNAV-URL');
                        if (!final_url) final_url = params.url;
                        if (params.append_history) {
                            // window.history.replaceState(pso, '', window.location);
                            window.history.pushState(pso,'', final_url);
                        } else {
                            window.history.replaceState(pso,'', final_url);
                        }
                    }
				}
				if (params.callback !== undefined) {
					params.callback(data, textStatus, jqXHR);
				}
			}
		}
	}, params);

	// vykdom ajax request'a
	$.ajax({
		url: params.url + (!params.url.match(/\?/) ? '?' : (!params.url.match(/&$/) ? '&' : '')) + 'display=' + params.template,
		method: params.method,
		data: params.data,
		success: params.success,
		beforeSend: function() {
			$('#ajax_loader').show();
		},
	});
}

window.ajaxnav = ajaxnav;

function init_fancybox(context) {
	// fancybox
	$(".fancybox", context).fancybox({
		width : '70%',
		height : '100%',
		autoSize : false,
		fitToView : false
	});
	$('a[rel^="fancybox"]', context).fancybox();
}

function init_tooltips(context) {
	// tooltip
	$('[data-toggle="tooltip"]', context).tooltip();
}

function init_selectpicker(context) {
    // selectpicer
    // selectpicer
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        $('.selectpicker', context).selectpicker('mobile');
    } else {
        $('.selectpicker', context).selectpicker();
    }
    /*{
        'width':'100%',
    }).addClass('select-default').selectpicker('setStyle');*/

}

function init_components(context) {
	init_fancybox(context);
	init_tooltips(context);
	init_selectpicker(context);
}

window.init_components = init_components;

function init_scrollup() {
	if ($('#scrollup').length) {
		$(document).on('click', '#scrollup', function(){
			$("html, body").animate({ scrollTop: 0 }, "slow");
		});
		function check_scrollup() {
			$('#scrollup').stop();
			if ($(window).scrollTop() > 200) {
				$('#scrollup').fadeIn();
			} else {
				$('#scrollup').fadeOut();
			}
		}
		check_scrollup();
		$(window).scroll(check_scrollup);
	}
}

function init_quantity_control() {
	// quantity-control ([-][?][+])
	$(document).on('click', 'div.quantity_control .plus, div.quantity_control .minus', function(e) {
		var $input = $(this).parents('.quantity_control').find('input');
		var val = parseInt($input.val()) || 1;
		if ($(this).is('.minus')) {
			val--;
		} else {
			val++;
		}
		var min_val = $input.data('min') !== undefined ? $input.data('min') : 1;
		var max_val = $input.data('max') !== undefined ? $input.data('max') : 999;
		if (val < min_val) val = min_val;
		if (val > max_val) val = max_val;
		$input.val(val);
	});
}

function init_ajaxnav() {
	// ajaxnav
	$(document).on('click', '[data-ajaxnav="true"]:not(form)', function(e){
		var url = this.href !== undefined ? this.href : $(this).data('ajaxnav-url');
        var callback = $(this).data('ajaxnav-callback');
		if (url) {
			e.preventDefault();
			ajaxnav({
				url: url,
				container: $(this).data('ajaxnav-container'), 
				template: $(this).data('ajaxnav-template'),
                callback: function(){
                    if (typeof window[callback] == 'function') {
                        window[callback]();
                    }
                }
			});
		}
	});
	
	$(document).on('submit', 'form[data-ajaxnav="true"]', function(e){
		var url = this.action ? this.action : ($(this).data('ajaxnav-url') ? $(this).data('ajaxnav-url') : '');
        var callback = $(this).data('ajaxnav-callback');
		e.preventDefault();
		ajaxnav({
			url: url, 
			method: this.method ? this.method.toUpperCase() : 'GET',
			data: $(this).serialize(), 
			container: $(this).data('ajaxnav-container'), 
			template: $(this).data('ajaxnav-template'),
            callback: function(){
                if (typeof window[callback] == 'function') {
                    window[callback]();
                }
            }
		});
		return false;
	});
	
	window.onpopstate = function(event) {
		if (event.state && event.state.container && event.state.template) {
			ajaxnav(window.location.href, event.state.container, event.state.template, false);
		} else {
			$('#ajax_loader').show();
			window.location.reload();
		}
	};
	
}

function init_list_collapse() {
	function list_collapse($list) {
		$list.toggleClass('open');
	}
	$(document).on('click', 'div.list-collapse > .title', function(e){
		e.preventDefault();
		list_collapse($(this).parent());
	});
	$(document).on('click', 'div.list-collapse-mobile > .title', function(e){
		if (get_vw() <= screen_sm_max) {
			e.preventDefault();
			list_collapse($(this).parent());
		}
	});
	$(document).on('click', 'div.list-collapse-desktop > .title', function(e){
		if (get_vw() >= screen_md_min) {
			e.preventDefault();
			list_collapse($(this).parent());
		}
	});
}

function init_list_dropdown() {
	function list_dropdown_click($list) {
		// jeigu click'as ivyko 100ms po hoverio, reikia uzdaryti drop-down'a
		if ($list.is('.hover') && ($list.data('hovertime') < (new Date()).getTime() - 100)) {
			$list.removeClass('hover');
		} else {
			$list.addClass('hover');
		}
	}
	function list_dropdown_enter($list) {
		$list.addClass('hover').data('hovertime', (new Date()).getTime());
        var submenu = $list.children('.submenu_list');
		
		var maxheight = $(window).height() - (submenu.offset().top - $(document).scrollTop()) - 20;
		submenu.find('> ul').css('max-height', maxheight);
		//submenu.find('> ul').niceScroll({autohidemode:false});

		if (submenu.length > 0) {
			var browser_width = $(document).outerWidth(true);
			var element_width = submenu.width();
			var offset_left = submenu.offset().left;
			var offset_right = browser_width - element_width - offset_left;
			if (offset_right > element_width) {
				submenu.css({'left': '0px', 'right': 'auto'});
			} else {
				submenu.css({'right': '0px', 'left': 'auto'});
			}
		}
	}
	function list_dropdown_leave($list) {
		var hovertime = $list.data('hovertime');
		var hover_delay = $list.data('hover-delay') ? $list.data('hover-delay') : 0;
		var $dropdown = $list;
		setTimeout(function(){
			// patikrinam, ar siuo metu dropdown'as tikrai neturetu buti rodomas
			if ($dropdown.data('hovertime') == hovertime) {
				$dropdown.removeClass('hover');
			}
		}, hover_delay);
	}

	// div.list-dropdown
    if (get_vw() > screen_sm_max) {
        $(document).on('click', 'div.list-dropdown > .title', function(){
            list_dropdown_click($(this).parent());
        });
        $(document).on('mouseenter', 'div.list-dropdown', function(){
            list_dropdown_enter($(this));
        });
        $(document).on('mouseleave', 'div.list-dropdown', function(){
            list_dropdown_leave($(this));
        });
	}

	// div.list-dropdown-mobile
	$(document).on('click', 'div.list-dropdown-mobile > .title', function(){
		if (get_vw() <= screen_sm_max) {
			list_dropdown_click($(this).parent());
		}
	});
	$(document).on('mouseenter', 'div.list-dropdown-mobile', function(){
		if (get_vw() <= screen_sm_max) {
			list_dropdown_enter($(this));
		}
	});
	$(document).on('mouseleave', 'div.list-dropdown-mobile', function(){
		if (get_vw() <= screen_sm_max) {
			list_dropdown_leave($(this));
		}
	});

	// div.list-dropdown-desktop
	$(document).on('click', 'div.list-dropdown-desktop > .title', function(){
		if (get_vw() >= screen_md_min) {
			list_dropdown_click($(this).parent());
		}
	});
	$(document).on('mouseenter', 'div.list-dropdown-desktop', function(){
		if (get_vw() >= screen_md_min) {
			list_dropdown_enter($(this));
		}
	});
	$(document).on('mouseleave', 'div.list-dropdown-desktop', function(){
		if (get_vw() >= screen_md_min) {
			list_dropdown_leave($(this));
		}
	});

    if (get_vw() >= screen_md_min) {
        $('.list-dropdown li.has_child').hover(
            function() {
                $(this).addClass('hover');

                var browser_width = $(document).outerWidth(true);
                var parent_width = $(this).width();
                var child = $(this).children('ul');
                var child_width = child.width();
                var offset_left = $(this).offset().left;
                var offset_right = browser_width - parent_width - offset_left;

                if (offset_right > child_width) {
                    child.css('left', $(this).width() + 'px');
                    child.css('right', 'auto');
                } else {
                    child.css('right', $(this).width() + 'px');
                    child.css('left', 'auto');
                }
            },
            function() {
                $(this).removeClass('hover');
            }
        );

    }
    $(document).on('mouseleave', '.list-dropdown li.hover', function() {
        if (get_vw() >= screen_md_min) {
            var parent = $(this).parent('li');
            parent.removeClass('hover');
        }
    });
}

function update_wishlist_info() {
	$.ajax({
		url: '?display=content_types/wishlist/wishlist_info',
		method: 'post',
		success: function(html) {
			$('#wishlist_info').replaceWith(html);
		}
	});
}

function init_wishlist() {
	$(document).on('click', '.add_to_wishlist', function(e){
		e.preventDefault();
		var el = $(this);
		if(el.data('id') == ""){
            alert ($alert_message);
		} else {
            $.ajax({
                url: '?display=content_types/wishlist/update_wishlist',
                data: {element_id: el.data('id')},
                method: 'post',
                dataType: 'json',
                success: function (json) {
                    if (json.status > 0) {
                        el.addClass('active');
                    } else if (json.status < 0) {
                        el.removeClass('active');
                    }
                    update_wishlist_info();
                }
            });
        }
	});
}

function doModal(content) {
	html =  '<div id="dynamicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-modal" aria-hidden="true">';
	html += '<div class="modal-dialog">';
	html += '<div class="modal-content">';
	html += '<div class="modal-body">';
	html += '<a class="close" data-dismiss="modal">×</a>';
	html += content;
	html += '</div>'; 	// body
	html += '</div>';  	// content
	html += '</div>';  	// dialog
	html += '</div>';  	// modal
	$('body').append(html);
	$("#dynamicModal").modal();
	$("#dynamicModal").modal('show');
	$('#dynamicModal').on('hidden.bs.modal', function (e) {
		$(this).remove();
	});
}

function init_authorize() {
	$(document).on('click', '.need2login', function(e) {
		e.preventDefault();
		ajaxnav({
			url: window.location.href,
			template: 'content_types/customers/authorize',
			callback: function(html) {
				doModal(html);
			}
		});
	});
}

function rotateMenuIcon(x) {
	x.classList.toggle("change");
}

window.rotateMenuIcon = rotateMenuIcon;

$(function(){
	init_components(document);
	init_scrollup();
	init_quantity_control();
	init_ajaxnav();
	init_list_collapse();
	init_list_dropdown();
	init_authorize();
	init_wishlist();
});

// Hide header on scroll down, show on scroll up
$(function () {
	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var navbarHeight = $('nav').outerHeight();

	setTimeout(function() {
		update_nav_top();
	}, 300);

	$(window).on('resize', function() {
		update_nav_top();
	});

	$(window).scroll(function(event){
		didScroll = true;
	});

	if ($(window).width() >= 768) {
		setInterval(function () {
			if (didScroll) {
				hasScrolled();
				didScroll = false;
			}
		}, 250);
	}

	function hasScrolled() {
		var st = $(this).scrollTop();

		// Make sure they scroll more than delta
		if(Math.abs(lastScrollTop - st) <= delta)
			return;

		// If they scrolled down and are past the navbar, add class .nav-up.
		// This is necessary so you never see what is "behind" the navbar.
		if (st > lastScrollTop && st > navbarHeight){
			// Scroll Down
			$('nav').removeClass('nav-down').addClass('nav-up');
		} else {
			// Scroll Up
			if(st + $(window).height() < $(document).height()) {
				$('nav').removeClass('nav-up').addClass('nav-down');
			}
		}

		lastScrollTop = st;
	}

	$(document).on('click', 'div.list-collapse-mobile > .title', function(e){
		if (get_vw() <= screen_sm_max) {
			var header_height = $('header').height();
			if ($('.linepromos').length) {
				var promo_height = $('.linepromos').height();
			} else {
				var promo_height = 0;
			}
			var nav_top = header_height + promo_height;

			$('#pages_mega_menu #mega_menu .list-dropdown .submenu_list').css('top', nav_top + 'px');
		}
	});

	function update_nav_top() {
		var header_height = $('header').height();
		if ($('.linepromos').length) {
			var promo_height = $('.linepromos').height();
		} else {
			var promo_height = 0;
		}
		var nav_top = header_height + promo_height;
		var submenu_height = get_vh();

		if (get_vw() >= screen_md_min) {
			$('nav').css('top', nav_top + 'px');
			content_padding = 0;
		} else {
			$('nav, #pages_mega_menu #mega_menu .list-dropdown .submenu_list, #pages_mega_menu #mega_menu .list-dropdown .row.level-3').css('top', nav_top + 'px');
			content_padding = promo_height;
		}

		$('#content_layout').css('padding-top', content_padding + 'px');
		submenu_height = submenu_height - nav_top;

		if (get_vw() <= screen_sm_max) {
			$('nav').css('height', submenu_height + 'px');
		} else {
			$('nav').css('height', '');
		}
	}
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	__webpack_modules__["./resources/js/ajax_submit.js"]();
/******/ 	__webpack_modules__["./resources/js/frontend/frontend.js"]();
/******/ 	__webpack_modules__["./resources/js/frontend/filters.js"]();
/******/ 	// This entry module doesn't tell about it's top-level declarations so it can't be inlined
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/scss/frontend.scss"]();
/******/ 	
/******/ })()
;
//# sourceMappingURL=frontend.9422a8ecbacacb6acec8.js.map