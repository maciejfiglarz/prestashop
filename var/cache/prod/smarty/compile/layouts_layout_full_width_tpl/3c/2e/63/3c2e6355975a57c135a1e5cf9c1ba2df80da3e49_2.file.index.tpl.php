<?php
/* Smarty version 3.1.33, created on 2020-09-27 12:50:42
  from '/var/www/html/prestashopn/themes/razdwa/templates/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f706e82d8d641_57205379',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3c2e6355975a57c135a1e5cf9c1ba2df80da3e49' => 
    array (
      0 => '/var/www/html/prestashopn/themes/razdwa/templates/index.tpl',
      1 => 1587131432,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f706e82d8d641_57205379 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_232613995f706e82d8c073_53125050', 'page_content_container');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_11710304115f706e82d8c3d8_61978762 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_536382465f706e82d8cb45_83022342 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_19169576665f706e82d8c893_86895581 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_536382465f706e82d8cb45_83022342', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_232613995f706e82d8c073_53125050 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_232613995f706e82d8c073_53125050',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_11710304115f706e82d8c3d8_61978762',
  ),
  'page_content' => 
  array (
    0 => 'Block_19169576665f706e82d8c893_86895581',
  ),
  'hook_home' => 
  array (
    0 => 'Block_536382465f706e82d8cb45_83022342',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11710304115f706e82d8c3d8_61978762', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19169576665f706e82d8c893_86895581', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
}
