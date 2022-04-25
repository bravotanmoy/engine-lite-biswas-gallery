<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:54:22
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/plain.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_6232082e7b98a0_15944203',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1eabdd1ba9c22ab6264459d6c6e651ebb8211841' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/plain.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6232082e7b98a0_15944203 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10094820486232082e7b5bc8_80032287', 'layout_name');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15814334376232082e7b6c31_30102128', 'content');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['h']->value->get_view_path('frontend/content_layouts/default.tpl'));
}
/* {block 'layout_name'} */
class Block_10094820486232082e7b5bc8_80032287 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'layout_name' => 
  array (
    0 => 'Block_10094820486232082e7b5bc8_80032287',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
plain<?php
}
}
/* {/block 'layout_name'} */
/* {block 'content'} */
class Block_15814334376232082e7b6c31_30102128 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_15814334376232082e7b6c31_30102128',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="content_body">
        <div class="container-fluid">
            <?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('frontend');?>

            <?php echo $_smarty_tpl->tpl_vars['frontend']->value->display_content_type();?>

        </div>
    </div>
<?php
}
}
/* {/block 'content'} */
}
