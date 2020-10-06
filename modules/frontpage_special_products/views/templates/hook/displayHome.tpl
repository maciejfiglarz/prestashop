<div id="frontpage_special_products">
    {foreach from=$frontpage_special_products item=product}
    <div class="product">
        <a href="{$product.link}" class="overlay_link"></a>
        <div class="left">
            <h2 class="name">
                {assign var=name_parts value="| "|explode:$product.name}
                <span>{$name_parts[0]}</span>{if isset($name_parts[1])}{if $name_parts[0] != ''}<br />{/if}<b>{$name_parts[1]}</b>{/if}
            </h2>
            <p class="desc">{$product.desc}</p>
            <a href="{$product.link}" class="btn btn-unstyle my_btn">sprawdź</a>
            <div class="prices">
                <span class="price">{$product.price} zł</span>
                {if $product.price_diff > 0}
                    <span class="price_regular">{$product.price_regular} zł</span>
                {/if}
            </div>
        </div>
        <div class="right">
            <figure class="image">
                <img src="{$product.cover}" alt="{$product.name}">
            </figure>
        </div>
    </div>
    {/foreach}
</div>