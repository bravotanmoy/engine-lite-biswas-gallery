<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:48:49
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_62322301d9c765_47970332',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01eb7ebf3119d526b3c99ea971120cb495f69390' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_layouts/default.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62322301d9c765_47970332 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<div id="content_layout" class="content_layout_<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_89149625962322301d86bb6_54195092', 'layout_name');
?>
">
	<div class="overlay"></div>
	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_122709410562322301d87da3_17338863', 'head');
?>


	<div id="content_wrapper">
		<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_191721110362322301d8f729_53451765', 'content');
?>

	</div>
</div>

<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/footer');?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_65980372562322301d9b574_30401034', 'script');
}
/* {block 'layout_name'} */
class Block_89149625962322301d86bb6_54195092 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'layout_name' => 
  array (
    0 => 'Block_89149625962322301d86bb6_54195092',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
default<?php
}
}
/* {/block 'layout_name'} */
/* {block 'head'} */
class Block_122709410562322301d87da3_17338863 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'head' => 
  array (
    0 => 'Block_122709410562322301d87da3_17338863',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<header>
			<div class="container-fluid">
				<div id="burger-icon" onclick="rotateMenuIcon(this)">
					<div class="menu-bar"></div>
					<div class="menu-bar"></div>
					<div class="menu-bar"></div>
				</div>

				<div id="logo">
					<a href="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
">
												<img src="<?php echo (defined('PROJECT_URL') ? constant('PROJECT_URL') : null);?>
images/logo.png" alt="<?php echo $_smarty_tpl->tpl_vars['config']->value['engine']['project_name'];?>
" />
					</a>
				</div>
				<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('search/quick_search');?>

				<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/languages_menu');?>

			</div>
		</header>

		<nav class="nav-down">
			<div class="container-fluid">
				<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('pages/mega_menu');?>

			</div>
		</nav>
		<div id="head_placeholder"></div>
	<?php
}
}
/* {/block 'head'} */
/* {block 'content'} */
class Block_191721110362322301d8f729_53451765 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_191721110362322301d8f729_53451765',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

			<div class="container-fluid">
				<?php echo $_smarty_tpl->tpl_vars['h']->value->show_messages('frontend');?>

				<?php echo $_smarty_tpl->tpl_vars['h']->value->breadcrumb();?>

			</div>
			<div class="content_body">
				<div class="container-fluid">
					<div class="row">
						<?php $_smarty_tpl->_assignInScope('context_menu', $_smarty_tpl->tpl_vars['frontend']->value->view('pages/context_menu'));?>
						<?php if ($_smarty_tpl->tpl_vars['context_menu']->value) {?>
							<div class="col-context_menu col-md-3">
								<?php echo $_smarty_tpl->tpl_vars['context_menu']->value;?>

							</div>
						<?php }?>
						<div class="col-content <?php if ($_smarty_tpl->tpl_vars['context_menu']->value) {?>col-md-9<?php } else { ?>col-12<?php }?>">
							<?php echo $_smarty_tpl->tpl_vars['frontend']->value->display_content_type();?>

						</div>
					</div>
				</div>
			</div>
		<?php
}
}
/* {/block 'content'} */
/* {block 'script'} */
class Block_65980372562322301d9b574_30401034 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'script' => 
  array (
    0 => 'Block_65980372562322301d9b574_30401034',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
	$(function () {
		$('#burger-icon').on('click', function () {
			$('body').toggleClass('main-nav-active');
			$('nav').toggleClass('nav-mobile');
		});
	})
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block 'script'} */
}
