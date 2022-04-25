<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:07
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/languages_menu_mobile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f7df30c9_57972060',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5e1db4ce102ebe92e7c424ee2387cb6acf801724' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/languages_menu_mobile.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f7df30c9_57972060 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '713964909623220f7de8086_33577426';
if ($_smarty_tpl->tpl_vars['languages']->value) {?>
    <li id="languages_menu_mobile" class="d-md-none">
        <div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
            <h4 class="title">
                <?php echo t('Kalba');?>


                <i class="icon-kalba icon"></i>
                <img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/languages/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['frontend']->value->lang_key, 'UTF-8');?>
.svg"/>
            </h4>

            <div class="submenu_list">
                <ul>
                    <li class="d-md-none mobile-head list-collapse-mobile level-2">
                        <h4 class="title">
                            <?php echo t('Pasirinkite kalbą');?>


                            <i class="icon-kalba icon"></i>
                        </h4>
                    </li>

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['languages']->value, 'lang');
$_smarty_tpl->tpl_vars['lang']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['lang']->value) {
$_smarty_tpl->tpl_vars['lang']->do_else = false;
?>
                        <li>
                            <a href="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);
echo mb_strtolower($_smarty_tpl->tpl_vars['lang']->value['language'], 'UTF-8');?>
">
                                <h4 class="level-2"><?php echo $_smarty_tpl->tpl_vars['lang']->value['name'];?>
 <img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/languages/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['lang']->value['language'], 'UTF-8');?>
.svg"/></h4>
                            </a>
                        </li>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </div>
        </div>
    </li>
<?php }
}
}
