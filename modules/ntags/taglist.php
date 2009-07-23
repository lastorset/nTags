<?php
include_once( "kernel/common/template.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "kernel/classes/ezcache.php" );

// Init
$ini = eZINI::instance( "tags.ini" );
$tpl = templateInit();
$Result['path'] = array( 
			array( 'text' => ezi18n( 'ntags', 'Tags' ) ), 
			array( 'url' => '/ntags/taglist', 'text' => ezi18n('ntags', 'Predefined tags') ) 
);
$http = eZHTTPTool::instance();
$clearCacheNote = false;

// Process POST
// New tags
if( $http->hasVariable('CreateButton') && $http->hasVariable('ntags_newtagname') ) {
	$taglist = $ini->variable( "Tags", "Tags" );
	$newtags = explode( ",", $http->variable( "ntags_newtagname" ) );
	foreach( $newtags as $newtag ) {
		$taglist[] = trim( $newtag );
	}
	$ini->setVariable( "Tags", "Tags", $taglist );
	$ini->save(false, false, "append");
	$clearCacheNote = true;
}
// Remove tags
if( $http->hasVariable('RemoveButton') && $http->hasVariable('RemoveTagList') ) {
	$iniTags = $ini->variable( "Tags", "Tags" );
	$removeTags = $http->variable( "RemoveTagList");
	for($i = 0; $i < sizeof($removeTags); $i++) {
		$index = array_search($removeTags[$i], $iniTags);
		if ($index !== false) {
			unset($iniTags[$index]);
		}
	}
	$ini->setVariable( "Tags", "Tags", $iniTags );
	$ini->save(false, false, "append");
	$clearCacheNote = true;
}

eZCache::clearByTag("ini");
 
// Get tag list
$predefTags = $ini->variable( "Tags", "Tags" );
 
$tpl->setVariable( 'predef_tags', $predefTags );
$tpl->setVariable( 'clear_cache_note', $clearCacheNote );

$Result['content'] = $tpl->fetch ( 'design:utils/taglist.tpl' );
?>
