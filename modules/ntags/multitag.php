<?php
include_once( "kernel/common/template.php" );

// Init
$tpl = templateInit();
$Result['path'] = array( 
			array( 'text' => ezpI18n::tr( 'ntags', 'Tags' ) ), 
			array( 'url' => '/ntags/multitag', 'text' => ezpI18n::tr('ntags', 'Tag multiple items') ) 
);

$tpl->setVariable( 'node_id', $Params['NodeID'] );
$tpl->setVariable( 'offset', $Params['Offset'] );
$tpl->setVariable( 'uri', '/ntags/multitag/'. $Params['NodeID'] );

$Result['content'] = $tpl->fetch ( 'design:utils/multitag.tpl' );
$Result['left_menu'] = 'design:parts/ntags/menu.tpl';
?>
