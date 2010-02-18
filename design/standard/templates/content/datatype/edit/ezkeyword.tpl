<script type="text/javascript" src="/extension/ntags/design/standard/javascript/ntags_edit.js"></script>
{default attribute_base=ContentObjectAttribute}
{* Find id of attribute *}
{def $attr_id="ezcoa-"}
{if ne( $attribute_base, 'ContentObjectAttribute' )}
	{set $attr_id=$attr_id|append($attribute_base, "-")}
{/if}
{set $attr_id=$attr_id|append($attribute.contentclassattribute_id, "_", $attribute.contentclass_attribute_identifier)}

{* Get lists of main tags and current tags. *}
{def $taglist_untrimmed=ezini( "Tags", "Tags", "tags.ini" )}
{def $storedTags=$attribute.content.keyword_string|explode(", ")}

{* Trim tags. *}
{def $taglist = array()}
{foreach $taglist_untrimmed as $tag}
	{set $taglist = $taglist|append( $tag|trim() )}
{/foreach}

<div class="nTags">

<input type="checkbox" checked="checked" id="{$attr_id}_showtags"
onchange="toggleThese = $('#{$attr_id}_maintags'); if (this.checked) toggleThese.slideDown('normal'); else toggleThese.slideUp('normal');"
/><label for="{$attr_id}_showtags">{"Show predefined tags"|i18n("ntags/content/edit")}</label>

{* Checkboxes for each main tag. *}
<ul id="{$attr_id}_maintags" class="ntagsTags ntagsPopup">
{foreach $taglist|sort as $tagFull }
<li>
	{if $storedTags|contains( $tagFull )}
		{def $checked="checked='checked'"}
		<script type="text/javascript"> nTags.edit.addCheckedTag("{$tagFull}"); </script>
	{else}
		{def $checked=""}
	{/if}
	{def $tagShort=$tagFull|explode(" ")|implode("")|downcase()} {* Remove spaces. *}
	<input type="checkbox" {$checked} id="tag_{$tagShort}" onchange="nTags.edit.setTag('{$tagFull}', this, '{$attr_id}_free', '{$attr_id}')"/><label for="tag_{$tagShort}">{$tagFull}</label>
	{undef $checked $tagShort}
</li>
{/foreach}
</div>

{* Free tags are free-text tags specified per object. *}
{def $freeTags=""}
{foreach $storedTags as $tag}
	{if and( not( $taglist|contains( $tag ) ), $tag|count_chars|gt(0) )}
		{set $freeTags = $freeTags|append( $tag )|append( ", " )}
	{/if}
{/foreach}

<h5>{"Free tags"|i18n("ntags/content/edit")}</h5>
<input id="{$attr_id}_free" class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" onchange="nTags.edit.genAttribute('{$attr_id}_free', '{$attr_id}')" type="text" size="70" value="{$freeTags}"  />

<input id="{$attr_id}" class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="hidden" size="70" name="{$attribute_base}_ezkeyword_data_text_{$attribute.id}" value="{$attribute.content.keyword_string|wash(xhtml)}"  />

<script type="text/javascript"> nTags.edit.scanCheckboxes( "{$attr_id}" ); nTags.edit.genAttribute('{$attr_id}_free', '{$attr_id}');</script>
</div> {* nTags *}
{/default}
