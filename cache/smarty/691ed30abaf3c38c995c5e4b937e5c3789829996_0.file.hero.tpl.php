<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:33
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/banners/hero.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320659294f06_81298947',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '691ed30abaf3c38c995c5e4b937e5c3789829996' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/banners/hero.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320659294f06_81298947 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['elements']->value)) {?>
    <?php $_smarty_tpl->_assignInScope('desktop_count', 0);?>
    <?php $_smarty_tpl->_assignInScope('mobile_count', 0);?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'element');
$_smarty_tpl->tpl_vars['element']->index = -1;
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
$_smarty_tpl->tpl_vars['element']->index++;
$_smarty_tpl->tpl_vars['element']->first = !$_smarty_tpl->tpl_vars['element']->index;
$__foreach_element_8_saved = $_smarty_tpl->tpl_vars['element'];
?>
        <?php if ($_smarty_tpl->tpl_vars['element']->value['visible_desktop']) {
$_smarty_tpl->_assignInScope('desktop_count', $_smarty_tpl->tpl_vars['desktop_count']->value+1);
}?>
        <?php if ($_smarty_tpl->tpl_vars['element']->value['visible_mobile']) {
$_smarty_tpl->_assignInScope('mobile_count', $_smarty_tpl->tpl_vars['mobile_count']->value+1);
}?>
    <?php
$_smarty_tpl->tpl_vars['element'] = $__foreach_element_8_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <div id="banners_hero" class="banners<?php if (!$_smarty_tpl->tpl_vars['mobile_count']->value) {?> d-none d-sm-block<?php }
if (!$_smarty_tpl->tpl_vars['desktop_count']->value) {?> d-sm-none<?php }?>">
        <div class="container-fluid">
            <div id="hero_slider">
                <div class="carousel-inner-x">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'element');
$_smarty_tpl->tpl_vars['element']->index = -1;
$_smarty_tpl->tpl_vars['element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['element']->value) {
$_smarty_tpl->tpl_vars['element']->do_else = false;
$_smarty_tpl->tpl_vars['element']->index++;
$_smarty_tpl->tpl_vars['element']->first = !$_smarty_tpl->tpl_vars['element']->index;
$__foreach_element_9_saved = $_smarty_tpl->tpl_vars['element'];
?>
                        <div class="item<?php if ($_smarty_tpl->tpl_vars['element']->first) {?> active<?php }?>">
                            <div class="banner_img">
                                <?php echo $_smarty_tpl->tpl_vars['h']->value->picture($_smarty_tpl->tpl_vars['element']->value['photo'],"width=1120&height=525&fill=1&bg_color=FFFFFF&mode=crop&quality=90",((string)$_smarty_tpl->tpl_vars['element']->value['photo']['name']),'','',"banner_picture");?>

                            </div>

                            <div class="title_wrp">
                                <?php if (!$_smarty_tpl->tpl_vars['element']->value['hide_text']) {?>
                                    <?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, 'default', "banner_content", null);?>
                                        <div class="banner_content<?php if ($_smarty_tpl->tpl_vars['element']->value['color_theme']) {?> color-theme-<?php echo $_smarty_tpl->tpl_vars['element']->value['color_theme'];
}?>" style="<?php if ($_smarty_tpl->tpl_vars['element']->value['text_color']) {?>color:<?php echo $_smarty_tpl->tpl_vars['element']->value['text_color'];?>
; <?php }
if ($_smarty_tpl->tpl_vars['element']->value['vertical_align']) {?> vertical-align: <?php echo $_smarty_tpl->tpl_vars['element']->value['vertical_align'];?>
;<?php }?>">
                                            <?php if ($_smarty_tpl->tpl_vars['element']->value['name']) {?><h1 class="title"><?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['element']->value['name']);?>
</h1><?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['element']->value['description']) {?><p class="desc"><?php echo nl2br($_smarty_tpl->tpl_vars['element']->value['description']);?>
</p><?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['element']->value['link']) {?><button  class="btn btn-primary btn-lg rounded-0"><?php echo t('Plačiau');?>
</button><?php }?>
                                        </div>
                                    <?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
                                <?php }?>

                                <?php if ($_smarty_tpl->tpl_vars['element']->value['link']) {?>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['element']->value['link'];?>
" <?php if ($_smarty_tpl->tpl_vars['element']->value['blank']) {?>target="_blank"<?php }?> class="container-fluid vcenter" style="text-align: <?php echo $_smarty_tpl->tpl_vars['element']->value['text_align'];?>
;">
                                        <?php echo $_smarty_tpl->tpl_vars['banner_content']->value;?>

                                    </a>
                                <?php } else { ?>
                                    <div class="container-fluid vcenter" style="text-align: <?php echo $_smarty_tpl->tpl_vars['element']->value['text_align'];?>
;">
                                        <?php echo $_smarty_tpl->tpl_vars['banner_content']->value;?>

                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    <?php
$_smarty_tpl->tpl_vars['element'] = $__foreach_element_9_saved;
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </div>
            </div>
            <div class="owl-nav owl-out-nav"></div>
                    </div>

        <?php echo '<script'; ?>
>
            $(function(){
                $('#hero_slider').addClass('owl-container');
                $('#hero_slider .carousel-inner-x').each(function () {
                    // Add main classes
                    $(this).addClass('owl-carousel');
                    // Options
                    $(this).owlCarousel({
                        autoHeight: true,
                        loop: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: true,
                        nav: true,
                        navText: [
                            "<span class='icon icon-left-big'></span>",
                            "<span class='icon icon-right-big'></span>",
                        ],
                        responsiveClass: true,
                        navContainer: '#banners_hero .owl-out-nav',
    //                    dotsContainer: '.owl-out-dots',
                        slideBy: 'page',
                        dots: false,
                        responsive: {
                            0: {
                                items: 1
                            },
                            750: {
                                items: 1
                            },
                            970: {
                                items: 1
                            },
                            1170: {
                                items: 1
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
