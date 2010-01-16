<?php

################################################################################
#
# $Id: create.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_DEADLINES_SETUP", "H_MESSAGE_DEADLINES_SETUP");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_STARTDATE", "H_WARNING_STARTDATE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_WB_ROUND_LENGTH", "H_WARNING_WB_ROUND_LENGTH");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LB_ROUND_LENGTH", "H_WARNING_LB_ROUND_LENGTH");
$content_tpl->set_block("F_CONTENT", "B_WARNING_STARTDATE_VALID", "H_WARNING_STARTDATE_VALID");
$content_tpl->set_block("F_CONTENT", "B_WARNING_WB_ROUND_LENGTH_VALID", "H_WARNING_WB_ROUND_LENGTH_VALID");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LB_ROUND_LENGTH_VALID", "H_WARNING_LB_ROUND_LENGTH_VALID");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LB_ROUND_LENGTH_SMALLER", "H_WARNING_LB_ROUND_LENGTH_SMALLER");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['single_elimination'] == "")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_SYSTEM", "B_WARNING_TOURNEY_SYSTEM");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  else
  {
    $is_complete = 1;
    if ($_REQUEST['startdate'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_STARTDATE", "B_WARNING_STARTDATE");
    }
    if ($_REQUEST['wb_round_length'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_WB_ROUND_LENGTH", "B_WARNING_WB_ROUND_LENGTH");
    }
    if ($season['double_elimination'] != "" and $_REQUEST['lb_round_length'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_LB_ROUND_LENGTH", "B_WARNING_LB_ROUND_LENGTH");
    }
    if (!preg_match("/\d\d\d\d-\d\d-\d\d/", $_REQUEST['startdate']))
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_STARTDATE_VALID", "B_WARNING_STARTDATE_VALID");
    }
    if (!preg_match("/\d+/", $_REQUEST['wb_round_length']))
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_WB_ROUND_LENGTH_VALID", "B_WARNING_WB_ROUND_LENGTH_VALID");
    }
    if ($season['double_elimination'] != "" and !preg_match("/\d+/", $_REQUEST['lb_round_length']))
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_LB_ROUND_LENGTH_VALID", "B_WARNING_LB_ROUND_LENGTH_VALID");
    }
    if ($_REQUEST['lb_round_length'] >= $_REQUEST['wb_round_length'])
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_LB_ROUND_LENGTH_SMALLER", "B_WARNING_LB_ROUND_LENGTH_SMALLER");
    }

    if ($is_complete)
    {
      dbQuery("DELETE FROM `{$cfg['db_table_prefix']}deadlines` WHERE `id_season` = {$_REQUEST['sid']}");

      $deadline = $_REQUEST['startdate'];

      // q
      if ($season['qualification'])
      {
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('q0', '$deadline', {$_REQUEST['sid']})");
	$deadline = addDaysToDate($deadline, $_REQUEST['wb_round_length']);
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('q1', '$deadline', {$_REQUEST['sid']})");
      }

      // wb
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
	       "VALUES ('wb0', '$deadline', {$_REQUEST['sid']})");

      for ($i = 1; $i <= getNumWBRounds($season); $i++)
      {
	$deadline = addDaysToDate($deadline, $_REQUEST['wb_round_length']);
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('wb$i', '$deadline', {$_REQUEST['sid']})");
	if ($i == getNumWBRounds($season) - getNumLBRounds($season) / 2)
	{
	  $lb_startdate = $deadline;
	}
      }

      // lb
      if ($season['double_elimination'] != "")
      {
	$deadline = $lb_startdate;
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('lb0', '$deadline', {$_REQUEST['sid']})");

	for ($i = 1; $i <= getNumLBRounds($season); $i++)
	{
	  if ($i == 1)
	  {
	    $deadline = addDaysToDate($deadline, $_REQUEST['wb_round_length']);
	  }
	  elseif (1 & $i)
	  {
	    $deadline = addDaysToDate($deadline, $_REQUEST['wb_round_length'] - $_REQUEST['lb_round_length']);
	  }
	  else
	  {
	    $deadline = addDaysToDate($deadline, $_REQUEST['lb_round_length']);
	  }
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		   "VALUES ('lb$i', '$deadline', {$_REQUEST['sid']})");
	}
	// gf
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('gf0', '$deadline', {$_REQUEST['sid']})");
	$deadline = addDaysToDate($deadline, $_REQUEST['wb_round_length'] - $_REQUEST['lb_round_length']);
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}deadlines` (`round`, `deadline`, `id_season`) " .
		 "VALUES ('gf1', '$deadline', {$_REQUEST['sid']})");
      }
      $content_tpl->parse("H_MESSAGE_DEADLINES_SETUP", "B_MESSAGE_DEADLINES_SETUP");
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
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

function addDaysToDate($date, $days) {
  preg_match("/(\d\d\d\d)-(\d\d)-(\d\d)/", $date, $matches);
  $year = $matches[1];
  $month = $matches[2];
  $day = $matches[3] + $days;
  $timestamp = mktime(0, 0, 0, $month, $day, $year);
  return(date("Y-m-d", $timestamp));
}

?>
