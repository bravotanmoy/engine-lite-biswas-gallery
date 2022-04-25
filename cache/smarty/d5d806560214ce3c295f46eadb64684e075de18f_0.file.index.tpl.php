<?php
/* Smarty version 3.1.44, created on 2022-03-16 17:46:32
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62320658d4d4d4_23849074',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd5d806560214ce3c295f46eadb64684e075de18f' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/index.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62320658d4d4d4_23849074 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_32281780262320658d48704_77949883', 'layout_name');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_161063278762320658d49466_68713486', 'content');
$_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['h']->value->get_view_path('frontend/content_layouts/default.tpl'));
}
/* {block 'layout_name'} */
class Block_32281780262320658d48704_77949883 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'layout_name' => 
  array (
    0 => 'Block_32281780262320658d48704_77949883',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
index<?php
}
}
/* {/block 'layout_name'} */
/* {block 'content'} */
class Block_161063278762320658d49466_68713486 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_161063278762320658d49466_68713486',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <div class="content_body">
		<div class="container-fluid"><?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('frontend');?>
</div>
        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('banners/hero');?>

        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('banners/small');?>

        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('collections/index_listing',10);?>

        <?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('brands/index_listing');?>

	</div>
<?php
}
}
/* {/block 'content'} */
}
