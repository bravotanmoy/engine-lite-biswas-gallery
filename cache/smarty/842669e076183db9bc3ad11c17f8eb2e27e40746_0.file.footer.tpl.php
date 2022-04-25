<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:51
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_6232230308a576_56069088',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '842669e076183db9bc3ad11c17f8eb2e27e40746' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/footer.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6232230308a576_56069088 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="footer">
    <div class="footer-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view("subscribers/subscribe");?>

                </div>
            </div>
        </div>
        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view("pages/footer_advantages");?>

    </div>
    <div class="footer-bottom">
        <div class="container-fluid">
            <div class="col-12 footer-menu-wrapper">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="footer-logo">
                            <?php $_smarty_tpl->_assignInScope('logo', ((string)(defined('PROJECT_URL') ? constant('PROJECT_URL') : null))."images/logo.png");?>
                            <a href="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
" target="_blank"><img class="img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
"
                                                                                       alt=""/></a>
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['frontend']->value->project['social_icons']) {?>
                            <div id="footer_social">
                                <div class="social-icons">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['frontend']->value->project['social_icons'], 'icon');
$_smarty_tpl->tpl_vars['icon']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['icon']->value) {
$_smarty_tpl->tpl_vars['icon']->do_else = false;
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['icon']->value['url'];?>
" class="icon icon-<?php echo $_smarty_tpl->tpl_vars['icon']->value['name'];?>
"></a>
                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </div>
                            </div>
                        <?php }?>
                    </div>

                    <div class="col-12 col-md-9">
                        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/footer_menu');?>

                    </div>
                </div>
            </div>

            <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/copyright');?>

        </div>
    </div>
</div>

<?php echo '<script'; ?>
>
    $(function () {
        function position_footer() {
            $('#content_wrapper').css('min-height', $(window).height() - $('#head').outerHeight() - $('#footer').outerHeight() - $('#footer_social').outerHeight() + 'px');
        }

        position_footer();
        setInterval(function () {
            position_footer();
        }, 100);
    });
<?php echo '</script'; ?>
>
<?php }
}
