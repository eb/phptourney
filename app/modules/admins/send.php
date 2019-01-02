<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MESSAGE_SENT", "H_MESSAGE_MESSAGE_SENT");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SUBJECT", "H_WARNING_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_MESSAGE", "H_WARNING_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACk");

if ($user['uid'])
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);

  $users_ref2 = dbQuery("SELECT * FROM `users` WHERE `id` = {$user['uid']}");
  $users_row2 = dbFetch($users_ref2);

  $season_users_ref = dbQuery("SELECT * FROM `season_users` " .
      			 "WHERE `id_season` = {$season['id']} AND `id_user` = $id_user");
  $season_users_row = dbFetch($season_users_ref);

  if ($season_users_row['usertype_headadmin'] or $season_users_row['usertype_admin'])
  {
    $is_complete = 1;
    if ($_REQUEST['subject'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_SUBJECT", "B_WARNING_SUBJECT");
    }
    if ($_REQUEST['message'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_MESSAGE", "B_WARNING_MESSAGE");
    }

    if ($is_complete)
    {
      $to = $users_row['email'];
      $subject = $_REQUEST['subject'];
      $message = $_REQUEST['message'];
      $reply_to_address = $users_row2['username'] . " <" . $users_row2['email'] . ">";

      sendMail($to, $subject, $message, $cfg['mail_from_address']);

      $content_tpl->parse("H_MESSAGE_MESSAGE_SENT", "B_MESSAGE_MESSAGE_SENT");
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
    $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_LOGIN", "B_WARNING_LOGIN");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
