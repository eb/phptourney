<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_ADMIN_REMOVED", "H_MESSAGE_ADMIN_REMOVED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);
  dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `usertype_headadmin` = 0, `usertype_admin` = 0 " .
	   "WHERE `id_user` = $id_user AND `id_season` = {$season['id']}");
  $content_tpl->parse("H_MESSAGE_ADMIN_REMOVED", "B_MESSAGE_ADMIN_REMOVED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
