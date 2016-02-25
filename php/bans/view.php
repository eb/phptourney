<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_BANS", "H_VIEW_BANS");

// Access for headadmins only
if ($user['usertype_admin'])
{
  $ip = $_REQUEST['opt'];
  $content_tpl->set_var("I_IP", htmlspecialchars($ip));
  $ipc = preg_replace("/(.*\\.).*/", "$1*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPC", htmlspecialchars($ipc));
  $ipb = preg_replace("/(.*\\.).*\\..*/", "$1*.*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPB", htmlspecialchars($ipb));
  $ipa = preg_replace("/(.*\\.).*\\..*\\..*/", "$1*.*.*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPA", htmlspecialchars($ipa));
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_VIEW_BANS", "B_VIEW_BANS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
