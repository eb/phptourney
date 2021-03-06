<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_TOURNEY_SYSTEM_CHOSEN", "H_MESSAGE_TOURNEY_SYSTEM_CHOSEN");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BRACKET_CREATED", "H_WARNING_BRACKET_CREATED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SINGLE_ELIMINATION", "H_WARNING_SINGLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WARNING_DOUBLE_ELIMINATION", "H_WARNING_DOUBLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WARNING_WINMAPS", "H_WARNING_WINMAPS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "bracket")
  {
    $content_tpl->parse("H_WARNING_BRACKET_CREATED", "B_WARNING_BRACKET_CREATED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "running")
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
    $is_complete = 1;
    if (!isset($_REQUEST['qualification']) or $_REQUEST['qualification'] == "")
    {
      $_REQUEST['qualification'] = 0;
    }
    if ($_REQUEST['single_elimination'] < $_REQUEST['double_elimination'])
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_SINGLE_ELIMINATION", "B_WARNING_SINGLE_ELIMINATION");
    }
    if ($_REQUEST['double_elimination'] < 4 and $_REQUEST['double_elimination'] != "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_DOUBLE_ELIMINATION", "B_WARNING_DOUBLE_ELIMINATION");
    }
    if ($_REQUEST['winmaps'] < 1)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_WINMAPS", "B_WARNING_WINMAPS");
    }

    if (!$is_complete)
    {
      $content_tpl->parse("H_WARNING", "B_WARNING");
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
    }
    else
    {
      // Delete deadlines
      dbQuery("DELETE FROM `deadlines` WHERE `id_season` = {$season['id']}");

      // Choose tourney-system
      $id_season = intval($_REQUEST['opt']);
      $qualification = intval($_REQUEST['qualification']);
      $single_elimination = intval($_REQUEST['single_elimination']);
      $double_elimination = intval($_REQUEST['double_elimination']);
      $winmaps = intval($_REQUEST['winmaps']);
      dbQuery("UPDATE `seasons` SET `qualification` = $qualification, " .
	       "`single_elimination` = '$single_elimination', " .
	       "`double_elimination` = '$double_elimination', " .
	       "`winmaps` = $winmaps " .
	       "WHERE `id` = $id_season");
      $content_tpl->parse("H_MESSAGE_TOURNEY_SYSTEM_CHOSEN", "B_MESSAGE_TOURNEY_SYSTEM_CHOSEN");
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
