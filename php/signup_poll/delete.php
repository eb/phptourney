<?php

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SIGNUP_POLL_REMOVED", "H_MESSAGE_SIGNUP_POLL_REMOVED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  dbQuery("DELETE FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
  $content_tpl->parse("H_MESSAGE_SIGNUP_POLL_REMOVED", "B_MESSAGE_SIGNUP_POLL_REMOVED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
