<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/page_layouts/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301d6fde6_94453930',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '88a8bacd064a7ab904c666bbbe975923fd6b9cae' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/page_layouts/default.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301d6fde6_94453930 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html>
<?php $_smarty_tpl->smarty->ext->_capture->open($_smarty_tpl, "body", null, null);?>
	<?php echo $_smarty_tpl->tpl_vars['h']->value->include_file("frontend/content_layouts/".((string)$_smarty_tpl->tpl_vars['content_layout']->value));?>

<?php $_smarty_tpl->smarty->ext->_capture->close($_smarty_tpl);?>
<head>
	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_106972979862322301d33490_49846884', 'meta');
?>


	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_171313735862322301d47f80_99176435', 'css');
?>


	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_167051964462322301d622b4_49259462', 'js');
?>


	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_151471695362322301d684c3_10182928', 'vendors');
?>

</head>
<body id="type_<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page['type'];?>
" <?php if ($_GET['layout']) {?>class="layout_<?php echo $_GET['layout'];?>
"<?php }?>>
	<?php echo $_smarty_tpl->smarty->ext->_capture->getBuffer($_smarty_tpl, 'body');?>

	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_102890862262322301d6da67_71718173', 'after_content');
?>

</body>
</html><?php }
/* {block 'meta'} */
class Block_106972979862322301d33490_49846884 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'meta' => 
  array (
    0 => 'Block_106972979862322301d33490_49846884',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<title><?php if ($_smarty_tpl->tpl_vars['frontend']->value->page['meta_title']) {
echo $_smarty_tpl->tpl_vars['frontend']->value->page['meta_title'];
} else {
echo $_smarty_tpl->tpl_vars['frontend']->value->get_title();
}?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page['meta_description'];?>
"/>
		<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page['meta_keywords'];?>
"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="shortcut icon" href="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/favicon.ico">
		<?php if ($_smarty_tpl->tpl_vars['frontend']->value->page['noindex'] || $_smarty_tpl->tpl_vars['frontend']->value->page['nofollow']) {?>
			<meta name="robots" content="<?php if ($_smarty_tpl->tpl_vars['frontend']->value->page['noindex']) {?>no<?php }?>index,<?php if ($_smarty_tpl->tpl_vars['frontend']->value->page['nofollow']) {?>no<?php }?>follow">
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['frontend']->value->page['canonical']) {?>
			<link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->page['canonical'];?>
" />
		<?php }?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-Language" content="<?php echo $_smarty_tpl->tpl_vars['frontend']->value->lang_key;?>
"/>
	<?php
}
}
/* {/block 'meta'} */
/* {block 'css'} */
class Block_171313735862322301d47f80_99176435 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'css' => 
  array (
    0 => 'Block_171313735862322301d47f80_99176435',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<link rel="preload" href="/public/fonts/stylesheet.css" as="style">
		<link rel="preload" href="/public/vendors/css/fancybox.min.css" as="style">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array_unique($_smarty_tpl->tpl_vars['frontend']->value->css), 'stylesheet');
$_smarty_tpl->tpl_vars['stylesheet']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['stylesheet']->value) {
$_smarty_tpl->tpl_vars['stylesheet']->do_else = false;
?>
			<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['stylesheet']->value;?>
"/>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['h']->value->mix('frontend.css','frontend');?>
" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['h']->value->mix('css/frontend.scss','vendors');?>
" />
	<?php
}
}
/* {/block 'css'} */
/* {block 'js'} */
class Block_167051964462322301d622b4_49259462 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'js' => 
  array (
    0 => 'Block_167051964462322301d622b4_49259462',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['h']->value->mix('frontend.js','vendors');?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['h']->value->mix('frontend.js','frontend');?>
"><?php echo '</script'; ?>
>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array_unique($_smarty_tpl->tpl_vars['frontend']->value->js), 'script');
$_smarty_tpl->tpl_vars['script']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['script']->value) {
$_smarty_tpl->tpl_vars['script']->do_else = false;
?>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['script']->value;?>
"><?php echo '</script'; ?>
>
		<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php
}
}
/* {/block 'js'} */
/* {block 'vendors'} */
class Block_151471695362322301d684c3_10182928 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'vendors' => 
  array (
    0 => 'Block_151471695362322301d684c3_10182928',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<!--[if lt IE 9]>
		<?php echo '<script'; ?>
 src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"><?php echo '</script'; ?>
>
		<![endif]-->
	<?php
}
}
/* {/block 'vendors'} */
/* {block 'after_content'} */
class Block_102890862262322301d6da67_71718173 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'after_content' => 
  array (
    0 => 'Block_102890862262322301d6da67_71718173',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<div id="ajax_loader"></div>
		<div id="scrollup"><span class="icon icon-up"></span></div>
		<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('popups/popup');?>

	<?php
}
}
/* {/block 'after_content'} */
}
