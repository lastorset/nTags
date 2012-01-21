<?php
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentcachemanager.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "kernel/classes/ezcache.php" );

// TODO: Needs to check access rights

class nTagsServer extends ezjscServerFunctions {
	public static function multitag( $args ) {
		// Check input
		$http = eZHTTPTool::instance();
		if( !$http->hasPostVariable( "attrID" ) ) {
			return "error: ". ezpI18n::tr( "ntags/ajax", "No attrID specified" );
		} else if( !$http->hasPostVariable( "version" ) ) {
			return "error: ". ezpI18n::tr( "ntags/ajax", "No version number specified" );
		} else if( !$http->hasPostVariable( "tags" ) && !$http->hasPostVariable( "removeAll" ) ) {
			return "error: ". ezpI18n::tr( "ntags/ajax", "'tags' and 'removeAll' not specified: nothing to do" );
		}

		// Fetch node
		$attrID = $http->postVariable( "attrID" );
		$version = $http->postVariable( "version" );
		$attr = eZContentObjectAttribute::fetch( $attrID, $version );
		if( $attr == null ) {
			return "error: Could not fetch attribute with id '$attrID' and version '$version'";
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
		return "success: ". $http->postVariable( "index" );
	}

	public static function saveTaglistSort( $args ) {
		$http = eZHTTPTool::instance();

		if( !( $http->hasVariable( "SaveSortButton" ) && $http->hasVariable( "tags" ) ))
			throw new Exception("missing parameters SaveSortButton and tags");

		$tags = $http->variable( "tags" );

		// Simple sanity check
		if ( !( is_array( $tags ) && count( $tags ) > 0) )
			throw new Exception("Submitted tag set was empty");

		$ini = eZINI::instance( "tags.ini" );
		$ini->setVariable( "Tags", "Tags", $tags );
		$ini->save(false, false, "append");
		eZCache::clearByTag("ini");
		return;
	}
}
?>
