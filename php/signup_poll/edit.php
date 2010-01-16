<?php

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_SIGNUP_POLL", "H_EDIT_SIGNUP_POLL");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $signup_poll_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
  $signup_poll_row = dbFetch($signup_poll_ref);
  $content_tpl->set_var("I_HEADING", $signup_poll_row['heading']);
  $content_tpl->set_var("I_BODY", $signup_poll_row['body']);
  $content_tpl->set_var("I_CHOICES", $signup_poll_row['choices']);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_SIGNUP_POLL", "B_EDIT_SIGNUP_POLL");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
