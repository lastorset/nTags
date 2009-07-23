<?php
/**
Causes a clean eZ publish exit in order to prevent debug messages and remembering the AJAX url (if, for instance, the cache is cleared immediately after the AJAX execution). Include in every AJAX file.
*/
function ajax_shutdown() {
    include_once( 'lib/ezutils/classes/ezexecution.php' );
    eZExecution::cleanExit();
}
?>
