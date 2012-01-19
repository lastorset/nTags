<?php
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "kernel/classes/ezcache.php" );
require_once( "extension/ntags/modules/ntags/ajax.inc.php" );

// Init
$Result['pagelayout'] = false;
header("Content-type: text/plain");
$http = eZHTTPTool::instance();

if($http->hasVariable( "SaveSortButton" ) && $http->hasVariable( "tags" ) ) {
	saveSort(json_decode($http->variable( "tags" ) ) );
} else {
	echo "error: missing parameters SaveSortButton and tags";
}

function saveSort($tags) {
	$ini = eZINI::instance( "tags.ini" );
	$ini->setVariable( "Tags", "Tags", $tags );
	$ini->save(false, false, "append");
	echo "success";
	eZCache::clearByTag("ini");
}

ajax_shutdown();
?>
