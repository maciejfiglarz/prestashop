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


{* <div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        {foreach from=$products item="product"}
        {include file="catalog/_partials/miniatures/slider-product.tpl" product=$product isSlider="1"}
        {/foreach}
        ...
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>

    <!-- If we need navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

    <!-- If we need scrollbar -->
    <div class="swiper-scrollbar"></div>
</div> *}
{* <div class="container">
    <div class="row"> *}
        <div class="owl-carousel">
            {* <div class="item"><img src="https://komentatory.pl/upload/graphic/2020-40/143.jpeg" alt="Owl Image"></div>
            <div class="item"><img src="https://komentatory.pl/upload/graphic/2020-40/143.jpeg" alt="Owl Image"></div>
            <div class="item"><img src="https://komentatory.pl/upload/graphic/2020-40/143.jpeg" alt="Owl Image"></div>
            <div class="item"><img src="https://komentatory.pl/upload/graphic/2020-40/143.jpeg" alt="Owl Image"></div>
            <div class="item"><img src="https://komentatory.pl/upload/graphic/2020-40/143.jpeg" alt="Owl Image"></div> *}
            {foreach from=$products item="product"}
            {include file="catalog/_partials/miniatures/slider-product.tpl" product=$product isSlider="1"}
            {/foreach}
        {* </div>
    </div> *}
</div>