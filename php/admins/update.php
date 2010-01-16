<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_ADMIN_EDITED", "H_MESSAGE_ADMIN_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $is_complete = 1;
  if (!isset($_REQUEST['headadmin']))
  {
    $_REQUEST['headadmin'] = 0;
  }

  if ($is_complete)
  {
    dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET " .
	     "`usertype_admin` = 1, " .
	     "`usertype_headadmin` = {$_REQUEST['headadmin']} " .
	     "WHERE `id_user` = {$_REQUEST['opt']} AND `id_season` = {$_REQUEST['sid']}");

    $content_tpl->parse("H_MESSAGE_ADMIN_EDITED", "B_MESSAGE_ADMIN_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
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

?>
