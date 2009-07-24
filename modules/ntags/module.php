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
$ViewList['taglist_ajax'] = array(
	'functions' => array( 'edit_predefined' ),
	'script' => 'taglist_ajax.php',
	'params' => array('tags')
);
$ViewList['multitag_ajax'] = array(
	'functions' => array( 'multitag' ),
	'script' => 'multitag_ajax.php',
);
$FunctionList = array();
$FunctionList[ 'edit_predefined' ] = array();
$FunctionList[ 'multitag' ] = array();
?>
