<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:33
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/banners/small.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623206593a4771_30687659',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3cbc5acae71dffd47f75de24e1b85ab665ce8cb7' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/banners/small.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623206593a4771_30687659 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['elements']->value) {?>
	<div id="banners_small">
		<div class="container-fluid">
			<div id="banners-small-slider">
				<?php $_smarty_tpl->_assignInScope('visible_mobile_count', 0);?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'element', false, 'key');
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
?>
					<?php if ($_smarty_tpl->tpl_vars['element']->value['visible_mobile']) {?>
						<?php $_smarty_tpl->_assignInScope('visible_mobile_count', $_smarty_tpl->tpl_vars['visible_mobile_count']->value+1);?>
					<?php }?>
					<div class="banner<?php if ($_smarty_tpl->tpl_vars['element']->value['visible_mobile']) {?> mobile<?php }
if ($_smarty_tpl->tpl_vars['element']->value['visible_desktop']) {?> desktop<?php }
if (!$_smarty_tpl->tpl_vars['element']->value['visible_desktop']) {?> d-md-none<?php } elseif (!$_smarty_tpl->tpl_vars['element']->value['visible_mobile']) {?> d-none d-md-block<?php }
if ($_smarty_tpl->tpl_vars['visible_mobile_count']->value == 1 && $_smarty_tpl->tpl_vars['element']->value['visible_mobile']) {?> active<?php }?>">
						<div class="zoom">
							<?php if ($_smarty_tpl->tpl_vars['element']->value['link']) {?>
							<a href="<?php echo $_smarty_tpl->tpl_vars['element']->value['link'];?>
" <?php if ($_smarty_tpl->tpl_vars['element']->value['blank']) {?>target="_blank"<?php }?>>
								<?php }?>
								<div class="banner-image">
									<?php echo $_smarty_tpl->tpl_vars['h']->value->picture($_smarty_tpl->tpl_vars['element']->value['photo'],"width=590&height=394&fill=1&bg_color=FFFFFF&mode=resize&quality=90",((string)$_smarty_tpl->tpl_vars['element']->value['photo']['name']));?>

								</div>
								<?php if (!$_smarty_tpl->tpl_vars['element']->value['hide_text']) {?>
									<div class="banner-title">
										<p class="title"><?php echo $_smarty_tpl->tpl_vars['element']->value['name'];?>
</p><p class="desc"><?php echo nl2br($_smarty_tpl->tpl_vars['element']->value['description']);?>
</p><?php if ($_smarty_tpl->tpl_vars['element']->value['link']) {?><button class="btn btn-custom rounded-0" href="<?php echo $_smarty_tpl->tpl_vars['element']->value['link'];?>
"><?php echo t('Plačiau');?>
</button><?php }?>
									</div>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['element']->value['link']) {?></a><?php }?>
						</div>
					</div>
				<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<div class="owl-nav owl-out-nav"></div>
			</div>
		</div>
		<?php if (false && $_smarty_tpl->tpl_vars['visible_mobile_count']->value > 1) {?>
			<div id="banners_small_navigation">
				<div class="prev"><span class="icon icon-left"></span></div>
				<div class="next"><span class="icon icon-right"></span></div>
			</div>
			<?php echo '<script'; ?>
>
				$(function() {
					$('#banners_small_navigation > div').click(function(){
						var $banners = $('#banners_small div.banner.mobile');
						var $current = $banners.filter('.active');
						var index = $banners.index($current);
						var $prev = $banners.eq(index >= 1 ? index-1 : -1);
						var $next = $banners.eq(index < $banners.length-1 ? index+1 : 0);
						if ($(this).is('.next')) {
							$next.addClass('animating').css({ left:'100%', 'z-index':3 }).animate({ left:0, 'z-index':2 }).addClass('active').removeClass('animating');
							$current.addClass('animating').css({ left:0, 'z-index':3 }).animate({ left:'-100%', 'z-index':1 }).removeClass('active').removeClass('animating');
						} else if ($(this).is('.prev')) {
							$prev.addClass('animating').css({ left:'-100%', 'z-index':3 }).animate({ left:0, 'z-index':2 }).addClass('active').removeClass('animating');
							$current.addClass('animating').css({ left:0, 'z-index':3 }).animate({ left:'100%', 'z-index':1 }).removeClass('active').removeClass('animating');
						}
					});
				});
			<?php echo '</script'; ?>
>
		<?php }?>
		<?php echo '<script'; ?>
>
			$(function(){
				$('#banners-small-slider').each(function () {
					// Add main classes
					$(this).addClass('owl-carousel');
					// Options
					$(this).owlCarousel({
						autoHeight: true,
						loop: false,
						autoplay: false,
						//autoplayTimeout: 5000,
						//autoplayHoverPause: true,
						nav: true,
						navText: [
							"<span class='icon icon-left-big'></span>",
							"<span class='icon icon-right-big'></span>",
						],
						responsiveClass: true,
						navContainer: '#banners_small .owl-out-nav',
						slideBy: 'page',
						dots: false,
						responsive: {
							0: {
								items: 1
							},
							750: {
								items: 2
							},
							970: {
								items: 2
							},
							1170: {
								items: 2
							}
						}
					});
				});
			});
		<?php echo '</script'; ?>
>
	</div>
<?php }
}
}
