<?php

################################################################################
#
# $Id: insert_user.php,v 1.3 2006/03/23 11:41:25 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PLAYER_ADDED", "H_MESSAGE_PLAYER_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PLAYER_INVITED", "H_MESSAGE_PLAYER_INVITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USER", "H_WARNING_USER");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PLAYER", "H_WARNING_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  // season_users-query
  $id_user = intval($_REQUEST['id_user']);
  $season_users_ref =  dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				"WHERE `id_season` = {$_REQUEST['sid']} AND `id_user` = $id_user AND `usertype_player` = 1");
  if (dbNumRows($season_users_ref) > 0)
  {
    $content_tpl->parse("H_WARNING_PLAYER", "B_WARNING_PLAYER");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
  else
  {
    $is_complete = 1;
    if ($_REQUEST['id_user'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_USER", "B_WARNING_USER");
    }

    if ($is_complete)
    {
      // users-query
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
      $users_row = dbFetch($users_ref);

      if (isset($_REQUEST['invite']))
      {
	// send invitation mail
	$invited = 1;
	$player = 0;

	$to = $users_row['email'];

	// subject
	$content_tpl->set_var("I_TOURNEY_NAME", $section['name']);
	$content_tpl->set_var("I_SEASON_NAME", $season['name']);
	$content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
	$subject = $content_tpl->get("MAIL_SUBJECT");

	// message
	$content_tpl->set_var("I_USERNAME", $users_row['username']);
	$content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$season['id']}");
	$content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
	$message = $content_tpl->get("MAIL_BODY");

	sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path'], $cfg['mail_bcc_address']);

	$content_tpl->parse("H_MESSAGE_PLAYER_INVITED", "B_MESSAGE_PLAYER_INVITED");
      }
      else
      {
	$invited = 0;
	$player = 1;
	$content_tpl->parse("H_MESSAGE_PLAYER_ADDED", "B_MESSAGE_PLAYER_ADDED");
      }

      $season_users_ref =  dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				    "WHERE `id_season` = {$_REQUEST['sid']} AND `id_user` = $id_user");
      if ($season_users_row = dbFetch($season_users_ref))
      {
	dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET " .
		 "`usertype_player` = $player, `invited` = $invited " .
		 "WHERE `id_user` = $id_user AND `id_season` = {$_REQUEST['sid']}");
      }
      else
      {
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}season_users` " .
		 "(`id_user`, `id_season`, `submitted`, `usertype_player`, `invited`) " .
		 "VALUES($id_user, {$_REQUEST['sid']}, NOW(), $player, $invited)");
      }

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
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
