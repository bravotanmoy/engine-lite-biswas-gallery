<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/products.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301d822a4_33107542',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '03efa0996516f0f5aa27cea3d01443f081420807' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/products.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301d822a4_33107542 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_197493136662322301d7cba1_15878284', 'layout_name');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_207755863162322301d7deb2_81218781', 'content');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['h']->value->get_view_path('frontend/content_layouts/default.tpl'));
}
/* {block 'layout_name'} */
class Block_197493136662322301d7cba1_15878284 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'layout_name' => 
  array (
    0 => 'Block_197493136662322301d7cba1_15878284',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
products<?php
}
}
/* {/block 'layout_name'} */
/* {block 'content'} */
class Block_207755863162322301d7deb2_81218781 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_207755863162322301d7deb2_81218781',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="container-fluid">
        <?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('frontend');?>

        <?php echo $_smarty_tpl->tpl_vars['h']->value->breadcrumb();?>

    </div>
    <div class="content_body">
        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view($_smarty_tpl->tpl_vars['content_type']->value);?>

    </div>
<?php
}
}
/* {/block 'content'} */
}
