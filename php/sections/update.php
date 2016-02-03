<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SECTION_EDITED", "H_MESSAGE_SECTION_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NAME", "H_WARNING_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_NAME", "H_WARNING_UNIQUE_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_ABBREVIATION", "H_WARNING_ABBREVIATION");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_ABBREVIATION", "H_WARNING_UNIQUE_ABBREVIATION");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for root only
if ($user['usertype_root'])
{
  $is_complete = 1;
  if ($_REQUEST['name'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_NAME", "B_WARNING_NAME");
  }
  if ($_REQUEST['abbreviation'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_ABBREVIATION", "B_WARNING_ABBREVIATION");
  }
  $id_section = intval($_REQUEST['opt']);
  $name = dbEscape($_REQUEST['name']);
  $abbreviation = dbEscape($_REQUEST['abbreviation']);
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` " .
			   "WHERE `name` = '$name' AND `id` <> $id_section AND `deleted` = 0");
  if (dbNumRows($sections_ref) > 0)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_UNIQUE_NAME", "B_WARNING_UNIQUE_NAME");
  }
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` " .
			   "WHERE `abbreviation` = '$abbreviation' AND `id` <> $id_section AND `deleted` = 0");
  if (dbNumRows($sections_ref) >0)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_UNIQUE_ABBREVIATION", "B_WARNING_UNIQUE_ABBREVIATION");
  }

  if ($is_complete)
  {
    $admin_irc_channels = dbEscape($_REQUEST['admin_irc_channels']);
    $public_irc_channels = dbEscape($_REQUEST['public_irc_channels']);
    $bot_host = dbEscape($_REQUEST['bot_host']);
    $bot_port = intval($_REQUEST['bot_port']);
    $bot_password = dbEscape($_REQUEST['bot_password']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}sections` SET " .
	     "`name` = '$name', " .
	     "`abbreviation` = '$abbreviation', " .
	     "`admin_irc_channels` = '$admin_irc_channels', " .
	     "`public_irc_channels` = '$public_irc_channels', " .
	     "`bot_host` = '$bot_host', " .
	     "`bot_port` = '$bot_port', " .
	     "`bot_password` = '$bot_password' " .
	     "WHERE `id` = $id_section");
    $content_tpl->parse("H_MESSAGE_SECTION_EDITED", "B_MESSAGE_SECTION_EDITED");
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
