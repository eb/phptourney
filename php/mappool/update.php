<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MAP_EDITED", "H_MESSAGE_MAP_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_MAP", "H_WARNING_MAP");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $is_complete = 1;
  if ($_REQUEST['map'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_MAP", "B_WARNING_MAP");
  }

  if ($is_complete)
  {
    $id_map = intval($_REQUEST['opt']);
    $map = dbEscape($_REQUEST['map']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}mappool` SET `map` = '$map' " .
	     "WHERE `id` = $id_map AND `deleted` = 0");
    $content_tpl->parse("H_MESSAGE_MAP_EDITED", "B_MESSAGE_MAP_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }

  if (!$is_complete)
  {
    $id_map = intval($_REQUEST['opt']); // XXX
    $content_tpl->set_var("I_MAP", htmlspecialchars($_REQUEST['map']));
    $content_tpl->set_var("I_ID_MAP", $id_map);
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
