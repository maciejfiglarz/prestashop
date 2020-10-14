<div class="feature-slider">
    <h2 class="owl-carousel-title">{$title}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="owl-carousel">
                {foreach from=$products item="product"}
                    {include file="catalog/_partials/miniatures/slider-product.tpl" product=$product isSlider="1"}
                {/foreach}
                {* <i class='fa fa-angle-left owl-prev'></i>
                <i class='fa fa-angle-right owl-next'></i> *}
            </div>
        </div>
    </div>
</div>