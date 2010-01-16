<?php

################################################################################
#
# $Id: confirm.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PLAYER_CONFIRMED", "H_MESSAGE_PLAYER_CONFIRMED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_HEADADMIN_CONFIRMED", "H_MESSAGE_HEADADMIN_CONFIRMED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING_INVITATION", "H_WARNING_INVITATION");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

if ($user['usertype_player'] or $user['usertype_headadmin'])
{
  if ($season['status'] == "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_RUNNING", "B_WARNING_TOURNEY_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
  else
  {
    if ($user['usertype_headadmin'] and $_REQUEST['opt'] != "")
    {
      // season_users-query
      $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_season` = {$_REQUEST['sid']} " .
				   "AND `usertype_player` = 1 " .
				   "AND `invited` = 1 " .
				   "AND `id_user` = {$_REQUEST['opt']}");
      if ($season_users_row = dbFetch($season_users_ref))
      {
	dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET " .
		 "`rejected` = 0, `invited` = 2 " .
		 "WHERE `id` = {$season_users_row['id']}");
	$content_tpl->parse("H_MESSAGE_HEADADMIN_CONFIRMED", "B_MESSAGE_HEADADMIN_CONFIRMED");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
      else
      {
	$content_tpl->parse("H_WARNING_INVITATION", "B_WARNING_INVITATION");
	$content_tpl->parse("H_WARNING", "B_WARNING");
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
    }
    elseif ($user['usertype_player'])
    {
      // season_users-query
      $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_season` = {$_REQUEST['sid']} " .
				   "AND `usertype_player` = 1 " .
				   "AND `invited` = 1 " .
				   "AND `id_user` = {$user['uid']}");
      if ($season_users_row = dbFetch($season_users_ref))
      {
	dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET " .
		 "`rejected` = 1, `invited` = 2 " .
		 "WHERE `id` = {$season_users_row['id']}");
	$content_tpl->parse("H_MESSAGE_PLAYER_CONFIRMED", "B_MESSAGE_PLAYER_CONFIRMED");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");
      }
      else
      {
	$content_tpl->parse("H_WARNING_INVITATION", "B_WARNING_INVITATION");
	$content_tpl->parse("H_WARNING", "B_WARNING");
      }
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_LOGIN", "B_WARNING_LOGIN");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
