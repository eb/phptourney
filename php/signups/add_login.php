<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_SIGNED_UP", "H_WARNING_SIGNED_UP");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_SIGNUP", "H_VIEW_NO_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_VIEW_LOGIN", "H_VIEW_LOGIN");

if ($season['status'] == "signups")
{
  if ($user['uid'])
  {
    $is_complete = 1;
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `id_user` = {$user['uid']} AND `id_season` = {$season['id']} AND `usertype_player` = 1");
    if (dbNumRows($season_users_ref) == 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_SIGNED_UP", "B_WARNING_SIGNED_UP");
    }

    if ($is_complete)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$user['uid']}");
      $users_row = dbFetch($users_ref);

      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->parse("H_VIEW_LOGIN", "B_VIEW_LOGIN");
    }

    if (!$is_complete)
    {
      $content_tpl->parse("H_WARNING", "B_WARNING");
      $content_tpl->parse("H_BACK", "B_BACK");
    }
  }
  else
  {
    $content_tpl->parse("H_WARNING_LOGIN", "B_WARNING_LOGIN");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_VIEW_NO_SIGNUP", "B_VIEW_NO_SIGNUP");
}

?>
