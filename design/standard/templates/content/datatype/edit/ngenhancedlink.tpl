{* Edit template for ngenhancedlink eZ4 datatype *}
{default attribute_base='ContentObjectAttribute'
         html_class='full'}
<textarea id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="{eq( $html_class, 'half' )|choose( 'box', 'halfbox' )} ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" name="{$attribute_base}_data_ngenhancedlink_json_{$attribute.id}" rows="5" cols="70">{$attribute.data_text|wash}</textarea>
{/default}
