<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PROFILE_EDITED", "H_MESSAGE_PROFILE_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_EMAIL", "H_WARNING_EMAIL");
$content_tpl->set_block("F_CONTENT", "B_WARNING_IRC_CHANNEL", "H_WARNING_IRC_CHANNEL");
$content_tpl->set_block("F_CONTENT", "B_WARNING_COUNTRY", "H_WARNING_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY", "H_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY_SELECTED", "H_COUNTRY_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");

// access for the user
if (!$_REQUEST['opt'] and $user['uid'])
{
  $is_complete = 1;
  if (!preg_match("/.+\@.+\..+/", $_REQUEST['email']))
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_EMAIL", "B_WARNING_EMAIL");
  }
  if ($_REQUEST['irc_channel'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_IRC_CHANNEL", "B_WARNING_IRC_CHANNEL");
  }
  if ($_REQUEST['id_country'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_COUNTRY", "B_WARNING_COUNTRY");
  }
  if (!isset($_REQUEST['notify']))
  {
    $_REQUEST['notify'] = 0;
  }

  if ($is_complete)
  {
    $id_country = intval($_REQUEST['id_country']);
    $email = dbEscape($_REQUEST['email']);
    $irc_channel = dbEscape($_REQUEST['irc_channel']);
    $notify = intval($_REQUEST['notify']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}users` SET " .
	     "`id_country` = $id_country, " .
	     "`email` = '$email', " .
	     "`irc_channel` = '$irc_channel', " .
	     "`notify` = $notify " .
	     "WHERE `id` = {$user['uid']}");
    $content_tpl->parse("H_MESSAGE_PROFILE_EDITED", "B_MESSAGE_PROFILE_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }

  if (!$is_complete)
  {
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
}

// access for headadmins
elseif ($_REQUEST['opt'] != "" and ($user['usertype_headadmin'] or $user['usertype_root']))
{
  $is_complete = 1;
  if ($_REQUEST['id_country'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_COUNTRY", "B_WARNING_COUNTRY");
  }
  if (!preg_match("/.+\@.+\..+/", $_REQUEST['email']))
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_EMAIL", "B_WARNING_EMAIL");
  }
  if ($_REQUEST['irc_channel'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_IRC_CHANNEL", "B_WARNING_IRC_CHANNEL");
  }
  if (!isset($_REQUEST['notify']))
  {
    $_REQUEST['notify'] = 0;
  }

  if ($is_complete)
  {
    $id_country = intval($_REQUEST['id_country']);
    $email = dbEscape($_REQUEST['email']);
    $irc_channel = dbEscape($_REQUEST['irc_channel']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}users` SET " .
	     "`id_country` = $id_country, " .
	     "`email` = '$email', " .
	     "`irc_channel` = '$irc_channel', " .
	     "`notify` = 1 " .
	     "WHERE `id` = $id_user");
    $content_tpl->parse("H_MESSAGE_PROFILE_EDITED", "B_MESSAGE_PROFILE_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
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
