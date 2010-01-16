<?php

################################################################################
#
# $Id: view_match.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_SCREENSHOT", "H_SCREENSHOT");
$content_tpl->set_block("F_CONTENT", "B_COMMENT_P1", "H_COMMENT_P1");
$content_tpl->set_block("F_CONTENT", "B_COMMENT_P2", "H_COMMENT_P2");
$content_tpl->set_block("F_CONTENT", "B_COMMENT_ADMIN", "H_COMMENT_ADMIN");
$content_tpl->set_block("F_CONTENT", "B_PLAYER1", "H_PLAYER1");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYER1", "H_NO_PLAYER1");
$content_tpl->set_block("F_CONTENT", "B_PLAYER2", "H_PLAYER2");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYER2", "H_NO_PLAYER2");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS", "H_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_RESULT", "H_RESULT");
$content_tpl->set_block("F_CONTENT", "B_WO", "H_WO");
$content_tpl->set_block("F_CONTENT", "B_BYE", "H_BYE");
$content_tpl->set_block("F_CONTENT", "B_OUT", "H_OUT");
$content_tpl->set_block("F_CONTENT", "B_NOT_PLAYED", "H_NOT_PLAYED");
$content_tpl->set_block("F_CONTENT", "B_OUTCOME", "H_OUTCOME");
$content_tpl->set_block("F_CONTENT", "B_MAP", "H_MAP");
$content_tpl->set_block("F_CONTENT", "B_DEMOS", "H_DEMOS");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_SUBMIT_TIMESTAMP", "H_SUBMIT_TIMESTAMP");
$content_tpl->set_block("F_CONTENT", "B_CONFIRM_TIMESTAMP", "H_CONFIRM_TIMESTAMP");
$content_tpl->set_block("F_CONTENT", "B_VIEW_MATCH", "H_VIEW_MATCH");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_COMMENTS", "H_VIEW_NO_COMMENTS");
$content_tpl->set_block("F_CONTENT", "B_BANS", "H_BANS");
$content_tpl->set_block("F_CONTENT", "B_VIEW_COMMENT", "H_VIEW_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_VIEW_COMMENTS", "H_VIEW_COMMENTS");
$content_tpl->set_block("F_CONTENT", "B_ADD_COMMENT", "H_ADD_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_LOGIN_TO_COMMENT", "H_LOGIN_TO_COMMENT");

$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = {$_REQUEST['opt']}");
if ($matches_row = dbFetch($matches_ref))
{
  // player1
  if ($matches_row['id_player1'] > 0)
  {
    $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
			  "FROM `{$cfg['db_table_prefix']}users` U " .
			  "LEFT JOIN `{$cfg['db_table_prefix']}countries` C " .
			  "ON U.`id_country` = C.`id` " .
			  "WHERE U.`id` = {$matches_row['id_player1']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_ID_PLAYER1", $users_row['id']);
    $content_tpl->set_var("I_PLAYER1", $users_row['username']);
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", $users_row['abbreviation']);
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_PLAYER1", "B_PLAYER1");
  }
  else
  {
    $content_tpl->parse("H_NO_PLAYER1", "B_NO_PLAYER1");
  }

  // player2
  if ($matches_row['id_player2'] > 0)
  {
    $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
			  "FROM `{$cfg['db_table_prefix']}users` U " .
			  "LEFT JOIN `{$cfg['db_table_prefix']}countries` C " .
			  "ON U.`id_country` = C.`id` " .
			  "WHERE U.`id` = {$matches_row['id_player2']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_ID_PLAYER2", $users_row['id']);
    $content_tpl->set_var("I_PLAYER2", $users_row['username']);
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", $users_row['abbreviation']);
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_PLAYER2", "B_PLAYER2");
  }
  else
  {
    $content_tpl->parse("H_NO_PLAYER2", "B_NO_PLAYER2");
  }
  $content_tpl->parse("H_PLAYERS", "B_PLAYERS");

  // not confirmed yet
  if ($matches_row['confirmed'] == "0000-00-00 00:00:00")
  {
    $content_tpl->parse("H_NOT_PLAYED", "B_NOT_PLAYED");
    $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    $content_tpl->parse("H_MATCH", "B_MATCH");
  }
  else
  {
    if ($matches_row['wo'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['wo']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER", $users_row['username']);
      $content_tpl->parse("H_WO", "B_WO");
      if ($matches_row['comment_admin'] != "")
      {
	$content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($matches_row['comment_admin'])));
	$content_tpl->parse("I_COMMENT", "B_COMMENT_ADMIN");
      }
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    }
    elseif ($matches_row['bye'] == 1)
    {
      if ($matches_row['id_player1'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", $users_row['username']);
	$content_tpl->set_var("I_PLAYER2", "-");
	$content_tpl->set_var("I_PLAYER", $users_row['username']);
      }
      elseif ($matches_row['id_player2'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", "-");
	$content_tpl->set_var("I_PLAYER2", $users_row['username']);
	$content_tpl->set_var("I_PLAYER", $users_row['username']);
      }
      else
      {
	$content_tpl->set_var("I_PLAYER1", "-");
	$content_tpl->set_var("I_PLAYER2", "-");
	$content_tpl->set_var("I_PLAYER", "-");
      }
      $content_tpl->parse("H_BYE", "B_BYE");
      if ($matches_row['comment_admin'] != "")
      {
	$content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($matches_row['comment_admin'])));
	$content_tpl->parse("I_COMMENT", "B_COMMENT_ADMIN");
      }
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    }
    elseif ($matches_row['out'] == 1)
    {
      $content_tpl->parse("H_OUT", "B_OUT");
      if ($matches_row['comment_admin'] != "")
      {
	$content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($matches_row['comment_admin'])));
	$content_tpl->parse("I_COMMENT", "B_COMMENT_ADMIN");
      }
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    }
    else
    {
      $content_tpl->set_var("I_RESULT", $matches_row['score_p1'] . " - " . $matches_row['score_p2']);
      $content_tpl->parse("H_RESULT", "B_RESULT");
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");

      $screenshot_prefix =
	"data/screenshots/{$_REQUEST['sid']}/" .
	"{$_REQUEST['sid']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}";

      ////////////////////////////////////////////////////////////////////////////////
      // maps
      ////////////////////////////////////////////////////////////////////////////////

      $content_tpl->set_var("I_MAP_SCREENSHOT", "");
      $content_tpl->set_var("I_MAP_COMMENTS", "");
      // maps-query
      $maps_ref = dbQuery("SELECT M1.*, M2.`map` " .
			   "FROM `{$cfg['db_table_prefix']}maps` M1,`{$cfg['db_table_prefix']}mappool` M2 " .
			   "WHERE M1.`id_match` = {$matches_row['id']} AND M1.`id_map` = M2.`id` " .
			   "ORDER BY `num_map` ASC");
      while ($maps_row = dbFetch($maps_ref))
      {
	$screenshot = $screenshot_prefix . "-m" . $maps_row['num_map'] . ".jpg";
	$screenshot_thumb = $screenshot_prefix . "-m" . $maps_row['num_map'] . "_thumb.jpg";
	if (file_exists($screenshot) and file_exists($screenshot_thumb))
	{
	  $content_tpl->set_var("I_SCREENSHOT", $screenshot);
	  $content_tpl->set_var("I_SCREENSHOT_THUMB", $screenshot_thumb);
	  $content_tpl->parse("I_MAP_SCREENSHOT", "B_SCREENSHOT");
	}
	$content_tpl->set_var("I_MAP", $maps_row['map']);
	$content_tpl->set_var("I_SCORE", $maps_row['score_p1'] . " - " . $maps_row['score_p2']);

	$content_tpl->set_var("I_MAP_COMMENTS", "");
	// comments player1
	if ($maps_row['comment_p1'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($maps_row['comment_p1'])));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_P1", true);
	}

	// comments player2
	if ($maps_row['comment_p2'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($maps_row['comment_p2'])));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_P2", true);
	}

	// comments admin
	if ($maps_row['comment_admin'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", nl2br(addSpacesToLongWords($maps_row['comment_admin'])));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_ADMIN", true);
	}
	$content_tpl->parse("H_MAP", "B_MAP", true);
      }
    }
    $content_tpl->set_var("I_COMMENT", "");

    // demos-query
    $demos_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}demos` WHERE `id_match` = {$_REQUEST['opt']}");
    while ($demos_row = dbFetch($demos_ref))
    {
      $content_tpl->set_var("I_URL", $demos_row['url']);
      $content_tpl->parse("H_DEMOS", "B_DEMOS");
    }
  }
  // matchkey
  $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
  $content_tpl->set_var("I_ROUND", $matches_row['round']);
  $content_tpl->set_var("I_MATCH", $matches_row['match']);
  $content_tpl->parse("H_MATCH", "B_MATCH");

  // submitted
  if ($matches_row['submitted'] != "0000-00-00 00:00:00" and $matches_row['submitter'] != 0)
  {
    $content_tpl->set_var("I_SUBMITTED", $matches_row['submitted']);
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['submitter']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_SUBMITTER", $users_row['username']);
    $content_tpl->parse("H_SUBMIT_TIMESTAMP", "B_SUBMIT_TIMESTAMP");
  }

  // confirmed
  if ($matches_row['confirmed'] != "0000-00-00 00:00:00" and $matches_row['confirmer'] != 0)
  {
    $content_tpl->set_var("I_CONFIRMED", $matches_row['confirmed']);
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['confirmer']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_CONFIRMER", $users_row['username']);
    $content_tpl->parse("H_CONFIRM_TIMESTAMP", "B_CONFIRM_TIMESTAMP");
  }
  $content_tpl->parse("H_VIEW_MATCH", "B_VIEW_MATCH");

  // comments-query
  $comments_ref = dbQuery("SELECT MC.*, U.`username` " .
			  "FROM `{$cfg['db_table_prefix']}match_comments` MC " .
			  "LEFT JOIN `{$cfg['db_table_prefix']}users` U " .
			  "ON MC.`id_user` = U.`id` " .
			  "WHERE `id_match` = {$_REQUEST['opt']} ORDER BY `submitted`");
  if (dbNumRows($comments_ref) <= 0)
  {
    $content_tpl->parse("H_VIEW_NO_COMMENTS", "B_VIEW_NO_COMMENTS");
  }
  else
  {
    $counter = 1;
    while ($comments_row = dbFetch($comments_ref))
    {
      $content_tpl->set_var("I_COUNTER", $counter);
      $content_tpl->set_var("I_USERNAME", $comments_row['username']);
      $ip = preg_replace("/(.*\\.).*/", "$1xxx", $comments_row['ip']);
      $content_tpl->set_var("I_IP", $ip);
      $content_tpl->set_var("I_BODY", nl2br(addSpacesToLongWords($comments_row['body'])));
      $content_tpl->set_var("I_SUBMITTED", $comments_row['submitted']);

      if ($user['usertype_admin'] == 1)
      {
	$ip = $comments_row['ip'];
	$content_tpl->set_var("I_IP", $ip);
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->parse("H_BANS", "B_BANS");
      }

      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `username` = '{$comments_row['username']}'");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->parse("H_VIEW_COMMENT", "B_VIEW_COMMENT", true);
      $counter++;
    }
  }
  $content_tpl->parse("H_VIEW_COMMENTS", "B_VIEW_COMMENTS");

  // add comments
  $content_tpl->set_var("I_ID_MATCH", $_REQUEST['opt']);
  $content_tpl->set_var("I_BODY", "");
  if ($user['uid'])
  {
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_ADD_COMMENT", "B_ADD_COMMENT");
  }
  else
  {
    $content_tpl->parse("H_LOGIN_TO_COMMENT", "B_LOGIN_TO_COMMENT");
  }
}

?>
