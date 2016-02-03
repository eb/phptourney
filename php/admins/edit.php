<?php

################################################################################
#
# $Id: edit.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_HEADADMIN_UNCHECKED", "H_EDIT_HEADADMIN_UNCHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_HEADADMIN_CHECKED", "H_EDIT_HEADADMIN_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_ADMIN", "H_EDIT_ADMIN");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  // users-query
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_ID_USER", $_REQUEST['opt']);
  $content_tpl->set_var("I_USERNAME", $users_row['username']);

  // season_users-query
  $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
			       "WHERE `id_user` = $id_user AND `id_season` = {$_REQUEST['sid']}");
  $season_users_row = dbFetch($season_users_ref);
  if ($season_users_row['usertype_headadmin'])
  {
    $content_tpl->parse("H_EDIT_HEADADMIN_CHECKED", "B_EDIT_HEADADMIN_CHECKED");
  }
  else
  {
    $content_tpl->parse("H_EDIT_HEADADMIN_UNCHECKED", "B_EDIT_HEADADMIN_UNCHECKED");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_ADMIN", "B_EDIT_ADMIN");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
