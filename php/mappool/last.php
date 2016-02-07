<?php

################################################################################
#
# $Id: last.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_LAST", "H_MESSAGE_LAST");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LAST", "H_WARNING_LAST");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `deleted` = 0 ORDER BY `submitted` DESC");
  while ($seasons_row = dbFetch($seasons_ref)) // XXX: Ugly
  {
    if ($seasons_row['id'] == $_REQUEST['sid'])
    {
      if ($seasons_row = dbFetch($seasons_ref))
      {
	$maps_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}mappool` WHERE `id_season` = {$seasons_row['id']} AND `deleted` = 0");
	while ($maps_row = dbFetch($maps_ref))
	{
          $map = dbEscape($maps_row['map']);
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}mappool` (`id_season`, `map`) " .
		   "VALUES ({$_REQUEST['sid']}, '$map')");
	}
	$content_tpl->parse("H_MESSAGE_LAST", "B_MESSAGE_LAST");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
      else
      {
	$content_tpl->parse("H_WARNING_LAST", "B_WARNING_LAST");
	$content_tpl->parse("H_WARNING", "B_WARNING");
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
