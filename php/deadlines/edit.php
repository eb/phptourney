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
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_EDIT_Q_ROUND_SELECTED", "H_EDIT_Q_ROUND_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_Q_ROUND_UNSELECTED", "H_EDIT_Q_ROUND_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_WB_ROUND_SELECTED", "H_EDIT_WB_ROUND_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_WB_ROUND_UNSELECTED", "H_EDIT_WB_ROUND_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_LB_ROUND_SELECTED", "H_EDIT_LB_ROUND_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_LB_ROUND_UNSELECTED", "H_EDIT_LB_ROUND_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_GF_ROUND_SELECTED", "H_EDIT_GF_ROUND_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_GF_ROUND_UNSELECTED", "H_EDIT_GF_ROUND_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_DEADLINE", "H_EDIT_DEADLINE");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['single_elimination'] == "")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_SYSTEM", "B_WARNING_TOURNEY_SYSTEM");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    // deadlines-query
    $deadlines_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` WHERE `id` = {$_REQUEST['opt']}");
    $deadlines_row = dbFetch($deadlines_ref);

    if ($season['qualification'] == 1)
    {
      $content_tpl->set_var("I_ROUND", 0);
      if ("q0" == $deadlines_row['round'])
      {
	$content_tpl->parse("H_EDIT_Q_ROUND_SELECTED", "B_EDIT_Q_ROUND_SELECTED", true);
      }
      else
      {
	$content_tpl->parse("H_EDIT_Q_ROUND_SELECTED", "B_EDIT_Q_ROUND_UNSELECTED", true);
      }

      $content_tpl->set_var("I_ROUND", 1);
      if ("q1" == $deadlines_row['round'])
      {
	$content_tpl->parse("H_EDIT_Q_ROUND_SELECTED", "B_EDIT_Q_ROUND_SELECTED", true);
      }
      else
      {
	$content_tpl->parse("H_EDIT_Q_ROUND_SELECTED", "B_EDIT_Q_ROUND_UNSELECTED", true);
      }
    }

    $num_wb_rounds = getNumWBRounds($season);
    for ($i = 0; $i <= $num_wb_rounds; $i++)
    {
      $content_tpl->set_var("I_ROUND", $i);
      if ("wb$i" == $deadlines_row['round'])
      {
	$content_tpl->parse("H_EDIT_WB_ROUND_SELECTED", "B_EDIT_WB_ROUND_SELECTED", true);
      }
      else
      {
	$content_tpl->parse("H_EDIT_WB_ROUND_SELECTED", "B_EDIT_WB_ROUND_UNSELECTED", true);
      }
    }

    $num_lb_rounds = getNumLBRounds($season);
    for ($i = 0; $i <= $num_lb_rounds; $i++)
    {
      $content_tpl->set_var("I_ROUND", $i);
      if ("lb$i" == $deadlines_row['round'])
      {
	$content_tpl->parse("H_EDIT_LB_ROUND_SELECTED", "B_EDIT_LB_ROUND_SELECTED", true);
      }
      else
      {
	$content_tpl->parse("H_EDIT_LB_ROUND_SELECTED", "B_EDIT_LB_ROUND_UNSELECTED", true);
      }
    }

    $content_tpl->set_var("I_ROUND", 0);
    if ("gf0" == $deadlines_row['round'])
    {
      $content_tpl->parse("H_EDIT_GF_ROUND_SELECTED", "B_EDIT_GF_ROUND_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_EDIT_GF_ROUND_SELECTED", "B_EDIT_GF_ROUND_UNSELECTED", true);
    }

    $content_tpl->set_var("I_ROUND", 1);
    if ("gf1" == $deadlines_row['round'])
    {
      $content_tpl->parse("H_EDIT_GF_ROUND_SELECTED", "B_EDIT_GF_ROUND_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_EDIT_GF_ROUND_SELECTED", "B_EDIT_GF_ROUND_UNSELECTED", true);
    }

    $content_tpl->set_var("I_ID_DEADLINE", $_REQUEST['opt']);
    $content_tpl->set_var("I_DEADLINE", $deadlines_row['deadline']);
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_EDIT_DEADLINE", "B_EDIT_DEADLINE");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
