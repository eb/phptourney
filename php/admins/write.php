<?php

################################################################################
#
# $Id: write.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN", "H_WARNING_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_WRITE_MAIL", "H_WRITE_MAIL");

if ($user['uid'])
{
  // users-query
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);

  // season_users-query
  $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
      			 "WHERE `id_season` = {$_REQUEST['sid']} AND `id_user` = $id_user");
  $season_users_row = dbFetch($season_users_ref);

  if ($season_users_row['usertype_headadmin'] or $season_users_row['usertype_admin'])
  {
    $content_tpl->set_var("I_ID_USER", $id_user);
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
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
