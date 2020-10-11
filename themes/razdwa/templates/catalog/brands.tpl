{**
* 2007-2019 PrestaShop and Contributors
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
* needs please refer to https://www.prestashop.com for more information.
*
* @author PrestaShop SA <contact@prestashop.com>
    * @copyright 2007-2019 PrestaShop SA and Contributors
    * @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
    * International Registered Trademark & Property of PrestaShop SA
    *}
    {extends file=$layout}

    {block name='content'}
    <section id="main">

        {block name='brand_header'}
        <h1>{l s='Brands' d='Shop.Theme.Catalog'}</h1>
        {/block}
        <br>
        <form method="post" action="{$urls.base_url}index.php?controller=supplier">
            <div class="row">
                <input type="hidden" name="controller" value="supplier">
                <div class="col-md-6">
                    <select class="form-control" name="submitVoivodeship">
                        <option value="all">Wszystkie</option>
                        {foreach from=$voivodeships item=$voivodeship}
                        <option value="{$voivodeship}">{$voivodeship}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn btn-primary" value="Pokaż" />
                </div>
            </div>
        </form>

        {block name='brand_miniature'}
        <br>   <br>
        <ul class="row">
            {foreach from=$brands item=brand}
            {include file='catalog/_partials/miniatures/brand.tpl' brand=$brand}
            {/foreach}
        </ul>
        {/block}

    </section>

    {/block}