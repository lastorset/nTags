{ezcss_require( 'ntags.css' )}
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio' ) )}

{* Uncomment if needed
<script type="text/javascript" src="/extension/ntags/design/standard/javascript/jquery-ui-1.7.2.custom.min.js"></script>
*}

{let node=fetch( content, node, hash( node_id, $node_id ) )
     item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     children_count=fetch( content, list_count, hash( parent_node_id, $node.node_id ) )
     children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          sort_by, $node.sort_array,
                                          limit, $number_of_items,
                                          offset, $offset ) ) }

<h1 class="context-title box-header">{"Tag multiple items"|i18n( 'ntags' )}</h1>

<div class="content-navigation-childlist nTagsView" id="nTagsChildren">
<div class="number-of-items">
{switch match=$number_of_items}
{case match=25}
	<a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
	<span class="current">25</span>
	<a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

	{/case}

	{case match=50}
	<a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
	<a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
	<span class="current">50</span>
	{/case}

	{case}
	<span class="current">10</span>
	<a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
	<a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
	{/case}

	{/switch}
</div>

	{if fetch( 'user', 'has_access_to',
       hash( 'module',   'ntags',
             'function', 'multitag') )}
	<script type="text/javascript" src="/extension/ntags/design/standard/javascript/ntags_multitag.js"></script>
	{include uri='file:extension/ntags/design/ntags/init.tpl'}
	<div class="nTagsEditActivation">
	<input type="submit" class="button" id="nTagsEditChildren" value="{"Edit tags"|i18n("ntags/admin/view")}" />
	<input type="submit" class="button nTagsEditControls" id="nTagsSave" value="{"Save tags"|i18n("ntags/admin/view")}" />
	</div>
	<form id="nTagsAddTagDiv" class="nTagsEditControls">
		<input type="submit" class="button nTagsEditControls" id="nTagsAddTag" value="{"Add tags"|i18n("ntags/admin/view")}" />
		<input type="text" id="nTagsNewTag" placeholder="{"Separate with commas"|i18n("ntags/admin/view")}"/>
		<input type="checkbox" id="nTagsNewTagChecked"/>
		<label for="nTagsNewTagChecked">{"Apply the new tag to all items"|i18n("ntags/admin/view")}</label>
	</form>
	{/if}

    <table class="list" cellspacing="0">
    <tr>
        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/node/view/full' )}</th>

        {* Tags column *}
        <th class="tags">{'Tags'|i18n( 'design/admin/node/view/full' )}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name|wash
         node_name=$node.name
         nodeContent=$Nodes.item.object}

        <tr class="{$Nodes.sequence}" id="nTags_object_{$nodeContent.id}">

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}
	{if $nodeContent.class_identifier|eq('user')}
		{if not($nodeContent.data_map['user_account'].content.is_enabled)}
		   <span class="userstatus-disabled">{'(disabled)'|i18n("design/admin/node/view/full", "Regarding user objects")}</span>
		{/if}
		{if $nodeContent.data_map['user_account'].content.is_locked}
		   <span class="userstatus-disabled">{'(locked)'|i18n("design/admin/node/view/full", "Regarding user objects")}</span>
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
				{if $attribute.content.keywords}
					{foreach $attribute.content.keywords as $tag_u}
						{def $tag=$tag_u|wash()
							 $tagShort=$tag|wash()|explode(" ")|implode("")|downcase()} {* Remove spaces. *}
						<label class="checked saved" for="nTags_{$attribute.id}_{$tagShort}">{$tag}
							<input type="checkbox" checked="checked" id="nTags_{$attribute.id}_{$tagShort}" name="nTags_{$attribute.id}_{$tagShort}" />
						</label>
						{undef $tagShort $tag}
					{/foreach}
				{/if}
				{break}
			{/if}
		{/foreach}
		{undef $attribute_tagsearch}
		 </td>

        {* Class type *}
        <td class="class">{$Nodes.item.class_name|wash()}</td>
  </tr>

{/let}
{/section}

</table>

{def $view_parameters=hash( "offset", $offset )}
{include name=navigator
	uri="design:navigator/google.tpl"
	page_uri=$uri
	item_count=$children_count
	view_parameters=$view_parameters
	item_limit=$number_of_items}
</div>

