{extends file="helpers/form/form.tpl"}
<div class="panel">
    {block name="field"}
        {if $input.type == 'file'}
            <div class="row">
                {if isset($fields[0]['form']['images'])}
                    <img src="{$image_baseurl}{$fields[0]['form']['images']}" class="img-thumbnail" />
                {/if}
            </div>
        {/if}
        {$smarty.block.parent}
    {/block}
</div>