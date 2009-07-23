{* Uncomment if needed
<script type="text/javascript" src="/extension/ntags/design/standard/javascript/jquery-ui-1.7.2.custom.min.js"></script>
*}
<script type="text/javascript" src="/extension/ntags/design/standard/javascript/ntags_multitag.js"></script>
<div class="content-navigation-childlist nTagsView" id="nTagsChildren">
	{if fetch( 'user', 'has_access_to',
       hash( 'module',   'ntags',
             'function', 'multitag') )}
	<input type="submit" class="button" id="nTagsEditChildren" value="Endre stikkord" />
		<input type="submit" class="button nTagsEditControls" id="nTagsSave" value="Lagre stikkord" />
	<div id="nTagsAddTagDiv" class="nTagsEditControls">
		<input type="submit" class="button nTagsEditControls" id="nTagsAddTag" value="Legg til stikkord" />
		<input type="text" id="nTagsNewTag"/>
		<input type="checkbox" id="nTagsNewTagChecked"/>
		<label for="nTagsNewTagChecked">Merk alle med det nye stikkordet</label>
	</div>
	{/if}

    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/node/view/full' )}</th>

        {* Tags column *}
        <th class="tags">{'Tags'|i18n( 'design/admin/node/view/full' )}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>

        {* Edit column *}
        <th class="edit">&nbsp;</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name|wash
         node_name=$node.name
         nodeContent=$Nodes.item.object}

        <tr class="{$Nodes.sequence}" id="nTags_object_{$nodeContent.id}">

        {* Remove checkbox *}
        <td>
        {section show=$Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/section}
        </td>

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}
	{if $nodeContent.class_identifier|eq('user')}
		{if not($nodeContent.data_map['user_account'].content.is_enabled)}
		   <span class="userstatus-disabled">{'(disabled)'|i18n("design/admin/node/view/full")}</span>
		{/if}
		{if $nodeContent.data_map['user_account'].content.is_locked}
		   <span class="userstatus-disabled">{'(locked)'|i18n("design/admin/node/view/full")}</span>
		{/if}
	{/if}		
         </td>

		{* Tags *}
		{def $attribute_tagsearch=fetch( 'content', 'contentobject_attributes',
									hash( 'version', $nodeContent.current ) )}
		<td {foreach $attribute_tagsearch as $attribute}
			{if $attribute.data_type_string|eq( "ezkeyword" )}
			class="tags" id="nTags_{$attribute.id}">
			<script type="text/javascript">$("#nTags_{$attribute.id}").data("version", {$nodeContent.current_version});</script>
				{foreach $attribute.content.keywords as $tag_u}
					{def $tag=$tag_u|wash()
					     $tagShort=$tag|wash()|explode(" ")|implode("")|downcase()} {* Remove spaces. *}
					<label class="checked saved" for="nTags_{$attribute.id}_{$tagShort}">{$tag}
						<input type="checkbox" checked="checked" id="nTags_{$attribute.id}_{$tagShort}" name="nTags_{$attribute.id}_{$tagShort}" />
					</label>
					{undef $tagShort $tag}
				{/foreach}
				{break}
			{/if}
		{/foreach}
		{undef $attribute_tagsearch}
		 </td>

        {* Class type *}
        <td class="class">{$Nodes.item.class_name|wash()}</td>

        {* Edit button *}
        <td>
        {section show=$Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {section-else}
            <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit %child_name.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" />
        {/section}
        </td>
  </tr>

{/let}
{/section}

</table>
</div>

