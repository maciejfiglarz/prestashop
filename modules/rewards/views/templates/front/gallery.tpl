{* <div class="gallery">
    <div class="row">
        <div class="col-md-12">
            <h2 class="gallery__title">{$title}</h2>
            <div class="gallery__wrap">
                {foreach from=$rewards item=reward}
                    <div class="gallery__item" style="background: url({$reward.image_url});background-size: cover;">
                        {if $reward.title }
                            <span class=" caption">
                                <h2>{$reward.title}</h2>
                            </span>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
*}
<div class="gallery">
    <div class="row">
        <div class="col-md-12">

            {* <div class="mdb-lightbox"> *}
                {foreach from=$rewards item=reward}
                <figure class="col-md-4">
                    <a href="{$reward.image_url}" data-size="1600x1067">
                        <img alt="picture" src="{$reward.image_url}" class="img-fluid">
                    </a>
                </figure>
                {/foreach}
                {* <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(145).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(145).jpg" class="img-fluid">
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(150).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(150).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(152).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(152).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(42).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(42).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(151).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(151).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(40).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(40).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">   {* <div class="mdb-lightbox">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(148).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(148).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(147).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(147).jpg" class="img-fluid" />
                    </a>
                </figure>

                <figure class="col-md-4">
                    <a href="https://mdbootstrap.com/img/Photos/Lightbox/Original/img%20(149).jpg" data-size="1600x1067">
                        <img alt="picture" src="https://mdbootstrap.com/img/Photos/Lightbox/Thumbnail/img%20(149).jpg" class="img-fluid" />
                    </a>
                </figure> *}

            {* </div> *}

        </div>
    </div>
</div>