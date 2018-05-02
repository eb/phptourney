<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_HEADADMIN_UNCHECKED", "H_EDIT_HEADADMIN_UNCHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_HEADADMIN_CHECKED", "H_EDIT_HEADADMIN_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_ADMIN", "H_EDIT_ADMIN");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_ID_USER", $id_user);
  $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));

  $season_users_ref = dbQuery("SELECT * FROM `season_users` " .
			       "WHERE `id_user` = $id_user AND `id_season` = {$season['id']}");
  $season_users_row = dbFetch($season_users_ref);
  if ($season_users_row['usertype_headadmin'])
  {
    $content_tpl->parse("H_EDIT_HEADADMIN_CHECKED", "B_EDIT_HEADADMIN_CHECKED");
  }
  else
  {
    $content_tpl->parse("H_EDIT_HEADADMIN_UNCHECKED", "B_EDIT_HEADADMIN_UNCHECKED");
  }
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_ADMIN", "B_EDIT_ADMIN");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
