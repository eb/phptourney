<?php

################################################################################
#
# $Id: insert.php,v 1.3 2006/03/23 11:41:25 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_REGISTERED", "H_MESSAGE_REGISTERED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_APPLIED", "H_MESSAGE_APPLIED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_USERNAME", "H_WARNING_UNIQUE_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME", "H_WARNING_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME_INVALID", "H_WARNING_USERNAME_INVALID");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME_TOO_LONG", "H_WARNING_USERNAME_TOO_LONG");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PASSWORD", "H_WARNING_PASSWORD");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PASSWORD_RETYPED", "H_WARNING_PASSWORD_RETYPED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_EMAIL", "H_WARNING_EMAIL");
$content_tpl->set_block("F_CONTENT", "B_WARNING_IRC_CHANNEL", "H_WARNING_IRC_CHANNEL");
$content_tpl->set_block("F_CONTENT", "B_WARNING_COUNTRY", "H_WARNING_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_MAIL_REGISTERED_SUBJECT", "H_MAIL_REGISTERED_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_REGISTERED_BODY", "H_MAIL_REGISTERED_BODY");
$content_tpl->set_block("F_CONTENT", "B_MAIL_APPLIED_SUBJECT", "H_MAIL_APPLIED_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_APPLIED_BODY", "H_MAIL_APPLIED_BODY");

$is_complete = 1;
// users-query
$username = dbEscape($_REQUEST['username']);
$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
		      "WHERE `username` = '$username'");
if ($users_row = dbFetch($users_ref))
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_UNIQUE_USERNAME", "B_WARNING_UNIQUE_USERNAME");
}
if ($_REQUEST['username'] == "")
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_USERNAME", "B_WARNING_USERNAME");
}
if (preg_match("/[<>&;]/", $_REQUEST['username']))
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_USERNAME_INVALID", "B_WARNING_USERNAME_INVALID");
}
if (strlen($_REQUEST['username']) > 15)
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_USERNAME_TOO_LONG", "B_WARNING_USERNAME_TOO_LONG");
}
if ($_REQUEST['password'] == "")
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_PASSWORD", "B_WARNING_PASSWORD");
}
if ($_REQUEST['password'] != $_REQUEST['password_retyped'])
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_PASSWORD_RETYPED", "B_WARNING_PASSWORD_RETYPED");
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
if ($_REQUEST['id_country'] == "")
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_COUNTRY", "B_WARNING_COUNTRY");
}

if ($is_complete)
{
  // notification
  if (!isset($_REQUEST['notify']))
  {
    $_REQUEST['notify'] = 0;
  }

  // encrypt password
  $password = crypt($_REQUEST['password'], createSalt());

  // register an account
  $id_country = intval($_REQUEST['id_country']);
  $email = dbEscape($_REQUEST['email']);
  $irc_channel = dbEscape($_REQUEST['irc_channel']);
  $notify = intval($_REQUEST['notify']);
  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}users` " .
	   "(`username`, `id_country`, `password`, `email`, `irc_channel`, `notify`, `submitted`) " .
	   "VALUES ('$username', $id_country, " .
	   "'$password', '$email', '$irc_channel', $notify, NOW())");

  // sign up
  $signup = false;
  if ($season['status'] == "signups" and $_REQUEST['signup'] == 1)
  {
    // users-query
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
			  "WHERE `username` = '$username'");
    $users_row = dbFetch($users_ref);

    dbQuery("INSERT INTO `{$cfg['db_table_prefix']}season_users` " .
	     "(`ip`, `submitted`, `usertype_player`, `id_season`, `id_user`) " .
	     "VALUES ('{$_SERVER['REMOTE_ADDR']}', NOW(), 1, {$_REQUEST['sid']}, {$users_row['id']})");

    $signup = true;
  }

  // send a mail to the player that signed up
  $to = $_REQUEST['email'];

  if ($signup == false)
  {
    // subject
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->parse("MAIL_REGISTERED_SUBJECT", "B_MAIL_REGISTERED_SUBJECT");
    $subject = $content_tpl->get("MAIL_REGISTERED_SUBJECT");

    // message
    $content_tpl->set_var("I_USERNAME", $_REQUEST['username']);
    $content_tpl->set_var("I_PASSWORD", $_REQUEST['password']);
    $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path']);
    $content_tpl->parse("MAIL_REGISTERED_BODY", "B_MAIL_REGISTERED_BODY");
    $message = $content_tpl->get("MAIL_REGISTERED_BODY");
  }
  else
  {
    // subject
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->parse("MAIL_APPLIED_SUBJECT", "B_MAIL_APPLIED_SUBJECT");
    $subject = $content_tpl->get("MAIL_APPLIED_SUBJECT");

    // message
    $content_tpl->set_var("I_USERNAME", $_REQUEST['username']);
    $content_tpl->set_var("I_PASSWORD", $_REQUEST['password']);
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->set_var("I_SEASON_NAME", $season['name']);
    $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path']);
    $content_tpl->parse("MAIL_APPLIED_BODY", "B_MAIL_APPLIED_BODY");
    $message = $content_tpl->get("MAIL_APPLIED_BODY");
  }

  sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path'], $cfg['mail_bcc_address']);

  if ($signup == false)
  {
    $content_tpl->parse("H_MESSAGE_REGISTERED", "B_MESSAGE_REGISTERED");
  }
  else
  {
    $content_tpl->parse("H_MESSAGE_APPLIED", "B_MESSAGE_APPLIED");
  }

  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
}

if (!$is_complete)
{
  $content_tpl->parse("H_WARNING", "B_WARNING");
  $content_tpl->parse("H_BACK", "B_BACK");
}

?>
