<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_IP_UNBANNED", "H_MESSAGE_IP_UNBANNED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for admins only
if ($user['usertype_admin'])
{
  $id_ban = intval($_REQUEST['opt']);
  dbQuery("DELETE FROM `bans` WHERE `id` = $id_ban");
  $content_tpl->parse("H_MESSAGE_IP_UNBANNED", "B_MESSAGE_IP_UNBANNED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
