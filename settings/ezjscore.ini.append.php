<?php /* vim:set syntax=dosini:
[ezjscServer]
# This broad permission (ezjscore/ntags) is used for granting access to all JS functions in nTags.
# There are finer-grained permissions for ntags/taglist and ntags/multitag, but they only control templates.
FunctionList[]=ntags

[ezjscServer_nTagsServer]
File=extension/ntags/classes/ntags_server.php
Class=nTagsServer
Functions=ntags
*/ ?>
