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