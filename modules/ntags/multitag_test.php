<?php
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/content/ezcontentfunctioncollection.php' );
include_once( "kernel/common/template.php" );

// Init
$tpl = templateInit();

// Simplified version of fetchObjectTree
function fetchList($parentNodeID, $sortBy, $offset, $limit, $classID, $attribute_filter, $extended_attribute_filter, $class_filter_type, $class_filter_array, $ignoreVisibility, $objectNameFilter) {
	return eZContentFunctionCollection::fetchObjectTree( $parentNodeID, $sortBy, false, null, $offset, $limit, 1, null,
		$classID, $attribute_filter, $extended_attribute_filter, $class_filter_type, $class_filter_array,
		null, false, $ignoreVisibility, null, true, $objectNameFilter, true );
}

$schengen = fetchList(21016, null, 0, 100, null, null, null, null, null, true, null);


/* Use for gradually outputting results to browser. Use JS later: user first enters all tags and submits, and watches as one by one is checked off as stored. On error, stop and let the user resubmit.
*/
ob_end_flush();
ob_flush();
flush();

echo "<pre style='border: thin solid red'>";
foreach($schengen['result'] as $nodeToFetch) {
	$nodeID = $nodeToFetch->NodeID;
	echo "Fetching $nodeID...";
	$node = eZContentObjectTreeNode::fetch($nodeID);
	$dataMap = $node->dataMap();
	$keywords = $dataMap[ 'tags' ]->content();
	$keywordArray = $keywords->keywordArray();
	echo "Done. Keywords: ". $keywords->keywordString() . "/(". implode( ", ", $keywordArray ) ."). Adding Schengen and storing...";
	$keywordArray[] = "Schengen";
	$keywords->setKeywordArray($keywordArray);
	$keywords->store( $dataMap[ 'tags' ] );
	echo "Done.\n";
}

ob_start(); 
echo "</pre>";
/* End of gradual output */

//$tpl->setVariable( 'schengen', $schengen_node_ids );

$Result['content'] = $tpl->fetch ( 'design:utils/multitag.tpl' );
?>
