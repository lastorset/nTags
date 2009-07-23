<?php
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentcachemanager.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
require_once( "extension/ntags/modules/ntags/ajax.inc.php" );

$Result['pagelayout'] = false;
header("Content-type: text/plain");

// TODO: Needs to check access rights

nTagsAjax();

ajax_shutdown();

function nTagsAjax() {
	// Check input
	$http = eZHTTPTool::instance();
	if( !$http->hasPostVariable( "attrID" ) ) {
		echo "error: ". eZi18n( "ntags/ajax", "No attrID specified" );
		return;
	} else if( !$http->hasPostVariable( "version" ) ) {
		echo "error: ". eZi18n( "ntags/ajax", "No version number specified" );
		return;
	} else if( !$http->hasPostVariable( "tags" ) && !$http->hasPostVariable( "removeAll" ) ) {
		echo "error: ". eZi18n( "ntags/ajax", "'tags' and 'removeAll' not specified: nothing to do" );
		return;
	}

	// Fetch node
	$attrID = $http->postVariable( "attrID" );
	$version = $http->postVariable( "version" );
	$attr = eZContentObjectAttribute::fetch( $attrID, $version );
	if( $attr == null ) {
		echo "error: Could not fetch attribute with id '$attrID' and version '$version'";
		return;
	}

	// Retrieve keyword attribute and new keywords
	$tags = $attr->content();
	//$newTags = $_POST["tags"];
	$newTags = $http->postVariable( "tags" );

	// Store attribute
	$tags->setKeywordArray( $newTags );
	$tags->store( $attr );

	// Clear cache for object
	eZContentCacheManager::clearObjectViewCache( $attr->ContentObjectID );

	// Return success and the index of the given element
	echo "success: ". $http->postVariable( "index" );
}
?>
