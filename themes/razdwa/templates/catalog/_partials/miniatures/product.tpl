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
    {block name='product_miniature_item'}

    <article class="main-product col-5" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
        {* {block name='product_thumbnail'} *}
        <div class="content">
            {if $product.cover}
            <a href="{$product.url}" class="image">
                <img class="img-fluid" src="{$product.cover.bySize.home_default.url}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url="{$product.cover.large.url}" />
            </a>
            {else}
            <a href="{$product.url}" class="">
                <img class="img-fluid" src="{$urls.no_picture_image.bySize.home_default.url}" />
            </a>
            {/if}
            <h3 class="title"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h3>
            {* {/block} *}
            {if $product.isbn}
            <div class="isbn">
                <span class="">{l s='ISBN' d='Shop.Theme.Transformer'}: </span>
                <span class="">{$product.isbn}</span>
            </div>
            {/if}
            {if $product.show_price}
            <div class="price">
                {if $product.has_discount}
                {* {hook h='displayProductPriceBlock' product=$product type="old_price"}
                <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span> *}
                <span class="discount">{$product.regular_price}</span>
                {/if}
                <span class="price">{$product.price}</span>
            </div>
            {/if}
        </div>

        <div class="label">

            <form action="{$urls.pages.cart}" method="post">
                <input type="hidden" name="token" value="{$static_token}">
                <input type="hidden" name="id_product" value="{$product.id}">
                {* <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id"> *}
                <input type="hidden" name="qty" value="1">
                <button class="item add-to-cart" data-button-action="add-to-cart">
                    <img class="icon" src="{$urls.img_ps_url}cart.svg" />
                </button>
            </form>

            {* <div class="item">
                <img class="icon" src="{$urls.img_ps_url}cart.svg" />
            </div> *}


            {* <form action="http://fartner.pl/koszyk" method="post" class="add-to-cart-or-refresh">
                <input type="hidden" name="token" value="289ffdd99d7ee3a29a6678052753096d">
                <input type="hidden" name="id_product" value="90" class="product_page_product_id">
                <input type="hidden" name="qty" value="1">
                <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit">
                    <i class="material-icons shopping-cart"></i>
                </button>
            </form> *}
            <a class="item" href="{$product.url}">
                <img class="icon icon--arrow" src="{$urls.img_ps_url}arrow.svg" />
            </a>
        </div>
    </article>







    {* <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
        <div class="thumbnail-container">
            {block name='product_thumbnail'}
            {if $product.cover}
            <a href="{$product.url}" class="thumbnail product-thumbnail">
                <img src="{$product.cover.bySize.home_default.url}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url="{$product.cover.large.url}" />
            </a>
            {else}
            <a href="{$product.url}" class="thumbnail product-thumbnail">
                <img src="{$urls.no_picture_image.bySize.home_default.url}" />
            </a>
            {/if}
            {/block}

            <div class="product-description">
                {block name='product_name'}
                {if $page.page_name == 'index'}
                <h3 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h3>
                {else}
                <h2 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h2>
                {/if}
                {/block}

                {block name='product_price_and_shipping'}
                {if $product.show_price}
                <div class="product-price-and-shipping">
                    {if $product.has_discount}
                    {hook h='displayProductPriceBlock' product=$product type="old_price"}

                    <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
                    <span class="regular-price">{$product.regular_price}</span>
                    {if $product.discount_type === 'percentage'}
                    <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
                    {elseif $product.discount_type === 'amount'}
                    <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
                    {/if}
                    {/if}

                    {hook h='displayProductPriceBlock' product=$product type="before_price"}

                    <span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
                    <span itemprop="price" class="price">{$product.price}</span>

                    {hook h='displayProductPriceBlock' product=$product type='unit_price'}

                    {hook h='displayProductPriceBlock' product=$product type='weight'}
                </div>
                {/if}
                {/block}

                {block name='product_reviews'}
                {hook h='displayProductListReviews' product=$product}
                {/block}
            </div>

            <!-- @todo: use include file='catalog/_partials/product-flags.tpl'} -->
            {block name='product_flags'}
            <ul class="product-flags">
                {foreach from=$product.flags item=flag}
                <li class="product-flag {$flag.type}">{$flag.label}</li>
                {/foreach}
            </ul>
            {/block}

            <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
                {block name='quick_view'}
                <a class="quick-view" href="#" data-link-action="quickview">
                    <i class="material-icons search">&#xE8B6;</i> {l s='Quick view' d='Shop.Theme.Actions'}
                </a>
                {/block}

                {block name='product_variants'}
                {if $product.main_variants}
                {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                {/if}
                {/block}
            </div>
        </div>
    </article> *}
    {/block}