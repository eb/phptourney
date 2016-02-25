<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_UPLOAD_SERVERLIST", "H_UPLOAD_SERVERLIST");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_UPLOAD_SERVERLIST", "B_UPLOAD_SERVERLIST");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
