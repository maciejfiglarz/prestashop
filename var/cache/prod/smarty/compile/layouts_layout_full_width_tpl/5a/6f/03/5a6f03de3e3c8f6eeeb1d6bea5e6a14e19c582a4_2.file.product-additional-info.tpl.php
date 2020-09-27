<?php
/* Smarty version 3.1.33, created on 2020-09-27 12:50:47
  from '/var/www/html/prestashopn/themes/razdwa/templates/catalog/_partials/product-additional-info.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f706e87b8ece1_09317222',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a6f03de3e3c8f6eeeb1d6bea5e6a14e19c582a4' => 
    array (
      0 => '/var/www/html/prestashopn/themes/razdwa/templates/catalog/_partials/product-additional-info.tpl',
      1 => 1587131432,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f706e87b8ece1_09317222 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="product-additional-info">
  <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['hook'][0], array( array('h'=>'displayProductAdditionalInfo','product'=>$_smarty_tpl->tpl_vars['product']->value),$_smarty_tpl ) );?>

</div>
<?php }
}
