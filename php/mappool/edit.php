<?php

################################################################################
#
# $Id: edit.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_MAP", "H_EDIT_MAP");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_map = intval($_REQUEST['opt']);
  $maps_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}mappool` WHERE `id` = $id_map AND `deleted` = 0");
  $maps_row = dbFetch($maps_ref);
  $content_tpl->set_var("I_ID_MAP", $_REQUEST['opt']);
  $content_tpl->set_var("I_MAP", $maps_row['map']);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_MAP", "B_EDIT_MAP");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
