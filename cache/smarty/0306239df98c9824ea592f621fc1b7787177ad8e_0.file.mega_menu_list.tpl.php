<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/mega_menu_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301e6b613_18611293',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0306239df98c9824ea592f621fc1b7787177ad8e' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/pages/mega_menu_list.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301e6b613_18611293 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['menu_group']->value['menu_columns']) {?>
    <div class="list-dropdown list-collapse-mobile" data-hover-delay="100">
        <h4 class="title"><a href="<?php echo $_smarty_tpl->tpl_vars['menu_group']->value['full_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['menu_group']->value['name'];?>
</a></h4>
        <?php if (true || $_smarty_tpl->tpl_vars['menu_group']->value['level'] > 1) {?>
            <div class="submenu_list">
                <ul>
                    <li class="d-md-none mobile-head list-collapse-mobile level-2">
                        <h4 class="title"><?php echo $_smarty_tpl->tpl_vars['menu_group']->value['name'];?>
</h4>
                    </li>

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu_group']->value['menu_columns'], 'menu_column');
$_smarty_tpl->tpl_vars['menu_column']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['menu_column']->value) {
$_smarty_tpl->tpl_vars['menu_column']->do_else = false;
?>
                        <?php if (in_array($_smarty_tpl->tpl_vars['menu_group']->value['type'],array('category_list','page_list','brand_list'))) {?>
                            <li <?php if ($_smarty_tpl->tpl_vars['menu_group']->value['level'] > 2 && (isset($_smarty_tpl->tpl_vars['menu_column']->value['childs'])) && !empty($_smarty_tpl->tpl_vars['menu_column']->value['childs'])) {?>class="has_child"<?php }?>>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['menu_column']->value['full_url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['menu_column']->value['name'];?>
">
                                    <h4 class="level-2"><?php echo $_smarty_tpl->tpl_vars['menu_column']->value['name'];?>
</h4>
                                </a>

                                <?php if ($_smarty_tpl->tpl_vars['menu_group']->value['level'] > 2 && (isset($_smarty_tpl->tpl_vars['menu_column']->value['childs'])) && !empty($_smarty_tpl->tpl_vars['menu_column']->value['childs'])) {?>
                                    <ul class="submenu_list">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu_column']->value['childs'], 'sub_element');
$_smarty_tpl->tpl_vars['sub_element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['sub_element']->value) {
$_smarty_tpl->tpl_vars['sub_element']->do_else = false;
?>
                                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['sub_element']->value['full_url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['sub_element']->value['name'];?>
" class=""><span class="level-2"><?php echo $_smarty_tpl->tpl_vars['sub_element']->value['name'];?>
</span></a></li>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </ul>
                                <?php }?>
                            </li>
                        <?php } else { ?>
                            <li <?php if ((isset($_smarty_tpl->tpl_vars['menu_column']->value['menu_items'])) && !empty($_smarty_tpl->tpl_vars['menu_column']->value['menu_items'])) {?>class="has_child"<?php }?>>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['menu_column']->value['full_url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['menu_column']->value['name'];?>
" class="" <?php if ($_smarty_tpl->tpl_vars['menu_column']->value['new_window']) {?>target="_blank"<?php }?>>
                                    <span class="level-2"><?php echo $_smarty_tpl->tpl_vars['menu_column']->value['name'];?>
</span>
                                </a>

                                <?php if ((isset($_smarty_tpl->tpl_vars['menu_column']->value['menu_items'])) && !empty($_smarty_tpl->tpl_vars['menu_column']->value['menu_items'])) {?>
                                    <ul class="submenu_list">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu_column']->value['menu_items'], 'sub_element');
$_smarty_tpl->tpl_vars['sub_element']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['sub_element']->value) {
$_smarty_tpl->tpl_vars['sub_element']->do_else = false;
?>
                                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['sub_element']->value['full_url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['sub_element']->value['name'];?>
" class="" <?php if ($_smarty_tpl->tpl_vars['sub_element']->value['new_window']) {?>target="_blank"<?php }?>><span class="level-3"><?php echo $_smarty_tpl->tpl_vars['sub_element']->value['name'];?>
</span></a></li>
                                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </ul>
                                <?php }?>
                            </li>
                        <?php }?>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                    <?php if ((isset($_smarty_tpl->tpl_vars['menu_group']->value['element']))) {?>
                        <li class="d-md-none">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['menu_group']->value['element']['full_url'];?>
" title="<?php echo t('Peržiūrėti viską');?>
"><h4 class="level-2"><?php echo t('Peržiūrėti viską');?>
</h4></a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>
    </div>
<?php } elseif ((isset($_smarty_tpl->tpl_vars['menu_group']->value['link'])) && strlen($_smarty_tpl->tpl_vars['menu_group']->value['link']) > 0) {?>
    <?php if (in_array($_smarty_tpl->tpl_vars['menu_group']->value['type'],array('category_list','page_list'))) {?>
        <?php $_smarty_tpl->_assignInScope('element_link', $_smarty_tpl->tpl_vars['menu_group']->value['element']['full_url']);?>
    <?php } else { ?>
        <?php $_smarty_tpl->_assignInScope('element_link', $_smarty_tpl->tpl_vars['menu_group']->value['full_url']);?>
    <?php }?>

    <a href="<?php echo $_smarty_tpl->tpl_vars['element_link']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['menu_group']->value['new_window']) {?>target="_blank"<?php }?>>
        <h4 class="title"><?php echo $_smarty_tpl->tpl_vars['menu_group']->value['name'];?>
</h4>
    </a>
<?php }
}
}
