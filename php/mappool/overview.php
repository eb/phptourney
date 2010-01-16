<?php

################################################################################
#
# $Id: overview.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_MAPS", "H_NO_MAPS");
$content_tpl->set_block("F_CONTENT", "B_MAP", "H_MAP");
$content_tpl->set_block("F_CONTENT", "B_MAPS", "H_MAPS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_MAPS", "H_OVERVIEW_MAPS");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  // maps-query
  $maps_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}mappool` " .
		       "WHERE `id_season` = {$_REQUEST['sid']} AND `deleted` = 0 ORDER BY `map` ASC");
  if (dbNumRows($maps_ref) <= 0)
  {
    $content_tpl->parse("H_NO_MAPS", "B_NO_MAPS");
  }
  else
  {
    $map_counter = 0;
    while ($maps_row = dbFetch($maps_ref))
    {
      $content_tpl->set_var("I_MAP_COUNTER", ++$map_counter);
      $content_tpl->set_var("I_ID_MAP", $maps_row['id']);
      $content_tpl->set_var("I_MAP", $maps_row['map']);
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->parse("H_MAP", "B_MAP", true);
    }
    $content_tpl->parse("H_MAPS", "B_MAPS");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_OVERVIEW_MAPS", "B_OVERVIEW_MAPS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
