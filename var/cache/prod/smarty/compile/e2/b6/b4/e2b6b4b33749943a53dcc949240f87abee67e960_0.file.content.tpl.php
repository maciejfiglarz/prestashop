<?php
/* Smarty version 3.1.33, created on 2020-09-27 12:50:37
  from '/var/www/html/prestashopn/admin6307yswgn/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f706e7d7e9231_52018008',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e2b6b4b33749943a53dcc949240f87abee67e960' => 
    array (
      0 => '/var/www/html/prestashopn/admin6307yswgn/themes/default/template/content.tpl',
      1 => 1601200748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f706e7d7e9231_52018008 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}
