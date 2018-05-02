<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_WRITE_MAIL", "H_WRITE_MAIL");

if ($user['uid'])
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);

  $season_users_ref = dbQuery("SELECT * FROM `season_users` " .
      			 "WHERE `id_season` = {$season['id']} AND `id_user` = $id_user");
  $season_users_row = dbFetch($season_users_ref);

  if ($season_users_row['usertype_headadmin'] or $season_users_row['usertype_admin'])
  {
    $content_tpl->set_var("I_ID_USER", $id_user);
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
    $content_tpl->parse("H_WRITE_MAIL", "B_WRITE_MAIL");
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
