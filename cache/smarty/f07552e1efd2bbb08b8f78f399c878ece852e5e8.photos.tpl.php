<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:08
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/photos.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f8eb6fa8_47600258',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1ed797323f3eaae9dbe6ab252ce10fb3fc456943' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/photos.tpl',
      1 => 1647450378,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_623220f8eb6fa8_47600258 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="products_photos" class="clearfix text-center">
			<a class="text-center" id="single_image" href="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg">
			<img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg">
			<img src="http://biswas.local/images/loading.gif" class="loading-images" alt=""/>
		</a>
	

</div>


<div class="products_photos_list owl-carousel owl-theme">

						<div class="item" onclick="focusMainImg(0)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/209.jpg"></div>
					<div class="item" onclick="focusMainImg(1)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/141.jpg"></div>
					<div class="item" onclick="focusMainImg(2)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/137.jpg"></div>
					<div class="item" onclick="focusMainImg(3)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/160.jpg"></div>
					<div class="item" onclick="focusMainImg(4)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/207.jpg"></div>
					<div class="item" onclick="focusMainImg(5)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/215.jpg"></div>
					<div class="item" onclick="focusMainImg(6)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/201.jpg"></div>
					<div class="item" onclick="focusMainImg(7)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/192.jpg"></div>
					<div class="item" onclick="focusMainImg(8)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/145.jpg"></div>
					<div class="item" onclick="focusMainImg(9)"><img class="img-fluid" alt="" src="https://gallery-api.engine.lt/api/gallery/catalog-image/218.jpg"></div>
			

</div>


<script language="JavaScript" type="text/JavaScript">

// Owl Carousel Configaration
$('.owl-carousel').owlCarousel({
	items: 7,
    margin:10,
    nav:true,
	dots: false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})

// Fancybox Configaration
$(document).ready(function() {

	/* This is basic - uses default settings */
	$("a#single_image").fancybox();
	
	/* Using custom settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});

	/* Apply fancybox to multiple items */
	
	$("a.group").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	true
	});
	
});


// Custom Script For Main Product Image View
let imagesList = null;
console.dir(imagesList);

function focusMainImg(e){
	let itemNumber = e;
	let produceImage = imagesList[itemNumber];
	$('#products_photos a').attr("href", produceImage);
	$('#products_photos img.img-fluid').attr("src", produceImage);

	$('#products_photos .loading-images').fadeIn();

	$("#products_photos img.img-fluid").on("load", function() {
		$('#products_photos .loading-images').fadeOut();
	}).attr("src", produceImage);

}

</script><?php }
}
