<?php

################################################################################
#
# $Id: overview.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_RESET_SEEDINGS", "H_RESET_SEEDINGS");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYERS", "H_NO_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_NEW_SEEDGROUP", "H_NEW_SEEDGROUP");
$content_tpl->set_block("F_CONTENT", "B_PLAYER", "H_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS", "H_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_SEEDINGS", "H_OVERVIEW_SEEDINGS");

// access for headadmins [management] / access for admins [viewing]
if ($user['usertype_admin'])
{
  if ($season['status'] == "" or $season['status'] == "signups")
  {
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_RESET_SEEDINGS", "B_RESET_SEEDINGS");
  }

  $player_counter = 0;
  // season_users-query [seeded players]
  $season_users_ref = dbQuery("SELECT SU.*, U.`username`, U.`email` " .
			       "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			       "WHERE SU.`id_season` = {$_REQUEST['sid']} " .
			       "AND SU.`usertype_player` = 1 " .
			       "AND SU.`rejected` = 0 " .
			       "AND SU.`seedgroup` > 0 " .
			       "AND SU.`id_user` = U.`id` " .
			       "ORDER BY SU.`seedgroup` ASC, SU.`submitted` ASC");
  while ($season_users_row = dbFetch($season_users_ref))
  {
    $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
    $content_tpl->set_var("I_ID_USER", $season_users_row['id_user']);
    $content_tpl->set_var("I_USERNAME", $season_users_row['username']);
    $content_tpl->set_var("I_EMAIL", $season_users_row['email']);
    $content_tpl->set_var("I_SEEDGROUP", $season_users_row['seedgroup']);
    if ($user['usertype_headadmin'] and $_REQUEST['opt'] != "view")
    {
      $content_tpl->parse("H_NEW_SEEDGROUP", "B_NEW_SEEDGROUP");
    }
    $content_tpl->parse("H_PLAYER", "B_PLAYER", true);
  }

  // season_users-query [unseeded players]
  $season_users_ref = dbQuery("SELECT SU.*, U.`username`, U.`email` " .
			       "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			       "WHERE SU.`id_season` = {$_REQUEST['sid']} " .
			       "AND SU.`usertype_player` = 1 " .
			       "AND SU.`rejected` = 0 " .
			       "AND SU.`seedgroup` = 0 " .
			       "AND SU.`id_user` = U.`id` " .
			       "ORDER BY SU.`submitted` ASC");
  while ($season_users_row = dbFetch($season_users_ref))
  {
    $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
    $content_tpl->set_var("I_ID_USER", $season_users_row['id_user']);
    $content_tpl->set_var("I_USERNAME", $season_users_row['username']);
    $content_tpl->set_var("I_EMAIL", $season_users_row['email']);
    $content_tpl->set_var("I_SEEDGROUP", $season_users_row['seedgroup']);
    if ($user['usertype_headadmin'] and $_REQUEST['opt'] != "view")
    {
      $content_tpl->parse("H_NEW_SEEDGROUP", "B_NEW_SEEDGROUP");
    }
    $content_tpl->parse("H_PLAYER", "B_PLAYER", true);
  }
 
  if ($player_counter == 0)
  {
    $content_tpl->parse("H_NO_PLAYERS", "B_NO_PLAYERS");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_PLAYERS", "B_PLAYERS");
  $content_tpl->parse("H_OVERVIEW_SEEDINGS", "B_OVERVIEW_SEEDINGS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
