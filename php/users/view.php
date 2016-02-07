<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_SHOW_IP", "H_SHOW_IP");
$content_tpl->set_block("F_CONTENT", "B_SHOW_IPS", "H_SHOW_IPS");
$content_tpl->set_block("F_CONTENT", "B_SHOW_EMAIL", "H_SHOW_EMAIL");
$content_tpl->set_block("F_CONTENT", "B_SHOW_PROFILE", "H_SHOW_PROFILE");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYED_MATCHES", "H_NO_PLAYED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_PLAYED_MATCH", "H_PLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_PLAYED_MATCHES", "H_PLAYED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_PLAYED_MATCHES", "H_OVERVIEW_PLAYED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_NO_SEASONS", "H_NO_SEASONS");
$content_tpl->set_block("F_CONTENT", "B_HEADADMIN_CHECKED", "H_HEADADMIN_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_ADMIN_CHECKED", "H_ADMIN_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_PLAYER_CHECKED", "H_PLAYER_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_SEASON", "H_SEASON");
$content_tpl->set_block("F_CONTENT", "B_SEASONS", "H_SEASONS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_SEASONS", "H_OVERVIEW_SEASONS");

////////////////////////////////////////////////////////////////////////////////
// profile
////////////////////////////////////////////////////////////////////////////////

// users-query
$id_user = intval($_REQUEST['opt']);
$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
		      "WHERE `id` = $id_user");
$users_row = dbFetch($users_ref);

$content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
$content_tpl->set_var("I_IRC_CHANNEL", htmlspecialchars($users_row['irc_channel']));

$countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` WHERE `id` = {$users_row['id_country']}");
$countries_row = dbFetch($countries_ref);
$content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($countries_row['abbreviation']));
$content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));

$is_complete = 0;
if ($user['usertype_admin'])
{
  $is_complete = 1;
}
else if ($_REQUEST['opt'] == $user['uid'])
{
  $is_complete = 1;
}
else if ($user['usertype_player'])
{
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
                         "WHERE `submitted` = '0000-00-00 00:00:00' " .
                         "AND `id_season` = {$_REQUEST['sid']} " .
                         "AND (`id_player1` = {$user['uid']} OR `id_player2` = {$user['uid']}) " .
                         "AND (`id_player1` = $id_user OR `id_player2` = $id_user)");
  if (dbNumRows($matches_ref) > 0)
  {
    $is_complete = 1;
  }
}
if ($is_complete)
{
  $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
  $content_tpl->parse("H_SHOW_EMAIL", "B_SHOW_EMAIL");
}

if ($user['usertype_admin'])
{
  // season_users-query
  $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
			       "WHERE `id_user` = $id_user");
  while ($season_users_row = dbFetch($season_users_ref))
  {
    if ($season_users_row['ip'] != "")
    {
      $content_tpl->set_var("I_IP", htmlspecialchars($season_users_row['ip']));
      $content_tpl->parse("H_SHOW_IP", "B_SHOW_IP", true);
    }
  }
  $content_tpl->parse("H_SHOW_IPS", "B_SHOW_IPS");
}
$content_tpl->parse("H_SHOW_PROFILE", "B_SHOW_PROFILE");

////////////////////////////////////////////////////////////////////////////////
// played matches
////////////////////////////////////////////////////////////////////////////////

// matches-query
$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			"WHERE `id_season` = {$_REQUEST['sid']} " .
			"AND (`id_player1` = $id_user OR `id_player2` = $id_user) " .
			"AND `wo` = 0 " .
			"AND `bye` = 0 " .
			"AND `out` = 0 " .
			"AND `confirmed` <> '0000-00-00 00:00:00' " .
			"ORDER BY `confirmed` DESC");
$match_counter = 0;
if (dbNumRows($matches_ref) <= 0)
{
  $content_tpl->parse("H_NO_PLAYED_MATCHES", "B_NO_PLAYED_MATCHES");
}
else
{
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    // match
    if ($matches_row['id_player1'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
      $users_row = dbFetch($users_ref);
      $player1 = $users_row['username'];
    }
    else
    {
      $player1 = "-";
    }
    $content_tpl->set_var("I_PLAYER1", htmlspecialchars($player1));

    if ($matches_row['id_player2'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
      $users_row = dbFetch($users_ref);
      $player2 = $users_row['username'];
    }
    else
    {
      $player2 = "-";
    }
    $content_tpl->set_var("I_PLAYER2", htmlspecialchars($player2));

    // outcome
    $outcome = "{$matches_row['score_p1']} - {$matches_row['score_p2']}";

    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
    $content_tpl->set_var("I_ROUND", htmlspecialchars($matches_row['round']));
    $content_tpl->set_var("I_MATCH", htmlspecialchars($matches_row['match']));
    $content_tpl->set_var("I_OUTCOME", htmlspecialchars($outcome));
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_PLAYED_MATCH", "B_PLAYED_MATCH", true);
  }
  $content_tpl->parse("H_PLAYED_MATCHES", "B_PLAYED_MATCHES");
}
$content_tpl->parse("H_OVERVIEW_PLAYED_MATCHES", "B_OVERVIEW_PLAYED_MATCHES");

////////////////////////////////////////////////////////////////////////////////
// seasons
////////////////////////////////////////////////////////////////////////////////

// season_users-query
$season_users_ref = dbQuery("SELECT SU.*,S.`name` AS `season_name` " .
			     "FROM `{$cfg['db_table_prefix']}season_users` SU,`{$cfg['db_table_prefix']}seasons` S " .
			     "WHERE SU.`id_user` = $id_user " .
			     "AND SU.`id_season` = S.`id` " .
			     "ORDER BY `submitted` DESC");
$season_counter = $match_counter;
if (dbNumRows($season_users_ref) <= 0)
{
  $content_tpl->parse("H_NO_SEASONS", "B_NO_SEASONS");
}
else
{
  while ($season_users_row = dbFetch($season_users_ref))
  {
    $content_tpl->set_var("H_HEADADMIN_CHECKED", "");
    $content_tpl->set_var("H_ADMIN_CHECKED", "");
    $content_tpl->set_var("H_ADMIN_UNCHECKED", "");
    $content_tpl->set_var("H_PLAYER_CHECKED", "");
    $content_tpl->set_var("H_PLAYER_UNCHECKED", "");

    $content_tpl->set_var("I_SEASON_COUNTER", ++$season_counter);
    $usertype = false;
    if ($season_users_row['usertype_headadmin'])
    {
      $content_tpl->parse("H_HEADADMIN_CHECKED", "B_HEADADMIN_CHECKED");
      $usertype = true;
    }
    elseif ($season_users_row['usertype_admin'])
    {
      $content_tpl->parse("H_ADMIN_CHECKED", "B_ADMIN_CHECKED");
      $usertype = true;
    }
    if ($season_users_row['usertype_player'] and !$season_users_row['rejected'])
    {
      $content_tpl->parse("H_PLAYER_CHECKED", "B_PLAYER_CHECKED");
      $usertype = true;
    }

    $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($season_users_row['season_name']));
    if ($usertype)
    {
      $content_tpl->parse("H_SEASON", "B_SEASON", true);
    }
  }
  $content_tpl->parse("H_SEASONS", "B_SEASONS");
}
$content_tpl->parse("H_OVERVIEW_SEASONS", "B_OVERVIEW_SEASONS");

?>
