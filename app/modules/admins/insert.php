<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_ADMIN_ADDED", "H_MESSAGE_ADMIN_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USER", "H_WARNING_USER");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $is_complete = 1;
  if ($_REQUEST['id_user'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_USER", "B_WARNING_USER");
  }

  if ($is_complete)
  {
    if (!isset($_REQUEST['headadmin']))
    {
      $_REQUEST['headadmin'] = 0;
    }

    $id_user = intval($_REQUEST['id_user']);
    $season_users_ref =  dbQuery("SELECT * FROM `season_users` " .
				  "WHERE `id_season` = {$season['id']} AND `id_user` = $id_user");
    $headadmin = intval($_REQUEST['headadmin']);
    if ($season_users_row = dbFetch($season_users_ref))
    {
      dbQuery("UPDATE `season_users` SET " .
	       "`usertype_admin` = 1, `usertype_headadmin` = $headadmin " .
	       "WHERE `id_user` = $id_user AND `id_season` = {$season['id']}");
    }
    else
    {
      dbQuery("INSERT INTO `season_users` " .
	       "(`id_user`, `id_season`, `submitted`, `usertype_headadmin`, `usertype_admin`) " .
	       "VALUES($id_user, {$season['id']}, NOW(), $headadmin, 1)");
    }

    // Send a mail to the new admin
    $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = $id_user");
    $users_row = dbFetch($users_ref);
    $to = $users_row['email'];

    // Subject
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->set_var("I_SEASON_NAME", $season['name']);
    $content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
    $subject = $content_tpl->get("MAIL_SUBJECT");

    // Message
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->set_var("I_SEASON_NAME", $season['name']);
    $content_tpl->set_var("I_USERNAME", $users_row['username']);
    $content_tpl->set_var("I_PASSWORD", $users_row['password']);
    $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$season['id']}");
    $content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
    $message = $content_tpl->get("MAIL_BODY");

    sendMail($to, $subject, $message, $cfg['mail_from_address']);

    $content_tpl->parse("H_MESSAGE_ADMIN_ADDED", "B_MESSAGE_ADMIN_ADDED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
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
