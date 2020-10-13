{extends file="helpers/form/form.tpl"}
<div class="panel">
    {block name="field"}
        {if $input.type == 'file'}
            <div class="row">
                <div class="col-lg-6">
                    {if isset($fields[0]['form']['images'])}
                        <img src="{$image_baseurl}{$fields[0]['form']['images']}" class="img-thumbnail" />
                    {/if}
                </div>
            </div>
        {/if}
        {$smarty.block.parent}
    {/block}
</div>