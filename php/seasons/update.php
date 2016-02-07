<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SEASON_EDITED", "H_MESSAGE_SEASON_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SEASON_NAME", "H_WARNING_SEASON_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_SEASON_NAME", "H_WARNING_UNIQUE_SEASON_NAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for root only
if ($user['usertype_root'])
{
  $is_complete = 1;
  if ($_REQUEST['season_name'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SEASON_NAME", "B_WARNING_SEASON_NAME");
  }
  $id_season = intval($_REQUEST['opt']);
  $season_name = dbEscape($_REQUEST['season_name']);
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `name` = '$season_name' AND `id` <> $id_season AND `deleted` = 0");
  if (dbNumRows($seasons_ref) == 1)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_UNIQUE_SEASON_NAME", "B_WARNING_UNIQUE_SEASON_NAME");
  }

  if ($is_complete)
  {
    dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET " .
	     "`name` = '$season_name' " .
	     "WHERE `id` = $id_season");
    $content_tpl->parse("H_MESSAGE_SEASON_EDITED", "B_MESSAGE_SEASON_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
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
