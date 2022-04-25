<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:33
  from '/opt/lampp/htdocs/engine-lite-biswas/views/helpers/frontend/picture.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320659342510_84758684',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '77a6150af1108fd3b020c2d086bf23c274fa4ca9' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/helpers/frontend/picture.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320659342510_84758684 (Smarty_Internal_Template $_smarty_tpl) {
?><picture class="<?php echo $_smarty_tpl->tpl_vars['picture_class']->value;?>
">
    <?php if ($_smarty_tpl->tpl_vars['webp']->value) {?>
        <source srcset="<?php echo $_smarty_tpl->tpl_vars['webp']->value;
if ($_smarty_tpl->tpl_vars['webp2x']->value) {?> 1x, <?php echo $_smarty_tpl->tpl_vars['webp2x']->value;?>
 2x<?php }?>" type="image/webp"
                media="(min-width: 769px)">
        <?php if (!$_smarty_tpl->tpl_vars['only_desktop']->value) {?>
            <source srcset="<?php echo $_smarty_tpl->tpl_vars['webpmobile']->value;?>
" type="image/webp" media="(max-width: 768px)">
        <?php }?>
    <?php }?>
    <source srcset="<?php echo $_smarty_tpl->tpl_vars['src']->value;
if ($_smarty_tpl->tpl_vars['src2x']->value) {?> 1x, <?php echo $_smarty_tpl->tpl_vars['src2x']->value;?>
 2x<?php }?>" media="(min-width: 769px)"/>
    <?php if (!$_smarty_tpl->tpl_vars['only_desktop']->value) {?>
        <source srcset="<?php echo $_smarty_tpl->tpl_vars['srcmobile']->value;?>
" type="image/webp" media="(max-width: 768px)">
    <?php }?>
    <img loading="lazy" class="img-responsive <?php echo $_smarty_tpl->tpl_vars['class']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['only_desktop']->value) {?>hidden-xs hidden-sm<?php }?>" src="<?php echo $_smarty_tpl->tpl_vars['src']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['alt']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['additional_attr']->value;?>
>
</picture><?php }
}
