<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_APPLIED", "H_MESSAGE_APPLIED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
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
    $season_users_ref = dbQuery("SELECT * FROM `season_users` " .
				 "WHERE `id_user` = {$user['uid']} AND `id_season` = {$season['id']} AND `usertype_player` = 1");
    if (dbNumRows($season_users_ref) == 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_SIGNED_UP", "B_WARNING_SIGNED_UP");
    }

    if ($is_complete)
    {
      $users_ref = dbQuery("SELECT * FROM `users` " .
			    "WHERE `id` = {$user['uid']}");
      $users_row = dbFetch($users_ref);

      $season_users_ref = dbQuery("SELECT * FROM `season_users` " .
				   "WHERE `id_user` = {$user['uid']} AND `id_season` = {$season['id']}");
      if (dbNumRows($season_users_ref) == 1)
      {
	dbQuery("UPDATE `season_users` SET " .
		 "`ip` = '{$_SERVER['REMOTE_ADDR']}', `submitted` = NOW(), `usertype_player` = 1 " .
		 "WHERE `id_season` = {$season['id']} AND `id_user` = {$user['uid']} ");
      }
      else
      {
	dbQuery("INSERT INTO `season_users` " .
		 "(`ip`, `submitted`, `usertype_player`, `id_season`, `id_user`) " .
		 "VALUES ('{$_SERVER['REMOTE_ADDR']}', NOW(), 1, {$season['id']}, {$users_row['id']})");
      }

      // Send a mail to the player that signed up
      $to = $users_row['email'];
      
      // Subject
      $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
      $content_tpl->set_var("I_SEASON_NAME", $season['name']);
      $content_tpl->set_var("I_USERNAME", $users_row['username']);
      $content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
      $subject = $content_tpl->get("MAIL_SUBJECT");
      
      // Message
      $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$season['id']}");
      $content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
      $message = $content_tpl->get("MAIL_BODY");
      
      sendMail($to, $subject, $message, $cfg['mail_from_address']);
      
      $content_tpl->parse("H_MESSAGE_APPLIED", "B_MESSAGE_APPLIED");
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
