<div class="container">
    <div class="row gallery">
        <h1 class="gallery__title">{$title}</h1>
        <div class="col-md-12">

            {foreach from=$rewards item=reward}
            <figure class="col-md-4">
                <a href="{$reward.image_url}" data-fancybox="gallery" class="gallery__item" style="background: url({$reward.image_url}); background-size:cover; display:block;" data-size="1600x1067">
                    {if $reward.title}
                    {* <h3 class="gallery__item-title">
                        <div><span>{$reward.title}</span></div>
                    </h3> *}
                    {/if}
                </a>
            </figure>
            {/foreach}
        </div>
    </div>
</div>