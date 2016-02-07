<?php

################################################################################
#
# $Id: add.php,v 1.3 2006/05/01 14:55:10 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PREV_MATCHES", "H_WARNING_PREV_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NOT_PLAYED", "H_NOT_PLAYED");
$content_tpl->set_block("F_CONTENT", "B_OPTION_MAP", "H_OPTION_MAP");
$content_tpl->set_block("F_CONTENT", "B_MAP", "H_MAP");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_ADD_REPORT", "H_ADD_REPORT");
$content_tpl->set_block("F_CONTENT", "B_SCREENSHOT", "H_SCREENSHOT");
$content_tpl->set_block("F_CONTENT", "B_COMMENT", "H_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_ADD_COMMENT", "H_ADD_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_NO_COMMENT", "H_NO_COMMENT");

// matches-query
$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = $id_match");
$matches_row = dbFetch($matches_ref);

// access for admins
// access for players when it is their own match
if ($user['usertype_admin'] or
    $user['uid'] == $matches_row['id_player1'] or $user['uid'] == $matches_row['id_player2'])
{
  // check if the previous matches are played already
  $matchkey = $matches_row['bracket'] . '-' . $matches_row['round'] . '-' . $matches_row['match'];
  $prev_match_player1 = "";
  $prev_match_player2 = "";
  if (isset($matches[$matchkey]['prev_match_player1']))
  {
    $prev_match_player1 = $matches[$matchkey]['prev_match_player1'];
  }
  if (isset($matches[$matchkey]['prev_match_player2']))
  {
    $prev_match_player2 = $matches[$matchkey]['prev_match_player2'];
  }
  if (($prev_match_player1 == "" or $matches[$prev_match_player1]['confirmed'] != "0000-00-00 00:00:00") and
      ($prev_match_player2 == "" or $matches[$prev_match_player2]['confirmed'] != "0000-00-00 00:00:00"))
  {
    ////////////////////////////////////////////////////////////////////////////////
    // report match
    ////////////////////////////////////////////////////////////////////////////////

    if ($matches_row['submitted'] == "0000-00-00 00:00:00")
    {
      // players
      if ($matches_row['id_player1'] != 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
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
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
			      "WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
      }
      else
      {
	$content_tpl->set_var("I_PLAYER2", "-");
      }

      // not played
      if ($user['usertype_admin'])
      {
	$content_tpl->parse("H_NOT_PLAYED", "B_NOT_PLAYED");
      }

      // mappool
      $mappool_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}mappool` " .
			      "WHERE `id_season` = {$_REQUEST['sid']} AND `deleted` = 0");
      while ($mappool_row = dbFetch($mappool_ref))
      {
	$content_tpl->set_var("I_ID_MAP", $mappool_row['id']);
	$content_tpl->set_var("I_MAP", htmlspecialchars($mappool_row['map']));
	$content_tpl->parse("H_OPTION_MAP", "B_OPTION_MAP", true);
      }

      // maps
      for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
      {
	$content_tpl->set_var("I_NUM_MAP", $i);
	$content_tpl->parse("H_MAP", "B_MAP", true);
      }
      $content_tpl->parse("H_MATCH", "B_MATCH");

      // matchkey
      $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
      $content_tpl->set_var("I_ROUND", $matches_row['round']);
      $content_tpl->set_var("I_MATCH", $matches_row['match']);

      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->set_var("I_OPT", $id_match);
      $content_tpl->parse("H_ADD_REPORT", "B_ADD_REPORT");
    }

    ////////////////////////////////////////////////////////////////////////////////
    // add comment
    ////////////////////////////////////////////////////////////////////////////////

    else
    {
      if ($matches_row['id_player1'] == $user['uid'])
      {
	$player = "p1";
      }
      elseif ($matches_row['id_player2'] == $user['uid'])
      {
	$player = "p2";
      }

      // maps-query
      $maps_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}maps` " .
			   "WHERE `id_match` = {$matches_row['id']} ORDER BY `num_map` ASC");
      $comments = false;
      while ($maps_row = dbFetch($maps_ref))
      {
	if ($maps_row["comment_$player"] == "")
	{
	  $comments = true;
	  $sshot_dir = "data/screenshots/{$_REQUEST['sid']}/";
	  $sshot = $sshot_dir . "{$_REQUEST['sid']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$maps_row['num_map']}_thumb.jpg";
	  $content_tpl->set_var("H_SCREENSHOT", "");
	  if (file_exists($sshot))
	  {
	    $content_tpl->set_var("I_SCREENSHOT", htmlspecialchars($sshot));
	    $content_tpl->parse("H_SCREENSHOT", "B_SCREENSHOT");
	  }
	  $content_tpl->set_var("I_NUM_MAP", $maps_row['num_map']);
	  $content_tpl->parse("H_COMMENT", "B_COMMENT", true);
	}
      }
      if (!$comments)
      {
	$content_tpl->parse("H_NO_COMMENT", "B_NO_COMMENT");
      }
      else
      {
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->set_var("I_OPT", $id_match);
	$content_tpl->parse("H_ADD_COMMENT", "B_ADD_COMMENT");
      }
    }
  }
  else
  {
    $content_tpl->parse("H_WARNING_PREV_MATCHES", "B_WARNING_PREV_MATCHES");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
