<?php

################################################################################
#
# $Id: start.php,v 1.3 2006/03/23 11:41:25 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_TOURNEY_RUNNING", "H_MESSAGE_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BRACKET", "H_WARNING_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_DEADLINE", "H_MAIL_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_MAIL_FIRST_MATCH", "H_MAIL_FIRST_MATCH");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY_ACCEPTED", "H_MAIL_BODY_ACCEPTED");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY_REJECTED", "H_MAIL_BODY_REJECTED");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_RUNNING", "B_WARNING_TOURNEY_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] != "bracket")
  {
    $content_tpl->parse("H_WARNING_BRACKET", "B_WARNING_BRACKET");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    // send a mail to all player that signed up
    if ($cfg['mail_enabled'])
    {
      $season_users_ref = dbQuery("SELECT SU.*, U.`username`, U.`email` " .
				  "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
				  "WHERE SU.`id_season` = {$_REQUEST['sid']} " .
				  "AND SU.`usertype_player` = 1 " .
				  "AND SU.`id_user` = U.`id`");
      while ($season_users_row = dbFetch($season_users_ref))
      {
        // subject
        $content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
        $content_tpl->set_var("I_SEASON_NAME", $season['name']);
        $content_tpl->set_var("I_USERNAME", $season_users_row['username']);
        $content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
        $subject = $content_tpl->get("MAIL_SUBJECT");

	$to = $season_users_row['email'];

	// message
	if ($season_users_row['rejected'] == 0)
	{
	  // matches query
	  $matches_ref1 = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
				  "WHERE `id_season` = {$season['id']} " .
				  "AND `id_player1` = {$season_users_row['id_user']}");
	  $matches_ref2 = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
				  "WHERE `id_season` = {$season['id']} " .
				  "AND `id_player2` = {$season_users_row['id_user']}");
	  if ($matches_row = dbFetch($matches_ref1))
	  {
	    // users query
	    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
				 "WHERE `id` = {$matches_row['id_player2']}");
	  }
	  elseif ($matches_row = dbFetch($matches_ref2))
	  {
	    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
				 "WHERE `id` = {$matches_row['id_player1']}");
	  }
	  $users_row = dbFetch($users_ref);

	  // message accepted
	  $content_tpl->set_var("I_OPPONENT", $users_row['username']);
	  $content_tpl->set_var("I_IRC_CHANNEL", $users_row['irc_channel']);
	  $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
	  $content_tpl->set_var("I_ROUND", $matches_row['round']);
	  $content_tpl->set_var("I_MATCH", $matches_row['match']);

	  $deadlines_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
				   "WHERE `id_season` = {$_REQUEST['sid']} " .
				   "AND `round` = '{$matches_row['bracket']}{$matches_row['round']}'");
	  if ($deadlines_row = dbFetch($deadlines_ref))
	  {
	    $content_tpl->set_var("I_DEADLINE", $deadlines_row['deadline']);
	    $content_tpl->parse("H_MAIL_DEADLINE", "B_MAIL_DEADLINE");
	  }
	  $content_tpl->parse("H_MAIL_FIRST_MATCH", "B_MAIL_FIRST_MATCH");
	  $content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
	  $content_tpl->set_var("I_SEASON_NAME", $season['name']);
	  $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sec={$section['abbreviation']}");
	  $content_tpl->parse("MAIL_BODY_ACCEPTED", "B_MAIL_BODY_ACCEPTED");
	  $message = $content_tpl->get("MAIL_BODY_ACCEPTED");
	}
	else
	{
	  // message rejected
	  $content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
	  $content_tpl->set_var("I_SEASON_NAME", $season['name']);
	  $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sec={$section['abbreviation']}");
	  $content_tpl->parse("MAIL_BODY_REJECTED", "B_MAIL_BODY_REJECTED");
	  $message = $content_tpl->get("MAIL_BODY_REJECTED");
	}

	sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path']);
      }
    }

    // set season-status to running
    dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `status` = 'running' WHERE `id` = {$_REQUEST['sid']}");
    $content_tpl->parse("H_MESSAGE_TOURNEY_RUNNING", "B_MESSAGE_TOURNEY_RUNNING");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
