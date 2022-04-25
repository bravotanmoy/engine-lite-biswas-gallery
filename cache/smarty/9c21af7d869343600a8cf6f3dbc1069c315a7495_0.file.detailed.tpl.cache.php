<?php
/* Smarty version 3.1.44, created on 2022-03-16 19:40:08
  from '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/detailed.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.44',
  'unifunc' => 'content_623220f8e902f5_87205821',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9c21af7d869343600a8cf6f3dbc1069c315a7495' => 
    array (
      0 => '/opt/lampp/htdocs/engine-lite-biswas/views/frontend/content_types/products/detailed.tpl',
      1 => 1646584124,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_623220f8e902f5_87205821 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
$_smarty_tpl->compiled->nocache_hash = '401278283623220f8e674d1_42523831';
?>
<div id="products_detailed">
	<div class="product_block">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 d-md-none summary_wrp">
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_267235972623220f8e6a5a3_42993417', "summary_mobile");
?>

				</div>
				<div class="col-md-7 col-12 photos_wrp">
					<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/photos');?>

				</div>
				<div class="col-md-5 col-12">
					<div class="col-12 summary_wrp d-none d-md-block">
					<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_748705798623220f8e7c370_19064582', "summary");
?>

					</div>
					<div class="col-12 add2cart_wrp">
						<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/add2cart');?>

					</div>
				</div>
			</div>
		</div>
	</div>

	<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_85774645623220f8e86483_33058752', "description");
?>


	<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/similar_products',$_smarty_tpl->tpl_vars['element']->value);?>

	<?php echo $_smarty_tpl->tpl_vars['frontend']->value->view('products/related_products',$_smarty_tpl->tpl_vars['element']->value);?>

</div><?php }
/* {block "summary_mobile"} */
class Block_267235972623220f8e6a5a3_42993417 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'summary_mobile' => 
  array (
    0 => 'Block_267235972623220f8e6a5a3_42993417',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

						<h1><?php echo $_smarty_tpl->tpl_vars['frontend']->value->get_title();?>
</h1>

						<?php if ($_smarty_tpl->tpl_vars['element']->value['tags'] || $_smarty_tpl->tpl_vars['element']->value['discount_percent'] || $_smarty_tpl->tpl_vars['modification_tags']->value) {?>
							<?php if ($_smarty_tpl->tpl_vars['modification_tags']->value) {?>
								<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['element']) ? $_smarty_tpl->tpl_vars['element']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array['tags'] = $_smarty_tpl->tpl_vars['modification_tags']->value;
$_smarty_tpl->_assignInScope('element', $_tmp_array);?>
							<?php }?>

							<span class="tags">
                                <?php if ($_smarty_tpl->tpl_vars['element']->value['tags']) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['element']->value['tags'], 'tag');
$_smarty_tpl->tpl_vars['tag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tag']->value) {
$_smarty_tpl->tpl_vars['tag']->do_else = false;
?>
										<span class="tag tag-<?php echo $_smarty_tpl->tpl_vars['tag']->value['url'];?>
" <?php if ($_smarty_tpl->tpl_vars['tag']->value['color']) {?>style="background-color: #<?php echo $_smarty_tpl->tpl_vars['tag']->value['color'];?>
"<?php }?>>
                                            <?php if ($_smarty_tpl->tpl_vars['tag']->value['photo']) {?>
												<img src="<?php echo $_smarty_tpl->tpl_vars['tag']->value['photo']['src'];?>
" />
											<?php } else { ?>
												<?php echo $_smarty_tpl->tpl_vars['tag']->value['name'];?>

											<?php }?>
                                        </span>
									<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>

								<?php if ($_smarty_tpl->tpl_vars['element']->value['discount_percent']) {?>
									<span class="tag tag-discount-percent">
                                        -<?php echo $_smarty_tpl->tpl_vars['element']->value['discount_percent'];?>
%
                                    </span>
								<?php }?>
                            </span>
						<?php }?>
					<?php
}
}
/* {/block "summary_mobile"} */
/* {block "summary"} */
class Block_748705798623220f8e7c370_19064582 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'summary' => 
  array (
    0 => 'Block_748705798623220f8e7c370_19064582',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

						<h1><?php echo $_smarty_tpl->tpl_vars['frontend']->value->get_title();?>
</h1>

						<?php if ($_smarty_tpl->tpl_vars['element']->value['tags'] || $_smarty_tpl->tpl_vars['element']->value['discount_percent'] || $_smarty_tpl->tpl_vars['modification_tags']->value) {?>
							<?php if ($_smarty_tpl->tpl_vars['modification_tags']->value) {?>
								<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['element']) ? $_smarty_tpl->tpl_vars['element']->value : array();
if (!(is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess)) {
settype($_tmp_array, 'array');
}
$_tmp_array['tags'] = $_smarty_tpl->tpl_vars['modification_tags']->value;
$_smarty_tpl->_assignInScope('element', $_tmp_array);?>
							<?php }?>

							<span class="tags">
                                <?php if ($_smarty_tpl->tpl_vars['element']->value['tags']) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['element']->value['tags'], 'tag');
$_smarty_tpl->tpl_vars['tag']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tag']->value) {
$_smarty_tpl->tpl_vars['tag']->do_else = false;
?>
										<span class="tag tag-<?php echo $_smarty_tpl->tpl_vars['tag']->value['url'];?>
" <?php if ($_smarty_tpl->tpl_vars['tag']->value['color']) {?>style="background-color: #<?php echo $_smarty_tpl->tpl_vars['tag']->value['color'];?>
"<?php }?>>
                                            <?php if ($_smarty_tpl->tpl_vars['tag']->value['photo']) {?>
												<img src="<?php echo $_smarty_tpl->tpl_vars['tag']->value['photo']['src'];?>
" />
											<?php } else { ?>
												<?php echo $_smarty_tpl->tpl_vars['tag']->value['name'];?>

											<?php }?>
                                        </span>
									<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>

								<?php if ($_smarty_tpl->tpl_vars['element']->value['discount_percent']) {?>
									<span class="tag tag-discount-percent">
                                        -<?php echo $_smarty_tpl->tpl_vars['element']->value['discount_percent'];?>
%
                                    </span>
								<?php }?>
                            </span>
						<?php }?>
					<?php
}
}
/* {/block "summary"} */
/* {block "description"} */
class Block_85774645623220f8e86483_33058752 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'description' => 
  array (
    0 => 'Block_85774645623220f8e86483_33058752',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

		<div class="container-fluid">
			<div class="product_detailed_description_wrp">
				<?php echo $_smarty_tpl->tpl_vars['h']->value->display_html($_smarty_tpl->tpl_vars['element']->value['description']);?>

			</div>
		</div>
	<?php
}
}
/* {/block "description"} */
}
