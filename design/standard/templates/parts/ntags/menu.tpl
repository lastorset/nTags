{* "Divitis" in order to hook into eZ publish styling *}
<div class="box-header"> <div class="box-ml">
	<h4>{"Settings"|i18n("ntags")}</h4>
</div> </div>
<div class="box-bc"> <div class="box-ml"> <div class="box-content">
	<ul class="leftmenu-items">
		<li><a href={"/ntags/taglist"|ezurl()}>{'Predefined tags'|i18n('ntags')}</a>
	</ul>
</div> </div> </div>

<div id="content-tree">
	<div class="box-header"> <div class="box-ml">
		<h4>{"Tag multiple items"|i18n("ntags")}</h4>
	</div> </div>
	<div class="box-bc"> <div class="box-ml"> <div class="box-content">
		<div id="contentstructure">
		{include
			uri='design:contentstructuremenu/content_structure_menu_dynamic.tpl'
			csm_menu_item_click_action='ntags/multitag'
		}
		</div>
	</div> </div> </div>
</div>
