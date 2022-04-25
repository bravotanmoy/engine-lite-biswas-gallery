<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/mega_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301df12c2_27404719',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd3fa45addab139327a4ceeab500b59ac0b1f20ae' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/mega_menu.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301df12c2_27404719 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="pages_mega_menu">
	<div id="mega_menu_overlay"></div>
	<ul id="mega_menu">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'menu_group');
$_smarty_tpl->tpl_vars['menu_group']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['menu_group']->value) {
$_smarty_tpl->tpl_vars['menu_group']->do_else = false;
?>
			<?php if ($_smarty_tpl->tpl_vars['menu_group']->value['show_type'] == 'mega_menu') {?>
				<li class="mega_element">
					<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/mega_menu_panel',$_smarty_tpl->tpl_vars['menu_group']->value);?>

				</li>
			<?php } else { ?>
				<li>
					<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/mega_menu_list',$_smarty_tpl->tpl_vars['menu_group']->value);?>

				</li>
			<?php }?>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/profile_menu_mobile');?>

		<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/languages_menu_mobile');?>

	</ul>
</div>

<?php echo '<script'; ?>
>
    $(function () {
        $('.list-dropdown').each(function () {
            let listDropdown = $(this);

            listDropdown.find('.mobile-head').on('click', function () {
                listDropdown.removeClass('open').removeClass('hover');
                listDropdown.find('.submenu_list').removeAttr('style');
            });
        });

        $('.mega_element').each(function () {
            let megaElement = $(this);

            megaElement.find('.menu_head').on('click', function () {
                megaElement.find('.menu_column').removeClass('open');
            });
        })
    })
<?php echo '</script'; ?>
><?php }
}
