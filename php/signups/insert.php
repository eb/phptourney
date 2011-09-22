<?php

################################################################################
#
# $Id: insert.php,v 1.3 2006/03/23 11:41:25 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_APPLIED", "H_MESSAGE_APPLIED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_APPLIED_WITH_EMAIL", "H_MESSAGE_APPLIED_WITH_EMAIL");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_POLL", "H_WARNING_POLL");
$content_tpl->set_block("F_CONTENT", "B_WARNING_POLL_CHOICE", "H_WARNING_POLL_CHOICE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_REJECTED", "H_WARNING_REJECTED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SIGNED_UP", "H_WARNING_SIGNED_UP");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_SIGNUP", "H_NO_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

if ($season['status'] == "signups")
{
  if ($user['uid'])
  {
    $is_complete = 1;
    // season_users-query
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `id_season` = {$_REQUEST['sid']} " .
				 "AND `ip` = '{$_SERVER['REMOTE_ADDR']}' " .
				 "AND `rejected` = 1");
    if (dbNumRows($season_users_ref) == 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_REJECTED", "B_WARNING_REJECTED");
    }
    // season_users-query
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `id_user` = {$user['uid']} AND `id_season` = {$_REQUEST['sid']} AND `usertype_player` = 1");
    if (dbNumRows($season_users_ref) == 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_SIGNED_UP", "B_WARNING_SIGNED_UP");
    }
    // polls-query
    $polls_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
    if ($_REQUEST['choice'] == "" and dbNumRows($polls_ref) == 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_POLL", "B_WARNING_POLL");
    }
    else
    {
      $polls_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
      if ($polls_row = dbFetch($polls_ref))
      {
	$choices = explode(";", $polls_row['choices']);
	$is_valid_choice = 0;
	foreach($choices as $choice) {
	  if ($_REQUEST['choice'] == $choice)
	  {
	    $is_valid_choice = 1;
	  }
	}
	if (!$is_valid_choice)
	{
	  $is_complete = 0;
	  $content_tpl->parse("H_WARNING_POLL_CHOICE", "B_WARNING_POLL_CHOICE");
	}
      }
    }

    if ($is_complete)
    {
      // users-query
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
			    "WHERE `id` = {$user['uid']}");
      $users_row = dbFetch($users_ref);

      // season_users-query
      $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_user` = {$user['uid']} AND `id_season` = {$_REQUEST['sid']}");
      if (dbNumRows($season_users_ref) == 1)
      {
	dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET " .
		 "`ip` = '{$_SERVER['REMOTE_ADDR']}', `submitted` = NOW(), `usertype_player` = 1 " .
		 "WHERE `id_season` = {$_REQUEST['sid']} AND `id_user` = {$user['uid']} ");
      }
      else
      {
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}season_users` " .
		 "(`ip`, `submitted`, `usertype_player`, `id_season`, `id_user`) " .
		 "VALUES ('{$_SERVER['REMOTE_ADDR']}', NOW(), 1, {$_REQUEST['sid']}, {$users_row['id']})");
      }

      // send a mail to the player that signed up
      if ($cfg['mail_enabled'])
      {
	$to = $users_row['email'];

	// subject
	$content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
	$content_tpl->set_var("I_SEASON_NAME", $season['name']);
	$content_tpl->set_var("I_USERNAME", $users_row['username']);
	$content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
	$subject = $content_tpl->get("MAIL_SUBJECT");

	// message
	$content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "?sec={$section['abbreviation']}");
	$content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
	$message = $content_tpl->get("MAIL_BODY");

	sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path']);

	$content_tpl->parse("H_MESSAGE_APPLIED_WITH_EMAIL", "B_MESSAGE_APPLIED_WITH_EMAIL");
      }
      else
      {
	$content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
	$content_tpl->set_var("I_SEASON_NAME", $season['name']);
	$content_tpl->parse("H_MESSAGE_APPLIED", "B_MESSAGE_APPLIED");
      }
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");

      // polls-query
      $polls_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
      if ($polls_row = dbFetch($polls_ref))
      {
	// insert vote
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}signup_votes` " .
		 "(`id_poll`, `id_user`, `vote`) " .
		 "VALUES ({$polls_row['id']}, {$user['uid']}, '{$_REQUEST['choice']}')");
      }
    }

    if (!$is_complete)
    {
      $content_tpl->parse("H_WARNING", "B_WARNING");
      $content_tpl->parse("H_BACK", "B_BACK");
    }
  }
  else
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_LOGIN", "B_WARNING_LOGIN");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_VIEW_NO_SIGNUP", "B_VIEW_NO_SIGNUP");
}

?>
