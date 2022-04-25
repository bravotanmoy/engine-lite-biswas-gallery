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