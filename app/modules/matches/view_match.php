<?php

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

$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `matches` WHERE `id` = $id_match");
if ($matches_row = dbFetch($matches_ref))
{
  // Player1
  if ($matches_row['id_player1'] > 0)
  {
    $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
			  "FROM `users` U " .
			  "LEFT JOIN `countries` C " .
			  "ON U.`id_country` = C.`id` " .
			  "WHERE U.`id` = {$matches_row['id_player1']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_ID_PLAYER1", $users_row['id']);
    $content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", htmlspecialchars($users_row['abbreviation']));
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_PLAYER1", "B_PLAYER1");
  }
  else
  {
    $content_tpl->parse("H_NO_PLAYER1", "B_NO_PLAYER1");
  }

  // Player2
  if ($matches_row['id_player2'] > 0)
  {
    $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
			  "FROM `users` U " .
			  "LEFT JOIN `countries` C " .
			  "ON U.`id_country` = C.`id` " .
			  "WHERE U.`id` = {$matches_row['id_player2']}");
    $users_row = dbFetch($users_ref);
    $content_tpl->set_var("I_ID_PLAYER2", $users_row['id']);
    $content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", htmlspecialchars($users_row['abbreviation']));
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_PLAYER2", "B_PLAYER2");
  }
  else
  {
    $content_tpl->parse("H_NO_PLAYER2", "B_NO_PLAYER2");
  }
  $content_tpl->parse("H_PLAYERS", "B_PLAYERS");

  // Not confirmed yet
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
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['wo']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER", htmlspecialchars($users_row['username']));
      $content_tpl->parse("H_WO", "B_WO");
      if ($matches_row['comment_admin'] != "")
      {
	$content_tpl->set_var("I_COMMENT", Parsedown::instance()->text($matches_row['comment_admin']));
	$content_tpl->parse("I_COMMENT", "B_COMMENT_ADMIN");
      }
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    }
    elseif ($matches_row['bye'] == 1)
    {
      if ($matches_row['id_player1'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player1']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
	$content_tpl->set_var("I_PLAYER2", "-");
	$content_tpl->set_var("I_PLAYER", htmlspecialchars($users_row['username']));
      }
      elseif ($matches_row['id_player2'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", "-");
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
	$content_tpl->set_var("I_PLAYER", htmlspecialchars($users_row['username']));
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
	$content_tpl->set_var("I_COMMENT", Parsedown::instance()->text($matches_row['comment_admin']));
	$content_tpl->parse("I_COMMENT", "B_COMMENT_ADMIN");
      }
      $content_tpl->parse("H_OUTCOME", "B_OUTCOME");
    }
    elseif ($matches_row['out'] == 1)
    {
      $content_tpl->parse("H_OUT", "B_OUT");
      if ($matches_row['comment_admin'] != "")
      {
	$content_tpl->set_var("I_COMMENT", Parsedown::instance()->text($matches_row['comment_admin']));
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
	"data/screenshots/{$season['id']}/" .
	"{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}";

      // Maps
      $maps_ref = dbQuery("SELECT M1.*, M2.`map` " .
			   "FROM `maps` M1,`mappool` M2 " .
			   "WHERE M1.`id_match` = {$matches_row['id']} AND M1.`id_map` = M2.`id` " .
			   "ORDER BY `num_map` ASC");
      while ($maps_row = dbFetch($maps_ref))
      {
	$content_tpl->set_var("I_MAP_SCREENSHOT", "");
	$screenshot = $screenshot_prefix . "-m" . $maps_row['num_map'] . ".jpg";
	$screenshot_thumb = $screenshot_prefix . "-m" . $maps_row['num_map'] . "_thumb.jpg";
	if (file_exists($screenshot) and file_exists($screenshot_thumb))
	{
	  $content_tpl->set_var("I_SCREENSHOT", htmlspecialchars($screenshot));
	  $content_tpl->set_var("I_SCREENSHOT_THUMB", htmlspecialchars($screenshot_thumb));
	  $content_tpl->parse("I_MAP_SCREENSHOT", "B_SCREENSHOT");
	}
	$content_tpl->set_var("I_MAP", htmlspecialchars($maps_row['map']));
	$content_tpl->set_var("I_SCORE", $maps_row['score_p1'] . " - " . $maps_row['score_p2']);

	$content_tpl->set_var("I_MAP_COMMENTS", "");
	// Comments player1
	if ($maps_row['comment_p1'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", nl2br(htmlspecialchars($maps_row['comment_p1'])));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_P1", true);
	}

	// Comments player2
	if ($maps_row['comment_p2'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", nl2br(htmlspecialchars($maps_row['comment_p2'])));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_P2", true);
	}

	// Comments admin
	if ($maps_row['comment_admin'] != "")
	{
	  $content_tpl->set_var("I_COMMENT", Parsedown::instance()->text($maps_row['comment_admin']));
	  $content_tpl->parse("I_MAP_COMMENTS", "B_COMMENT_ADMIN", true);
	}
	$content_tpl->parse("H_MAP", "B_MAP", true);
      }
    }
    $content_tpl->set_var("I_COMMENT", "");

    $demos_ref = dbQuery("SELECT * FROM `demos` WHERE `id_match` = $id_match");
    while ($demos_row = dbFetch($demos_ref))
    {
      $content_tpl->set_var("I_URL", htmlspecialchars($demos_row['url']));
      $content_tpl->parse("H_DEMOS", "B_DEMOS");
    }
  }
  // Matchkey
  $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
  $content_tpl->set_var("I_ROUND", $matches_row['round']);
  $content_tpl->set_var("I_MATCH", $matches_row['match']);
  $content_tpl->parse("H_MATCH", "B_MATCH");

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
  $content_tpl->parse("H_VIEW_MATCH", "B_VIEW_MATCH");

  $comments_ref = dbQuery("SELECT MC.*, U.`username` " .
			  "FROM `match_comments` MC " .
			  "LEFT JOIN `users` U " .
			  "ON MC.`id_user` = U.`id` " .
			  "WHERE `id_match` = $id_match ORDER BY `submitted`");
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
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($comments_row['username']));
      $ip = preg_replace("/(.*\\.).*/", "$1xxx", $comments_row['ip']);
      $content_tpl->set_var("I_IP", htmlspecialchars($ip));
      $content_tpl->set_var("I_BODY", nl2br(htmlspecialchars($comments_row['body'])));
      $content_tpl->set_var("I_SUBMITTED", htmlspecialchars($comments_row['submitted']));

      if ($user['usertype_admin'] == 1)
      {
	$ip = $comments_row['ip'];
	$content_tpl->set_var("I_IP", htmlspecialchars($ip));
	$content_tpl->set_var("I_ID_SEASON", $season['id']);
	$content_tpl->parse("H_BANS", "B_BANS");
      }

      $username = dbEscape($comments_row['username']);
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `username` = '$username'");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->parse("H_VIEW_COMMENT", "B_VIEW_COMMENT", true);
      $counter++;
    }
  }
  $content_tpl->parse("H_VIEW_COMMENTS", "B_VIEW_COMMENTS");

  // Add comments
  $content_tpl->set_var("I_ID_MATCH", $id_match);
  $content_tpl->set_var("I_BODY", "");
  if ($user['uid'])
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_ADD_COMMENT", "B_ADD_COMMENT");
  }
  else
  {
    $content_tpl->parse("H_LOGIN_TO_COMMENT", "B_LOGIN_TO_COMMENT");
  }
}

?>
