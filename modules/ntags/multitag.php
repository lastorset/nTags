<?php
include_once( "kernel/common/template.php" );

// Init
$tpl = templateInit();
$Result['path'] = array( 
			array( 'text' => ezpI18n::tr( 'ntags', 'Tags' ) ), 
			array( 'url' => '/ntags/multitag', 'text' => ezpI18n::tr('ntags', 'Tag multiple items') ) 
);
$http = eZHTTPTool::instance();

$tpl->setVariable( 'node_id', $Params['NodeID'] );
$tpl->setVariable( 'offset', $Params['Offset'] );

$Result['content'] = $tpl->fetch ( 'design:utils/multitag.tpl' );
$Result['left_menu'] = 'design:parts/ntags/menu.tpl';
?>
