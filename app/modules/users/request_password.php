<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MAIL_SENT", "H_MESSAGE_MAIL_SENT");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME", "H_WARNING_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

$username = dbEscape($_REQUEST['username']);
$users_ref = dbQuery("SELECT * FROM `users` " .
		      "WHERE `username` = '$username'");
if ($users_row = dbFetch($users_ref))
{
  // Generate password
  $rand_num = mt_rand();
  $new_password = crypt($rand_num, createSalt());
  $new_encrypted_password = crypt($new_password, createSalt());

  dbQuery("UPDATE `users` SET `new_password` = '$new_encrypted_password' WHERE `id` = {$users_row['id']}");

  // Send a mail
  $to = $users_row['email'];

  // Subject
  $content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
  $content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
  $subject = $content_tpl->get("MAIL_SUBJECT");

  // Message
  $content_tpl->set_var("I_USERNAME", $users_row['username']);
  $content_tpl->set_var("I_NEW_PASSWORD", $new_password);
  $content_tpl->set_var("I_ACTIVATION_URL", $cfg['host'] . $cfg['path'] . "index.php?mod=users&act=activation_login");
  $content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
  $message = $content_tpl->get("MAIL_BODY");

  sendMail($to, $subject, $message, $cfg['mail_from_address']);

  $content_tpl->parse("H_MESSAGE_MAIL_SENT", "B_MESSAGE_MAIL_SENT");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
}
else
{
  $content_tpl->parse("H_WARNING_USERNAME", "B_WARNING_USERNAME");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
