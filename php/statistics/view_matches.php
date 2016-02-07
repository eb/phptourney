<?php

################################################################################
#
# $Id: view_matches.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_LED_RED", "H_LED_RED");
$content_tpl->set_block("F_CONTENT", "B_LED_ORANGE", "H_LED_ORANGE");
$content_tpl->set_block("F_CONTENT", "B_LED_GREEN", "H_LED_GREEN");
$content_tpl->set_block("F_CONTENT", "B_LED_DONE", "H_LED_DONE");
$content_tpl->set_block("F_CONTENT", "B_SEASON_MATCHES", "H_SEASON_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_SECTION_MATCHES", "H_SECTION_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_VIEW_STATISTICS", "H_VIEW_STATISTICS");

////////////////////////////////////////////////////////////////////////////////
// sections
////////////////////////////////////////////////////////////////////////////////

$total_signups = 0;
$total_matches = 0;
$total_played = 0;
$total_wos = 0;
$total_byes = 0;
$total_outs = 0;

// sections-query
$sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `deleted` = 0 ORDER BY `name`");
while ($sections_row = dbFetch($sections_ref))
{
  ////////////////////////////////////////////////////////////////////////////////
  // seasons
  ////////////////////////////////////////////////////////////////////////////////

  $section_signups = 0;
  $section_matches = 0;
  $section_played = 0;
  $section_wos = 0;
  $section_byes = 0;
  $section_outs = 0;

  // seasons-query
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `id_section` = {$sections_row['id']} AND `deleted` = 0");
  $content_tpl->set_var("H_SEASON_MATCHES", "");
  while ($seasons_row = dbFetch($seasons_ref))
  {
    // signups
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `usertype_player` = 1 AND `rejected` = 0 AND `id_season` = {$seasons_row['id']}");
    $season_signups = dbNumRows($season_users_ref);
    $section_signups += $season_signups;

    // matches
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `confirmed` <> '0000-00-00 00:00:00' AND `id_season` = {$seasons_row['id']}");
    $season_matches = dbNumRows($matches_ref);
    $section_matches += $season_matches;

    // played matches
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `wo` = 0 AND `bye` = 0 AND `out` = 0 AND `confirmed` <> '0000-00-00 00:00:00' " .
			    "AND `id_season` = {$seasons_row['id']}");
    $season_played = dbNumRows($matches_ref);
    $section_played += $season_played;

    // walkovers
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `wo` <> 0 AND `confirmed` <> '0000-00-00 00:00:00' AND `id_season` = {$seasons_row['id']}");
    $season_wos = dbNumRows($matches_ref);
    $section_wos += $season_wos;

    // byes
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `bye` = 1 AND `confirmed` <> '0000-00-00 00:00:00' AND `id_season` = {$seasons_row['id']}");
    $season_byes = dbNumRows($matches_ref);
    $section_byes += $season_byes;

    // outs
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `out` = 1 AND `confirmed` <> '0000-00-00 00:00:00' AND `id_season` = {$seasons_row['id']}");
    $season_outs = dbNumRows($matches_ref);
    $section_outs += $season_outs;

    $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($seasons_row['name']));
    if ($seasons_row['status'] == "")
    {
      $content_tpl->parse("I_LED", "B_LED_RED");
    }
    elseif ($seasons_row['status'] == "signups")
    {
      $content_tpl->parse("I_LED", "B_LED_ORANGE");
    }
    elseif ($seasons_row['status'] == "running")
    {
      $content_tpl->parse("I_LED", "B_LED_GREEN");
    }
    elseif ($seasons_row['status'] == "finished")
    {
      $content_tpl->parse("I_LED", "B_LED_DONE");
    }
    $content_tpl->set_var("I_SIGNUPS", $season_signups);
    $content_tpl->set_var("I_MATCHES", $season_matches);
    $content_tpl->set_var("I_PLAYED", $season_played);
    $content_tpl->set_var("I_WOS", $season_wos);
    $content_tpl->set_var("I_BYES", $season_byes);
    $content_tpl->set_var("I_OUTS", $season_outs);
    $content_tpl->parse("H_SEASON_MATCHES", "B_SEASON_MATCHES", true);
  }

  $total_signups += $section_signups;
  $total_matches += $section_matches;
  $total_played += $section_played;
  $total_wos += $section_wos;
  $total_byes += $section_byes;
  $total_outs += $section_outs;

  $content_tpl->set_var("I_SECTION_NAME", htmlspecialchars($sections_row['name']));
  $content_tpl->set_var("I_SIGNUPS", $section_signups);
  $content_tpl->set_var("I_MATCHES", $section_matches);
  $content_tpl->set_var("I_PLAYED", $section_played);
  $content_tpl->set_var("I_WOS", $section_wos);
  $content_tpl->set_var("I_BYES", $section_byes);
  $content_tpl->set_var("I_OUTS", $section_outs);
  $content_tpl->parse("H_SECTION_MATCHES", "B_SECTION_MATCHES", true);
}

$content_tpl->set_var("I_TOURNEY_NAME", htmlspecialchars($cfg['tourney_name']));
$content_tpl->set_var("I_SIGNUPS", $total_signups);
$content_tpl->set_var("I_MATCHES", $total_matches);
$content_tpl->set_var("I_PLAYED", $total_played);
$content_tpl->set_var("I_WOS", $total_wos);
$content_tpl->set_var("I_BYES", $total_byes);
$content_tpl->set_var("I_OUTS", $total_outs);
$content_tpl->parse("H_VIEW_STATISTICS", "B_VIEW_STATISTICS", true);

?>
