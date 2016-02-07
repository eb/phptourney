<?php

################################################################################
#
# $Id: overview.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SIGNUPS_OPEN", "H_MESSAGE_SIGNUPS_OPEN");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SIGNUPS_CLOSED", "H_WARNING_SIGNUPS_CLOSED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_OPEN_SIGNUPS", "H_OPEN_SIGNUPS");
$content_tpl->set_block("F_CONTENT", "B_CLOSE_SIGNUPS", "H_CLOSE_SIGNUPS");
$content_tpl->set_block("F_CONTENT", "B_ADD_PLAYER", "H_ADD_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_ADD_PLAYER", "H_OVERVIEW_ADD_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_INVITE_PLAYER", "H_INVITE_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_INVITE_PLAYER", "H_OVERVIEW_INVITE_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_NO_ACCEPTED_PLAYERS", "H_NO_ACCEPTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_ACCEPTED_PLAYER", "H_ACCEPTED_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_ACCEPTED_PLAYERS", "H_ACCEPTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_ACCEPTED_PLAYERS", "H_OVERVIEW_ACCEPTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_NO_REJECTED_PLAYERS", "H_NO_REJECTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_REJECTED_PLAYER", "H_REJECTED_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_REJECTED_PLAYERS", "H_REJECTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_REJECTED_PLAYERS", "H_OVERVIEW_REJECTED_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_NO_INVITATIONS", "H_NO_INVITATIONS");
$content_tpl->set_block("F_CONTENT", "B_INVITATION", "H_INVITATION");
$content_tpl->set_block("F_CONTENT", "B_INVITATIONS", "H_INVITATIONS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_INVITATIONS", "H_OVERVIEW_INVITATIONS");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "signups")
  {
    $content_tpl->parse("H_MESSAGE_SIGNUPS_OPEN", "B_MESSAGE_SIGNUPS_OPEN");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_CLOSE_SIGNUPS", "B_CLOSE_SIGNUPS");
  }
  else
  {
    $content_tpl->parse("H_WARNING_SIGNUPS_CLOSED", "B_WARNING_SIGNUPS_CLOSED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_OPEN_SIGNUPS", "B_OPEN_SIGNUPS");
  }

  ////////////////////////////////////////////////////////////////////////////////
  // add player
  ////////////////////////////////////////////////////////////////////////////////

  if ($season['status'] == "" or $season['status'] == "signups")
  {
    // users-query
    $users_ref = dbQuery("SELECT * " .
			  "FROM `{$cfg['db_table_prefix']}users` " .
			  "ORDER BY `username` ASC");

    while ($users_row = dbFetch($users_ref))
    {
      // season_users-query
      $season_users_ref = dbQuery("SELECT * " .
				   "FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_season` = {$season['id']} " .
				   "AND `id_user` = {$users_row['id']} " .
				   "AND `usertype_player` = 1");
      if (dbNumRows($season_users_ref) == 0)
      {
	$content_tpl->set_var("I_ID_USER", $users_row['id']);
	$content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
	$content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
	$content_tpl->parse("H_ADD_PLAYER", "B_ADD_PLAYER", true);
      }
    }
    $content_tpl->parse("H_OVERVIEW_ADD_PLAYER", "B_OVERVIEW_ADD_PLAYER");
  }

  ////////////////////////////////////////////////////////////////////////////////
  // invite player
  ////////////////////////////////////////////////////////////////////////////////

  if ($season['status'] == "" or $season['status'] == "signups")
  {
    // users-query
    $users_ref = dbQuery("SELECT * " .
			  "FROM `{$cfg['db_table_prefix']}users` " .
			  "ORDER BY `username` ASC");

    while ($users_row = dbFetch($users_ref))
    {
      // season_users-query
      $season_users_ref = dbQuery("SELECT * " .
				   "FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_season` = {$season['id']} " .
				   "AND `id_user` = {$users_row['id']} " .
				   "AND (`usertype_player` = 1 OR `invited` = 1)");
      if (dbNumRows($season_users_ref) == 0)
      {
	$content_tpl->set_var("I_ID_USER", $users_row['id']);
	$content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
	$content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
	$content_tpl->parse("H_INVITE_PLAYER", "B_INVITE_PLAYER", true);
      }
    }
    $content_tpl->parse("H_OVERVIEW_INVITE_PLAYER", "B_OVERVIEW_INVITE_PLAYER");
  }

  ////////////////////////////////////////////////////////////////////////////////
  // accepted players
  ////////////////////////////////////////////////////////////////////////////////

  // users-query
  $users_ref = dbQuery("SELECT U.* " .
			"FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			"WHERE SU.`id_season` = {$season['id']} " .
			"AND SU.`usertype_player` = 1 " .
			"AND SU.`rejected` = 0 " .
			"AND SU.`id_user` = U.`id` " .
			"ORDER BY SU.`submitted` ASC");
  $player_counter = 0;
  if (dbNumRows($users_ref) <= 0)
  {
    $content_tpl->parse("H_NO_ACCEPTED_PLAYERS", "B_NO_ACCEPTED_PLAYERS");
  }
  else
  {
    while ($users_row = dbFetch($users_ref))
    {
      $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
      $content_tpl->parse("H_ACCEPTED_PLAYER", "B_ACCEPTED_PLAYER", true);
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_ACCEPTED_PLAYERS", "B_ACCEPTED_PLAYERS");
  }
  $content_tpl->parse("H_OVERVIEW_ACCEPTED_PLAYERS", "B_OVERVIEW_ACCEPTED_PLAYERS");

  ////////////////////////////////////////////////////////////////////////////////
  // rejected players
  ////////////////////////////////////////////////////////////////////////////////

  // users-query
  $users_ref = dbQuery("SELECT U.* " .
			"FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			"WHERE SU.`id_season` = {$season['id']} " .
			"AND SU.`usertype_player` = 1 " .
			"AND SU.`rejected` = 1 " .
			"AND SU.`id_user` = U.`id` " .
			"ORDER BY SU.`submitted` ASC");
  if (dbNumRows($users_ref) <= 0)
  {
    $content_tpl->parse("H_NO_REJECTED_PLAYERS", "B_NO_REJECTED_PLAYERS");
  }
  else
  {
    while ($users_row = dbFetch($users_ref))
    {
      $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
      $content_tpl->parse("H_REJECTED_PLAYER", "B_REJECTED_PLAYER", true);
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_REJECTED_PLAYERS", "B_REJECTED_PLAYERS");
  }
  $content_tpl->parse("H_OVERVIEW_REJECTED_PLAYERS", "B_OVERVIEW_REJECTED_PLAYERS");

  ////////////////////////////////////////////////////////////////////////////////
  // invitations
  ////////////////////////////////////////////////////////////////////////////////

  // users-query
  $users_ref = dbQuery("SELECT U.* " .
			"FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			"WHERE SU.`id_season` = {$season['id']} " .
			"AND SU.`usertype_player` = 0 " .
			"AND SU.`invited` = 1 " .
			"AND SU.`id_user` = U.`id` " .
			"ORDER BY SU.`submitted` ASC");
  if (dbNumRows($users_ref) <= 0)
  {
    $content_tpl->parse("H_NO_INVITATIONS", "B_NO_INVITATIONS");
  }
  else
  {
    while ($users_row = dbFetch($users_ref))
    {
      $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
      $content_tpl->parse("H_INVITATION", "B_INVITATION", true);
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_INVITATIONS", "B_INVITATIONS");
  }
  $content_tpl->parse("H_OVERVIEW_INVITATIONS", "B_OVERVIEW_INVITATIONS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
