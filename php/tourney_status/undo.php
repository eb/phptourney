<?php

################################################################################
#
# $Id: undo.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_BRACKET_UNDONE", "H_MESSAGE_BRACKET_UNDONE");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_CONFIRMED_MATCHES", "H_WARNING_CONFIRMED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_RUNNING", "B_WARNING_TOURNEY_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    // matches-query
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `id_season` = {$season['id']} " .
			    "AND `confirmed` <> '0000-00-00 00:00:00'");
    if (dbNumRows($matches_ref) == 0)
    {
      // delete all matches
      dbQuery("DELETE FROM `{$cfg['db_table_prefix']}matches` WHERE `id_season` = {$season['id']}");

      // unset season-status
      dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `status` = '' WHERE `id` = {$season['id']}");
      $content_tpl->parse("H_MESSAGE_BRACKET_UNDONE", "B_MESSAGE_BRACKET_UNDONE");
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
    }
    else
    {
      $content_tpl->parse("H_WARNING_CONFIRMED_MATCHES", "B_WARNING_CONFIRMED_MATCHES");
      $content_tpl->parse("H_WARNING", "B_WARNING");
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
