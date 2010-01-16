<?php

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SIGNUP_POLL_ADDED", "H_MESSAGE_SIGNUP_POLL_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_HEADING", "H_WARNING_HEADING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING_CHOICES", "H_WARNING_CHOICES");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $is_complete = 1;
  if ($_REQUEST['heading'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_HEADING", "B_WARNING_HEADING");
  }
  if ($_REQUEST['body'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_BODY", "B_WARNING_BODY");
  }
  if ($_REQUEST['choices'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_CHOICES", "B_WARNING_CHOICES");
  }

  if ($is_complete)
  {
    $demos_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
    if (dbNumRows($demos_ref) == 1)
    {
      dbQuery("UPDATE `{$cfg['db_table_prefix']}signup_polls` SET " .
	       "`heading` = '{$_REQUEST['heading']}', " .
	       "`body` = '{$_REQUEST['body']}', " .
	       "`choices` = '{$_REQUEST['choices']}' " .
		"WHERE `id_season` = {$_REQUEST['sid']}");
    }
    else
    {
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}signup_polls` (`id_season`, `choices`, `heading`, `body`, `submitted`) " .
		"VALUES ('{$_REQUEST['sid']}', '{$_REQUEST['choices']}', '{$_REQUEST['heading']}', '{$_REQUEST['body']}', NOW())");
    }
    $content_tpl->parse("H_MESSAGE_SIGNUP_POLL_ADDED", "B_MESSAGE_SIGNUP_POLL_ADDED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }

  if (!$is_complete)
  {
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
