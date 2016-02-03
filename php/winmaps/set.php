<?php

################################################################################
#
# $Id: set.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_WINMAPS_SET", "H_MESSAGE_WINMAPS_SET");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_NOT_RUNNING", "H_WARNING_TOURNEY_NOT_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] != "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_NOT_RUNNING", "B_WARNING_TOURNEY_NOT_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  else
  {
    // matches-query
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `id_season` = {$_REQUEST['sid']}");
    while ($matches_row = dbFetch($matches_ref))
    {
      if (isset($_REQUEST[$matches_row['id']]))
      {
        $num_winmaps = intval($_REQUEST[$matches_row['id']]);
	dbQuery("UPDATE `{$cfg['db_table_prefix']}matches` SET " .
		 "`num_winmaps` = $num_winmaps " .
		 "WHERE `id` = {$matches_row['id']}");
      }
    }
    $content_tpl->parse("H_MESSAGE_WINMAPS_SET", "B_MESSAGE_WINMAPS_SET");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
