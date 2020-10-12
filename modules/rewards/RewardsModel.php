<?php
/**
 * 2007-2020 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class RewardsModel extends ObjectModel
{
	public $title;
	public $image;
	public $active;
	public $position;
		/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'rewards',
		'primary' => 'id_reward',
		'multilang' => false,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'title' =>			array('type' => self::TYPE_STRING,  'validate' => 'isCleanHtml', 'size' => 255),
			'image' =>			array('type' => self::TYPE_STRING,  'validate' => 'isCleanHtml', 'size' => 255),
		)
	);

	public	function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_slide, $id_lang, $id_shop);
	}
	
	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		// $res &= Db::getInstance()->execute('
		// 	INSERT INTO `'._DB_PREFIX_.'homeslider` (`id_shop`, `id_homeslider_slides`)
		// 	VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		// );
		return $res;
	}

}
