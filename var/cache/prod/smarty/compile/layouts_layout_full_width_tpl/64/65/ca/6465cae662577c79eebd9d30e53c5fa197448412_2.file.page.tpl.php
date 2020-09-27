<?php
/* Smarty version 3.1.33, created on 2020-09-27 12:50:42
  from '/var/www/html/prestashopn/themes/razdwa/templates/page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f706e82d91e81_36305582',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6465cae662577c79eebd9d30e53c5fa197448412' => 
    array (
      0 => '/var/www/html/prestashopn/themes/razdwa/templates/page.tpl',
      1 => 1587131432,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f706e82d91e81_36305582 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4080336135f706e82d8f2c2_14034246', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_8648744305f706e82d8f957_23534937 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header class="page-header">
          <h1><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h1>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_18891099675f706e82d8f5c0_90982977 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8648744305f706e82d8f957_23534937', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_12093308925f706e82d90b60_53360206 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_4948863605f706e82d90f55_94551093 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_4261298075f706e82d90895_43195036 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12093308925f706e82d90b60_53360206', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4948863605f706e82d90f55_94551093', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_3673533765f706e82d917b0_04905783 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_2868462035f706e82d91521_40746776 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3673533765f706e82d917b0_04905783', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_4080336135f706e82d8f2c2_14034246 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_4080336135f706e82d8f2c2_14034246',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_18891099675f706e82d8f5c0_90982977',
  ),
  'page_title' => 
  array (
    0 => 'Block_8648744305f706e82d8f957_23534937',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_4261298075f706e82d90895_43195036',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_12093308925f706e82d90b60_53360206',
  ),
  'page_content' => 
  array (
    0 => 'Block_4948863605f706e82d90f55_94551093',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_2868462035f706e82d91521_40746776',
  ),
  'page_footer' => 
  array (
    0 => 'Block_3673533765f706e82d917b0_04905783',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_18891099675f706e82d8f5c0_90982977', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_4261298075f706e82d90895_43195036', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2868462035f706e82d91521_40746776', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
