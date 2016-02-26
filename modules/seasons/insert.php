<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SEASON_ADDED", "H_MESSAGE_SEASON_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SEASON_NAME", "H_WARNING_SEASON_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_SEASON_NAME", "H_WARNING_UNIQUE_SEASON_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_HEADADMIN", "H_WARNING_HEADADMIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

// Access for root only
if ($user['usertype_root'])
{
  $is_complete = 1;
  if ($_REQUEST['season_name'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SEASON_NAME", "B_WARNING_SEASON_NAME");
  }
  $season_name = dbEscape($_REQUEST['season_name']);
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `name` = '$season_name' AND `deleted` = 0");
  if (dbNumRows($seasons_ref) == 1)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_UNIQUE_SEASON_NAME", "B_WARNING_UNIQUE_SEASON_NAME");
  }
  if ($_REQUEST['id_user'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_HEADADMIN", "B_WARNING_HEADADMIN");
  }

  if ($is_complete)
  {
    dbQuery("INSERT INTO `{$cfg['db_table_prefix']}seasons` (`submitted`, `name`, `status`) " .
	    "VALUES (NOW(), '$season_name', NULL)");

    $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			    "WHERE `name` = '$season_name' AND `deleted` = 0");
    $seasons_row = dbFetch($seasons_ref);

    $id_user = intval($_REQUEST['id_user']);
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
    $users_row = dbFetch($users_ref);

    dbQuery("INSERT INTO `{$cfg['db_table_prefix']}season_users` " .
	     "(`submitted`, `usertype_headadmin`, `usertype_admin`, `id_season`, `id_user`) " .
	     "VALUES (NOW(), 1, 1, {$seasons_row['id']}, {$users_row['id']})");

    // Send a mail to the new headadmin
    $to = $users_row['email'];

    // Subject
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->set_var("I_SEASON_NAME", $seasons_row['name']);
    $content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
    $subject = $content_tpl->get("MAIL_SUBJECT");

    // Message
    $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
    $content_tpl->set_var("I_SEASON_NAME", $seasons_row['name']);
    $content_tpl->set_var("I_USERNAME", $users_row['username']);
    $content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$seasons_row['id']}");
    $content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
    $message = $content_tpl->get("MAIL_BODY");

    sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path'], $cfg['mail_bcc_address']);

    $content_tpl->parse("H_MESSAGE_SEASON_ADDED", "B_MESSAGE_SEASON_ADDED");
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
