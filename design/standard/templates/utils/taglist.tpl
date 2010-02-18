<script type="text/javascript" src="/extension/ntags/design/standard/javascript/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="/extension/ntags/design/standard/javascript/ntags_taglist.js"></script>

<div class="context-block ntags">
{if $clear_cache_note}
	<div class="message-feedback">
		<h2>{'Please clear the template cache'|i18n('ntags/utils/taglist')}</h2>
		<p>
		{'Your changes to predefined tags will take effect after the template cache has been cleared.'|i18n('ntags/utils/taglist')}
		</p>
	</div>
{/if}


{**** LIST/REMOVE  ****}
<form id="taglistform" class="nTags" method="post" action={'ntags/taglist'|ezurl()}>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Predefined tags'|i18n('ntags/utils/taglist')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">

<table class="list sortable" id="taglist" cellspacing="0">
<thead>
<tr>
    <th class="tight"></th>
    <th>{'Name'|i18n( 'design/admin/setup/cache' )}</th>
</tr>
</thead>

{foreach $predef_tags as $predef sequence array( bglight, bgdark ) as $rowClass}

<tr class="{$rowClass}">
{* Checkbox *}
<td><input type="checkbox" name="RemoveTagList[]" id="tag_{$predef}" value="{$predef}" /><span class="sortableArrow ui-icon ui-icon-arrowthick-2-n-s"></span></td>

{* Tag *}
<td class="nTags"><label class="tag" for="tag_{$predef}">{$predef}</label></td>

</tr>
{/foreach}
</table>

{* DESIGN: Content END *}</div></div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block" id="sortControls" style="display: none">
    <input class="button" type="submit" id="DoneSortingButton" name="DoneSortingButton" value="{"Don't save"|i18n('ntags/utils/taglist')}" />
    <input class="button" type="submit" id="SaveSortButton" name="SaveSortButton" value="{'Save order'|i18n('ntags/utils/taglist')}" />
	<span id="sortInstructions">{'Sort the items by dragging with the mouse.'|i18n('ntags/utils/taglist')}</span>
	<span id="saveStatus" style="display: none">{'Saving sort order...'|i18n('ntags/utils/taglist')}<img src="/extension/ntags/design/standard/images/ajax-loader.gif"/></span>
	<div id="saveResult" class="ui-state-error ui-corner-all" style="display: none"><span class="ui-icon ui-icon-alert"></span><span class="message"></span></div>
</div>
<div class="block">
    <input class="button" type="submit" id="RemoveButton" name="RemoveButton" value="{'Remove selected'|i18n('ntags/utils/taglist')}" />
    <input class="button" type="submit" id="SortButton" name="SortButton" value="{'Change order'|i18n('ntags/utils/taglist')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>


<div class="context-block ntags">

{**** CREATE NEW ****}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Define new tag'|i18n('ntags/utils/taglist')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">
<label for="ntags_createtag">{'Create new tag:'|i18n('ntags/utils/taglist')}</label>
<input type="text" id="ntags_newtagname" name="ntags_newtagname" size="45" />

{* DESIGN: Content END *}</div></div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="CreateButton" value="{'Create new'|i18n('ntags/utils/taglist')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>
</div>
