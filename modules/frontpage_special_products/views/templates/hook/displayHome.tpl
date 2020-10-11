<div class="products">
    {foreach from=$frontpage_special_products item=product}
        <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="0" itemscope="" itemtype="http://schema.org/Product">
            <div class="thumbnail-container">
                <a href="{$product.url}" class="thumbnail product-thumbnail">
                    <img src="{$product.cover.bySize.home_default.url}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url="{$product.cover.large.url}">
                </a>
                <div class="highlighted-informations no-variants hidden-sm-down">

                <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" name="id_product" value="{$product.id_product}" class="product_page_product_id">
                    <input type="hidden" name="qty" value="1">
                    <button class="btn btn-unstyle add-to-cart" data-button-action="add-to-cart" type="submit">
                        <i class="fa fa-plus"></i>
                        Dodaj do koszyka
                    </button>
                </form>
            </div>
            <div class="product-description">

                <h3 class="h3 product-title" itemprop="name"><a href="{$product.link}">{$product.name|truncate:30:'...'}</a></h3>
                <div class="product-price-and-shipping">

                    <span class="sr-only">Cena podstawowa</span>
                    {if $product.has_discount}
                    <span class="discount-amount discount-product">{$product.regular_price} zł</span>
                    {/if}
                    <span class="sr-only">Cena</span>
                    <span itemprop="price" class="price">{$product.price}</span>

                </div>
            </div>
            {foreach from=$product.flags item=flag}
            <ul class="product-flags">
                <li class="product-flag {if $flag.type == 'new'}new{/if} {if $flag.type == 'discount'}discount{/if}">{if $flag.type == 'new'}Nowy{/if} {if $flag.type == 'discount'}{$product.discount_amount_to_display}{/if} </li>
            </ul>
            {/foreach}

    </article>
    {/foreach}
</div>


{* <span class="sr-only">Cena podstawowa</span>
<span class="regular-price">{$product.price_regular} zł</span>
<span class="discount-amount discount-product">{$product.price_diff} zł</span>
<span class="sr-only">Cena</span>
<span itemprop="price" class="price">{$product.price_regular} zł</span> *}


{* <div class="products">
    {foreach from=$frontpage_special_products item=product}
    <article class="product-miniature js-product-miniature" data-id-product="{$product.id}" data-id-product-attribute="0" itemscope="" itemtype="http://schema.org/Product">
        <div class="thumbnail-container">

            <a href="{$product.link}" class="thumbnail product-thumbnail">
                <img src="{$product.cover}" alt="{$product.name|truncate:30:'...'}" data-full-size-image-url="{$product.cover}">
            </a>

            <div class="highlighted-informations no-variants hidden-sm-down">

                <form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
                    <input type="hidden" name="token" value="{$static_token}">
                    <input type="hidden" name="id_product" value="{$product.id}" class="product_page_product_id">
                    <input type="hidden" name="qty" value="1">
                    <button class="btn btn-unstyle add-to-cart" data-button-action="add-to-cart" type="submit">
                        <i class="fa fa-plus"></i>
                        Dodaj do koszyka
                    </button>
                </form>

            </div>
        </div>

        <div class="product-description">

            <h3 class="h3 product-title" itemprop="name"><a href="{$product.link}">{$product.name|truncate:30:'...'}</a></h3>


            <div class="product-price-and-shipping">
                <span class="sr-only">Cena podstawowa</span>
                <span class="regular-price">{$product.price_regular} zł</span>
                <span class="discount-amount discount-product">{$product.price_diff} zł</span>
                <span class="sr-only">Cena</span>
                <span itemprop="price" class="price">{$product.price_regular} zł</span>
            </div>

        </div>

        <ul class="product-flags">
            <ul class="product-flags">
            </ul>
        </ul>

    </article>
    {/foreach}
</div> *}