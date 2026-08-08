{* View template for ngenhancedlink eZ4 datatype *}
{default attribute_base='ContentObjectAttribute'}
<div class="ngenhancedlink-field">
    {if $attribute.data_text|ne('')}
        {def $value = $attribute.content}
        {if and($value, $value.reference|ne(''))}
            {if eq($value.type, 'external')}
                <a href="{$value.reference|wash( xhtml )}"{if eq($value.target, 'link_new_tab')} target="_blank"{/if}>{$value.label|wash( xhtml )}</a>
            {else}
                <span>{$value.label|wash( xhtml )}</span>
            {/if}
        {else}
            <span>–</span>
        {/if}
        {undef $value}
    {/if}
</div>
{/default}
