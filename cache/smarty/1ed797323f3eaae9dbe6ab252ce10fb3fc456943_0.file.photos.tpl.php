<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:50
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/photos.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322302e22080_62721545',
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
  'includes' => 
  array (
  ),
),false)) {
function content_62322302e22080_62721545 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['modificationStatus']->value != false) {?>
<div id="products_photos" class="clearfix">
<?php } else { ?>
<div id="products_photos" class="clearfix text-center">
<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['modificationStatus']->value != false) {?>
		<a id="single_image" href="<?php echo $_smarty_tpl->tpl_vars['gallery']->value->dimensions[2]->images[0];?>
">
			<img class="img-fluid" alt="" src="<?php echo $_smarty_tpl->tpl_vars['gallery']->value->dimensions[2]->images[0];?>
">
			<img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/loading.gif" class="loading-images" alt=""/>
		</a>
	<?php } else { ?>
		<a class="text-center" id="single_image" href="<?php echo $_smarty_tpl->tpl_vars['gallery']->value[0]['photo'];?>
">
			<img class="img-fluid" alt="" src="<?php echo $_smarty_tpl->tpl_vars['gallery']->value[0]['photo'];?>
">
			<img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/loading.gif" class="loading-images" alt=""/>
		</a>
	<?php }?>


</div>


<div class="products_photos_list owl-carousel owl-theme">

	<?php if ($_smarty_tpl->tpl_vars['modificationStatus']->value != false) {?>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gallery']->value->dimensions[0]->images, 'productImg', false, 'keyvar');
$_smarty_tpl->tpl_vars['productImg']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['keyvar']->value => $_smarty_tpl->tpl_vars['productImg']->value) {
$_smarty_tpl->tpl_vars['productImg']->do_else = false;
?>
			<div class="item" onclick="focusMainImg(<?php echo $_smarty_tpl->tpl_vars['keyvar']->value;?>
)"><img class="img-fluid" alt="" src="<?php echo $_smarty_tpl->tpl_vars['productImg']->value;?>
"></div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php } else { ?>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['gallery']->value, 'productImg', false, 'keyvar');
$_smarty_tpl->tpl_vars['productImg']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['keyvar']->value => $_smarty_tpl->tpl_vars['productImg']->value) {
$_smarty_tpl->tpl_vars['productImg']->do_else = false;
?>
			<div class="item" onclick="focusMainImg(<?php echo $_smarty_tpl->tpl_vars['keyvar']->value;?>
)"><img class="img-fluid" alt="" src="<?php echo $_smarty_tpl->tpl_vars['gallery']->value[$_smarty_tpl->tpl_vars['keyvar']->value]['photo'];?>
"></div>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php }?>


</div>


<?php echo '<script'; ?>
 language="JavaScript" type="text/JavaScript">

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
let imagesList = <?php echo json_encode($_smarty_tpl->tpl_vars['gallery']->value->dimensions[2]->images);?>
;
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

<?php echo '</script'; ?>
><?php }
}
