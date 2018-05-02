<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PASSWORD_ACTIVATED", "H_MESSAGE_PASSWORD_ACTIVATED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME", "H_WARNING_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PASSWORD", "H_WARNING_PASSWORD");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN_FAILED", "H_WARNING_LOGIN_FAILED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");

$is_complete = 1;
if ($_REQUEST['username'] == "")
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_USERNAME", "B_WARNING_USERNAME");
}
if ($_REQUEST['new_password'] == "")
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_PASSWORD", "B_WARNING_PASSWORD");
}
$username = dbEscape($_REQUEST['username']);
$users_ref = dbQuery("SELECT * FROM `users` " .
		      "WHERE `username` = '$username'");
if ($users_row = dbFetch($users_ref))
{
  $new_password = crypt($_REQUEST['new_password'], substr($users_row['new_password'], 0, 2));
  if ($new_password != $users_row['new_password'])
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_LOGIN_FAILED", "B_WARNING_LOGIN_FAILED");
  }
}
else
{
  $is_complete = 0;
  $content_tpl->parse("H_WARNING_LOGIN_FAILED", "B_WARNING_LOGIN_FAILED");
}

if ($is_complete)
{
  $new_password = dbEscape($users_row['new_password']);
  dbQuery("UPDATE `users` SET " .
	   "`password` = '$new_password', " .
	   "`new_password` = '' " .
	   "WHERE `id` = {$users_row['id']}");
  $content_tpl->parse("H_MESSAGE_PASSWORD_ACTIVATED", "B_MESSAGE_PASSWORD_ACTIVATED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
}

if (!$is_complete)
{
  $content_tpl->parse("H_WARNING", "B_WARNING");
  $content_tpl->parse("H_BACK", "B_BACK");
}

?>
