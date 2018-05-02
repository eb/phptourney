<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_DEADLINE_EDITED", "H_MESSAGE_DEADLINE_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_UNIQUE_ROUND", "H_WARNING_UNIQUE_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WARNING_ROUND", "H_WARNING_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WARNING_DEADLINE", "H_WARNING_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
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
    if ($_REQUEST['round'] == "")
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_ROUND", "B_WARNING_ROUND");
    }
    if (!preg_match("/\d\d\d\d-\d\d-\d\d/", $_REQUEST['deadline']))
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_DEADLINE", "B_WARNING_DEADLINE");
    }

    if ($is_complete)
    {
      $id_deadline = intval($_REQUEST['opt']);
      $round = dbEscape($_REQUEST['round']);
      $deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
				"WHERE `id_season` = {$season['id']} AND `round` = '$round' AND `id` <> $id_deadline");
      if (dbNumRows($deadlines_ref) == 0)
      {
        $deadline = dbEscape($_REQUEST['deadline']);
	dbQuery("UPDATE `deadlines` SET `round` = '$round', `deadline` = '$deadline' " .
		 "WHERE `id` = $id_deadline");
	$content_tpl->parse("H_MESSAGE_DEADLINE_EDITED", "B_MESSAGE_DEADLINE_EDITED");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");
	$content_tpl->set_var("I_ID_SEASON", $season['id']);
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
      else
      {
	$is_complete = 0;
	$content_tpl->parse("H_WARNING_UNIQUE_ROUND", "B_WARNING_UNIQUE_ROUND");
	$content_tpl->parse("H_WARNING", "B_WARNING");
      }
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

?>
