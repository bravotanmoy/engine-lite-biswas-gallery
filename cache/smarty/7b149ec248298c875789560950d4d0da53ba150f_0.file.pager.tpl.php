<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:44
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/elements/pager.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320844e8aa25_19692829',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7b149ec248298c875789560950d4d0da53ba150f' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/elements/pager.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320844e8aa25_19692829 (Smarty_Internal_Template $_smarty_tpl) {
if (count($_smarty_tpl->tpl_vars['numeriai']->value) > 1) {?>
    <ul class="pagination justify-content-center mt-3>
        <li class="<?php if ($_smarty_tpl->tpl_vars['aktyvus_psl']->value <= 1) {?>disabled<?php }?> page-item"><a class="pagination_link page-link border-0 rounded-0" href="<?php if ($_smarty_tpl->tpl_vars['aktyvus_psl']->value <= 1) {?>#<?php } else { ?>?<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
page=<?php echo $_smarty_tpl->tpl_vars['aktyvus_psl']->value-1;
echo $_smarty_tpl->tpl_vars['bookmark']->value;
}?>"><?php if ($_smarty_tpl->tpl_vars['frontend']->value) {?><i class="icon-left"></i><?php } else { ?>&laquo;<?php }?></a></li>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['numeriai']->value, 'i');
$_smarty_tpl->tpl_vars['i']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['i']->value) {
$_smarty_tpl->tpl_vars['i']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['i']->value-$_smarty_tpl->tpl_vars['last']->value > 1) {?><li class="dot page-item"><a nohref class="dots page-link border-0">...</a></li><?php }?>
            <li class="<?php if ($_smarty_tpl->tpl_vars['aktyvus_psl']->value == $_smarty_tpl->tpl_vars['i']->value) {?>active<?php }?> page-item"><a href="?<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
page=<?php echo $_smarty_tpl->tpl_vars['i']->value;
echo $_smarty_tpl->tpl_vars['bookmark']->value;?>
" class="page-link border-0"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</a></li>
            <?php $_smarty_tpl->_assignInScope('last', $_smarty_tpl->tpl_vars['i']->value);?>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <li class="<?php if ($_smarty_tpl->tpl_vars['aktyvus_psl']->value >= $_smarty_tpl->tpl_vars['puslapiu_sk']->value) {?>disabled<?php }?> page-item"><a class="pagination_link page-link border-0 rounded-0" href="<?php if ($_smarty_tpl->tpl_vars['aktyvus_psl']->value >= $_smarty_tpl->tpl_vars['puslapiu_sk']->value) {?>#<?php } else { ?>?<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&<?php echo $_smarty_tpl->tpl_vars['prefix']->value;?>
page=<?php echo $_smarty_tpl->tpl_vars['aktyvus_psl']->value+1;
echo $_smarty_tpl->tpl_vars['bookmark']->value;
}?>"><?php if ($_smarty_tpl->tpl_vars['frontend']->value) {?><i class="icon-right"></i><?php } else { ?>&raquo;<?php }?></a></li>
    </ul>
<?php }?>

<?php }
}
