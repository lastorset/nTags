<?php
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentcachemanager.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "kernel/classes/ezcache.php" );

// TODO: Needs to check access rights

class IndexedException extends Exception {
	function __construct($index, $msg) {
		parent::__construct("Element ". $index .": ". $msg);
	}
}

class nTagsServer extends ezjscServerFunctions {
	public static function multitag( $args ) {
		// Check input
		$http = eZHTTPTool::instance();
		$index = $http->postVariable( "index" );

		if( !$http->hasPostVariable( "attrID" ) ) {
			throw new IndexedException( $index, ezpI18n::tr( "ntags/ajax", "No attrID specified" ) );
		} else if( !$http->hasPostVariable( "version" ) ) {
			throw new IndexedException( $index, ezpI18n::tr( "ntags/ajax", "No version number specified" ) );
		} else if( !$http->hasPostVariable( "tags" ) && !$http->hasPostVariable( "removeAll" ) ) {
			throw new IndexedException( $index, ezpI18n::tr( "ntags/ajax", "'tags' and 'removeAll' not specified: nothing to do" ) );
		}

		// Fetch node
		$attrID = $http->postVariable( "attrID" );
		$version = $http->postVariable( "version" );
		$attr = eZContentObjectAttribute::fetch( $attrID, $version );
		if( $attr == null ) {
			throw new IndexedException( $index, "Could not fetch attribute with id '$attrID' and version '$version'" );
		}

		// Retrieve keyword attribute and new keywords
		$tags = $attr->content();
		$newTags = $http->postVariable( "tags" );

		// Store attribute
		$tags->setKeywordArray( $newTags );
		$tags->store( $attr );

		// Clear cache for object
		eZContentCacheManager::clearObjectViewCache( $attr->ContentObjectID );

		// Return success and the index of the given element
		return $http->postVariable( "index" );
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
