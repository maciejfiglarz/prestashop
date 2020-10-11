{* <section>
    <h1>{l s='Our Products' d='Modules.Featuredproducts.Shop'}</h1>
    <div class="slider-products">
        {foreach from=$products item="product"}
            {include file="catalog/_partials/miniatures/product.tpl" product=$product}
        {/foreach}
    </div>
    <a href="{$allProductsLink}">{l s='All products' d='Modules.Featuredproducts.Shop'}</a>
</section>
*}
<div class="owl-carousel">
    {foreach from=$products item="product"}
    {include file="catalog/_partials/miniatures/slider-product.tpl" product=$product isSlider="1"}
    {/foreach}
    {* <i class='fa fa-angle-left owl-prev'></i>
    <i class='fa fa-angle-right owl-next'></i> *}

</div>