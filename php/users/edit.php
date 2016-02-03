<?php

################################################################################
#
# $Id: edit.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY", "H_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY_SELECTED", "H_COUNTRY_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_NOTIFY_UNCHECKED", "H_NOTIFY_UNCHECKED");
$content_tpl->set_block("F_CONTENT", "B_NOTIFY_CHECKED", "H_NOTIFY_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_PROFILE", "H_EDIT_PROFILE");

// access for the user to edit his own profile
if ((!isset($_REQUEST['opt']) or $_REQUEST['opt'] == "") and $user['uid'])
{
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$user['uid']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_OPT", $_REQUEST['opt']);
  $content_tpl->set_var("I_USERNAME", $users_row['username']);
  $content_tpl->set_var("I_PASSWORD", $users_row['password']);
  $content_tpl->set_var("I_PASSWORD_RETYPED", $users_row['password']);
  $content_tpl->set_var("I_EMAIL", $users_row['email']);
  $content_tpl->set_var("I_IRC_CHANNEL", $users_row['irc_channel']);

  // countries-query
  $countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` " .
			    "WHERE `active` = 1 " .
			    "ORDER BY `name` ASC");
  while ($countries_row = dbFetch($countries_ref))
  {
    $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
    $content_tpl->set_var("I_COUNTRY", $countries_row['name']);
    if ($countries_row['id'] == $users_row['id_country'])
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY", true);
    }
  }
  if ($users_row['notify'] == 1)
  {
    $content_tpl->parse("H_NOTIFY_CHECKED", "B_NOTIFY_CHECKED");
  }
  else
  {
    $content_tpl->parse("H_NOTIFY_UNCHECKED", "B_NOTIFY_UNCHECKED");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_PROFILE", "B_EDIT_PROFILE");
}

// access for the headadmin to edit any profile
elseif ($_REQUEST['opt'] != "" and ($user['usertype_headadmin'] or $user['usertype_root']))
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_OPT", $_REQUEST['opt']);
  $content_tpl->set_var("I_USERNAME", $users_row['username']);
  $content_tpl->set_var("I_EMAIL", $users_row['email']);
  $content_tpl->set_var("I_IRC_CHANNEL", $users_row['irc_channel']);

  $countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` " .
			    "WHERE `active` = 1 " .
			    "ORDER BY `name` ASC");
  while ($countries_row = dbFetch($countries_ref))
  {
    $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
    $content_tpl->set_var("I_COUNTRY", $countries_row['name']);
    if ($countries_row['id'] == $users_row['id_country'])
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY", true);
    }
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_PROFILE", "B_EDIT_PROFILE");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
