<?php

$content_tpl->set_block("F_CONTENT", "B_ADD", "H_ADD");
$content_tpl->set_block("F_CONTENT", "B_NO_ADMINS", "H_NO_ADMINS");
$content_tpl->set_block("F_CONTENT", "B_EMAIL", "H_EMAIL");
$content_tpl->set_block("F_CONTENT", "B_USERTYPE_HEADADMIN", "H_USERTYPE_HEADADMIN");
$content_tpl->set_block("F_CONTENT", "B_USERTYPE_ADMIN", "H_USERTYPE_ADMIN");
$content_tpl->set_block("F_CONTENT", "B_USERTYPE", "H_USERTYPE");
$content_tpl->set_block("F_CONTENT", "B_EDIT_DELETE", "H_EDIT_DELETE");
$content_tpl->set_block("F_CONTENT", "B_VIEW_ADMIN", "H_VIEW_ADMIN");
$content_tpl->set_block("F_CONTENT", "B_VIEW_ADMINS", "H_VIEW_ADMINS");

if ($user['usertype_admin'])
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_ADD", "B_ADD");
}

$season_users_ref = dbQuery("SELECT SU.* " .
			     "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
			     "WHERE SU.`id_season` = {$season['id']} AND (SU.`usertype_admin` = 1 OR SU.`usertype_headadmin` = 1) " .
			     "AND U.`id` = SU.`id_user` " .
			     "ORDER BY U.`username` ASC");
if (dbNumRows($season_users_ref) == 0)
{
  $content_tpl->parse("H_NO_ADMINS", "B_NO_ADMINS");
}
else
{
  $admin_counter = 0;
  while ($season_users_row = dbFetch($season_users_ref))
  {
    $users_ref = dbQuery("SELECT U.*, C.`abbreviation` FROM `{$cfg['db_table_prefix']}users` U " .
			 "LEFT JOIN `{$cfg['db_table_prefix']}countries` C " .
			 "ON U.`id_country` = C.`id` " .
			 "WHERE U.`id` = {$season_users_row['id_user']} " .
			 "ORDER BY `username` ASC");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_ADMIN_COUNTER", ++$admin_counter);
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($users_row['abbreviation']));
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
    $content_tpl->set_var("I_ID_USER", $users_row['id']);
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("H_USERTYPE_HEADADMIN", "");
    $content_tpl->set_var("H_USERTYPE_ADMIN", "");

    if ($season_users_row['usertype_headadmin'])
    {
      $content_tpl->parse("H_USERTYPE_HEADADMIN", "B_USERTYPE_HEADADMIN");
    }
    elseif ($season_users_row['usertype_admin'])
    {
      $content_tpl->parse("H_USERTYPE_ADMIN", "B_USERTYPE_ADMIN");
    }
    $content_tpl->parse("H_USERTYPE", "B_USERTYPE");

    if ($user['usertype_admin'])
    {
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
      $content_tpl->parse("H_EMAIL", "B_EMAIL");
      $content_tpl->parse("H_EDIT_DELETE", "B_EDIT_DELETE");
    }
    $content_tpl->parse("H_VIEW_ADMIN", "B_VIEW_ADMIN", true);
  }
}
$content_tpl->parse("H_VIEW_ADMINS", "B_VIEW_ADMINS");

?>
