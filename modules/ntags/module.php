<?php
$Module = array(
"name" => 'nTags utilities'
);
$ViewList = array();
$ViewList['taglist'] = array(
	'functions' => array( 'edit_predefined' ),
	'script' => 'taglist.php',
    'default_navigation_part' => 'ntags'
);
$ViewList['multitag'] = array(
	'functions' => array( 'multitag' ),
	'script' => 'multitag.php',
    'default_navigation_part' => 'ntags',
    'params' => array( 'NodeID'),
    'unordered_params' => array( 'offset' => 'Offset' )
);
$FunctionList = array();
$FunctionList[ 'edit_predefined' ] = array();
$FunctionList[ 'multitag' ] = array();
?>
