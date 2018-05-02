<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_EDIT", "H_WARNING_EDIT");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_OPTION_WO1_SELECTED", "H_OPTION_WO1_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_WO1_UNSELECTED", "H_OPTION_WO1_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_WO2_SELECTED", "H_OPTION_WO2_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_WO2_UNSELECTED", "H_OPTION_WO2_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_BOTH_OUT_SELECTED", "H_OPTION_BOTH_OUT_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_BOTH_OUT_UNSELECTED", "H_OPTION_BOTH_OUT_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_BYE_SELECTED", "H_OPTION_BYE_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_BYE_UNSELECTED", "H_OPTION_BYE_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_NOT_PLAYED", "H_NOT_PLAYED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_MAP_SELECTED", "H_OPTION_MAP_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_MAP_UNSELECTED", "H_OPTION_MAP_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_SCREENSHOT", "H_SCREENSHOT");
$content_tpl->set_block("F_CONTENT", "B_MAP", "H_MAP");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_SUBMIT_TIMESTAMP", "H_SUBMIT_TIMESTAMP");
$content_tpl->set_block("F_CONTENT", "B_CONFIRM_TIMESTAMP", "H_CONFIRM_TIMESTAMP");
$content_tpl->set_block("F_CONTENT", "B_EDIT_REPORT", "H_EDIT_REPORT");

if ($user['usertype_admin'])
{
  $id_match = intval($_REQUEST['opt']);
  $matches_ref = dbQuery("SELECT * FROM `matches` WHERE `id` = $id_match");
  $matches_row = dbFetch($matches_ref);
  if (isLastMatch($matches_row))
  {
    $content_tpl->set_var("I_OPT", $id_match);
    $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);

    // Players
    if ($matches_row['id_player1'] != 0)
    {
      $users_ref = dbQuery("SELECT * FROM `users` " .
			   "WHERE `id` = {$matches_row['id_player1']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
    }
    else
    {
      $content_tpl->set_var("I_PLAYER1", "-");
    }
    if ($matches_row['id_player2'] != 0)
    {
      $users_ref = dbQuery("SELECT * FROM `users` " .
			   "WHERE `id` = {$matches_row['id_player2']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
    }
    else
    {
      $content_tpl->set_var("I_PLAYER2", "-");
    }

    // Not played
    if ($matches_row['wo'] == $matches_row['id_player1'])
    {
      $content_tpl->parse("H_OPTION_WO1_SELECTED", "B_OPTION_WO1_SELECTED");
    }
    else
    {
      $content_tpl->parse("H_OPTION_WO1_SELECTED", "B_OPTION_WO1_UNSELECTED");
    }
    if ($matches_row['wo'] == $matches_row['id_player2'])
    {
      $content_tpl->parse("H_OPTION_WO2_SELECTED", "B_OPTION_WO2_SELECTED");
    }
    else
    {
      $content_tpl->parse("H_OPTION_WO2_SELECTED", "B_OPTION_WO2_UNSELECTED");
    }
    if ($matches_row['out'] == 1)
    {
      $content_tpl->parse("H_OPTION_BOTH_OUT_SELECTED", "B_OPTION_BOTH_OUT_SELECTED");
    }
    else
    {
      $content_tpl->parse("H_OPTION_BOTH_OUT_SELECTED", "H_OPTION_BOTH_OUT_UNSELECTED");
    }
    if ($matches_row['bye'] == 1)
    {
      $content_tpl->parse("H_OPTION_BYE_SELECTED", "B_OPTION_BYE_SELECTED");
    }
    else
    {
      $content_tpl->parse("H_OPTION_BYE_SELECTED", "B_OPTION_BYE_UNSELECTED");
    }
    $content_tpl->set_var("I_COMMENT_ADMIN", htmlspecialchars($matches_row['comment_admin']));
    $content_tpl->parse("H_NOT_PLAYED", "B_NOT_PLAYED");

    // Maps
    for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
    {
      $content_tpl->set_var("I_NUM_MAP", $i);
      $content_tpl->set_var("H_SCREENSHOT", "");

      $maps_ref = dbQuery("SELECT * FROM `maps` " .
			  "WHERE `id_match` = {$matches_row['id']} AND `num_map` = $i");
      if ($maps_row = dbFetch($maps_ref))
      {
	// Map
	$content_tpl->set_var("H_OPTION_MAP_SELECTED", "");
	$mappool_ref = dbQuery("SELECT * FROM `mappool` " .
			       "WHERE `id_season` = {$season['id']} AND `deleted` = 0");
	while ($mappool_row = dbFetch($mappool_ref))
	{
	  $content_tpl->set_var("I_ID_MAP", $mappool_row['id']);
	  $content_tpl->set_var("I_MAP", htmlspecialchars($mappool_row['map']));
	  if ($maps_row['id_map'] == $mappool_row['id'])
	  {
	    $content_tpl->parse("H_OPTION_MAP_SELECTED", "B_OPTION_MAP_SELECTED", true);
	  }
	  else
	  {
	    $content_tpl->parse("H_OPTION_MAP_SELECTED", "B_OPTION_MAP_UNSELECTED", true);
	  }
	}

	// Score
	if ($maps_row['score_p1'] == 0 and $maps_row['score_p2'] == 0)
	{
	  $maps_row['score_p1'] = "";
	  $maps_row['score_p2'] = "";
	}
	$content_tpl->set_var("I_SCORE_P1", $maps_row['score_p1']);
	$content_tpl->set_var("I_SCORE_P2", $maps_row['score_p2']);

	// Screenshot
	$sshot_dir = "data/screenshots/{$season['id']}/";
	$sshot = $sshot_dir . "{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$i}.jpg";
	$sshot_thumb = $sshot_dir . "{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$i}_thumb.jpg";
	if (file_exists($sshot))
	{
	  $content_tpl->set_var("I_SCREENSHOT", htmlspecialchars($sshot));
	  $content_tpl->set_var("I_SCREENSHOT_THUMB", htmlspecialchars($sshot_thumb));
	  $content_tpl->parse("H_SCREENSHOT", "B_SCREENSHOT");
	}

	// Comments
	$content_tpl->set_var("I_COMMENT_ADMIN", htmlspecialchars($maps_row['comment_admin']));
	$content_tpl->set_var("I_COMMENT_P1", htmlspecialchars($maps_row['comment_p1']));
	$content_tpl->set_var("I_COMMENT_P2", htmlspecialchars($maps_row['comment_p2']));
      }
      else
      {
	// Map
	$content_tpl->set_var("H_OPTION_MAP_SELECTED", "");
	$mappool_ref = dbQuery("SELECT * FROM `mappool` " .
			       "WHERE `id_season` = {$season['id']} AND `deleted` = 0");
	while ($mappool_row = dbFetch($mappool_ref))
	{
	  $content_tpl->set_var("I_ID_MAP", $mappool_row['id']);
	  $content_tpl->set_var("I_MAP", htmlspecialchars($mappool_row['map']));
	  $content_tpl->parse("H_OPTION_MAP_SELECTED", "B_OPTION_MAP_UNSELECTED", true);
	}

	// Score
	$content_tpl->set_var("I_SCORE_P1", "");
	$content_tpl->set_var("I_SCORE_P2", "");

	// Comments
	$content_tpl->set_var("I_COMMENT_ADMIN", "");
	$content_tpl->set_var("I_COMMENT_P1", "");
	$content_tpl->set_var("I_COMMENT_P2", "");
      }
      $content_tpl->parse("H_MAP", "B_MAP", true);
    }
    $content_tpl->parse("H_MATCH", "B_MATCH");

    // Matchkey
    $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);

    // Submitted
    if ($matches_row['submitted'] != "0000-00-00 00:00:00" and $matches_row['submitter'] != 0)
    {
      $content_tpl->set_var("I_SUBMITTED", htmlspecialchars($matches_row['submitted']));
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['submitter']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_SUBMITTER", htmlspecialchars($users_row['username']));
      $content_tpl->parse("H_SUBMIT_TIMESTAMP", "B_SUBMIT_TIMESTAMP");
    }

    // Confirmed
    if ($matches_row['confirmed'] != "0000-00-00 00:00:00" and $matches_row['confirmer'] != 0)
    {
      $content_tpl->set_var("I_CONFIRMED", htmlspecialchars($matches_row['confirmed']));
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['confirmer']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_CONFIRMER", htmlspecialchars($users_row['username']));
      $content_tpl->parse("H_CONFIRM_TIMESTAMP", "B_CONFIRM_TIMESTAMP");
    }

    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_EDIT_REPORT", "B_EDIT_REPORT");
  }
  else
  {
    $content_tpl->parse("H_WARNING_EDIT", "B_WARNING_EDIT");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
