<?php

################################################################################
#
# $Id: overview.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_REPORTED_MATCHES", "H_NO_REPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_REPORTED_MATCH", "H_REPORTED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_REPORTED_MATCH_OUT_OF_TIMEFRAME", "H_REPORTED_MATCH_OUT_OF_TIMEFRAME");
$content_tpl->set_block("F_CONTENT", "B_REPORTED_MATCHES", "H_REPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_REPORTED_MATCHES", "H_OVERVIEW_REPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_NO_UNREPORTED_MATCHES", "H_NO_UNREPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_UNREPORTED_MATCH", "H_UNREPORTED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_UNREPORTED_MATCHES", "H_UNREPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_UNREPORTED_MATCHES", "H_OVERVIEW_UNREPORTED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_NO_CONFIRMED_MATCHES", "H_NO_CONFIRMED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_CROP", "H_CROP");
$content_tpl->set_block("F_CONTENT", "B_CONFIRMED_MATCH", "H_CONFIRMED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_CONFIRMED_MATCHES", "H_CONFIRMED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_CONFIRMED_MATCHES", "H_OVERVIEW_CONFIRMED_MATCHES");

// access for admins only
if ($user['usertype_admin'])
{
  $match_counter = 0;

  ////////////////////////////////////////////////////////////////////////////////
  // Reported matches
  ////////////////////////////////////////////////////////////////////////////////

  // matches-query
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `submitted` != '0000-00-00 00:00:00' " .
			  "AND `confirmed` = '0000-00-00 00:00:00' " .
			  "ORDER BY `bracket` ASC, `round` DESC, `match` ASC");
  if (dbNumRows($matches_ref) <= 0)
  {
    $content_tpl->parse("H_NO_REPORTED_MATCHES", "B_NO_REPORTED_MATCHES");
  }
  else
  {
    while ($matches_row = dbFetch($matches_ref))
    {
      $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
      $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
      $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
      $content_tpl->set_var("I_ROUND", $matches_row['round']);
      $content_tpl->set_var("I_MATCH", $matches_row['match']);
      if ($matches_row['id_player1'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", $users_row['username']);
      }
      else
      {
	$content_tpl->set_var("I_PLAYER1", "-");
      }
      if ($matches_row['id_player2'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER2", $users_row['username']);
      }
      else
      {
	$content_tpl->set_var("I_PLAYER2", "-");
      }
      // deadlines-query
      if (isAfterPreDeadline($season, $matches_row['bracket'], $matches_row['round']))
      {
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	$content_tpl->parse("H_REPORTED_MATCH", "B_REPORTED_MATCH", true);
      }
      else
      {
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	$content_tpl->parse("H_REPORTED_MATCH_OUT_OF_TIMEFRAME", "B_REPORTED_MATCH_OUT_OF_TIMEFRAME", true);
      }
    }
    $content_tpl->parse("H_REPORTED_MATCHES", "B_REPORTED_MATCHES");
  }
  $content_tpl->parse("H_OVERVIEW_REPORTED_MATCHES", "B_OVERVIEW_REPORTED_MATCHES");

  ////////////////////////////////////////////////////////////////////////////////
  // Unreported matches
  ////////////////////////////////////////////////////////////////////////////////

  $unreported_matches = false;

  // matches-query
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `submitted` = '0000-00-00 00:00:00' " .
			  "AND `confirmed` = '0000-00-00 00:00:00' " .
			  "ORDER BY `bracket` ASC, `round` DESC, `match` ASC");
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);
    if ($matches_row['id_player1'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER1", $users_row['username']);
    }
    else
    {
      $content_tpl->set_var("I_PLAYER1", "-");
    }
    if ($matches_row['id_player2'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER2", $users_row['username']);
    }
    else
    {
      $content_tpl->set_var("I_PLAYER2", "-");
    }

    // deadlines-query
    if (isAfterPreDeadline($season, $matches_row['bracket'], $matches_row['round']))
    {
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
      $unreported_matches = true;
      $content_tpl->parse("H_UNREPORTED_MATCH", "B_UNREPORTED_MATCH", true);
    }
  }
  if (!$unreported_matches)
  {
    $content_tpl->parse("H_NO_UNREPORTED_MATCHES", "B_NO_UNREPORTED_MATCHES");
  }
  else
  {
    $content_tpl->parse("H_UNREPORTED_MATCHES", "B_UNREPORTED_MATCHES");
  }
  $content_tpl->parse("H_OVERVIEW_UNREPORTED_MATCHES", "B_OVERVIEW_UNREPORTED_MATCHES");

  ////////////////////////////////////////////////////////////////////////////////
  // Confirmed matches
  ////////////////////////////////////////////////////////////////////////////////

  // matches-query
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `confirmed` <> '0000-00-00 00:00:00' " .
			  "ORDER BY `bracket` ASC, `round` DESC, `match` ASC");
  if (dbNumRows($matches_ref) <= 0)
  {
    $content_tpl->parse("H_NO_CONFIRMED_MATCHES", "B_NO_CONFIRMED_MATCHES");
  }
  else
  {
    while ($matches_row = dbFetch($matches_ref))
    {
      $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
      $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
      $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
      $content_tpl->set_var("I_ROUND", $matches_row['round']);
      $content_tpl->set_var("I_MATCH", $matches_row['match']);
      if ($matches_row['id_player1'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER1", $users_row['username']);
      }
      else
      {
	$content_tpl->set_var("I_PLAYER1", "-");
      }
      if ($matches_row['id_player2'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$content_tpl->set_var("I_PLAYER2", $users_row['username']);
      }
      else
      {
	$content_tpl->set_var("I_PLAYER2", "-");
      }
      if ($cfg['convert'] != "")
      {
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	$content_tpl->parse("H_CROP", "B_CROP");
      }
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->parse("H_CONFIRMED_MATCH", "B_CONFIRMED_MATCH", true);
    }
    $content_tpl->parse("H_CONFIRMED_MATCHES", "B_CONFIRMED_MATCHES");
  }
  $content_tpl->parse("H_OVERVIEW_CONFIRMED_MATCHES", "B_OVERVIEW_CONFIRMED_MATCHES");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
