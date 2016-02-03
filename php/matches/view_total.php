<?php

################################################################################
#
# $Id: view_total.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_NO_RESULTS", "H_NO_RESULTS");
$content_tpl->set_block("F_CONTENT", "B_SECTION", "H_SECTION");
$content_tpl->set_block("F_CONTENT", "B_RESULTS", "H_RESULTS");
$content_tpl->set_block("F_CONTENT", "B_L10G", "H_L10G");
$content_tpl->set_block("F_CONTENT", "B_L10GS", "H_L10GS");

$results = false;
$counter = 0;

// sections-query
$sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `deleted` = 0 ORDER BY `name` ASC");
while ($sections_row = dbFetch($sections_ref))
{
  // seasons-query
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `id_section` = {$sections_row['id']} AND `deleted` = 0 ORDER BY `submitted` DESC");
  while ($seasons_row = dbfetch($seasons_ref))
  {
    // matches-query
    $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			    "WHERE `id_season` = {$seasons_row['id']} " .
			    "AND `confirmed` <> '0000-00-00 00:00:00' " .
			    "AND `bye` = 0 AND `wo` = 0 AND `out` = 0 " .
			    "ORDER BY `confirmed` DESC " .
			    "LIMIT 0, 10");
    $content_tpl->set_var("H_L10G", "");
    if (dbNumRows($matches_ref) > 0)
    {
      $results = true;
      for ($i = 0; $i < 10; $i++)
      {
	if ($matches_row = dbFetch($matches_ref))
	{
	  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player1']}");
	  $users_row = dbFetch($users_ref);
	  $player1 = $users_row['username'];
	  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$matches_row['id_player2']}");
	  $users_row = dbFetch($users_ref);
	  $player2 = $users_row['username'];
	  $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	  $content_tpl->set_var("I_PLAYER1", "$player1");
	  $content_tpl->set_var("I_PLAYER2", "$player2");
	  $content_tpl->set_var("I_ID_SEASON", $seasons_row['id']);
	  $content_tpl->parse("H_L10G", "B_L10G", true);
	}
      }
      $content_tpl->set_var("I_ID_SEASON", $seasons_row['id']);
      $content_tpl->set_var("I_SECTION_NAME", $sections_row['name']);
      $content_tpl->set_var("I_SEASON_NAME", $seasons_row['name']);
      if ($counter % 3 == 0)
      {
	$content_tpl->parse("I_L10GS1", "B_L10GS");
      }
      elseif ($counter % 3 == 1)
      {
	$content_tpl->parse("I_L10GS2", "B_L10GS");
      }
      elseif ($counter % 3 == 2)
      {
	$content_tpl->parse("I_L10GS3", "B_L10GS");
	$content_tpl->parse("H_SECTION", "B_SECTION", true);
	$content_tpl->set_var("I_L10GS1", "");
	$content_tpl->set_var("I_L10GS2", "");
	$content_tpl->set_var("I_L10GS3", "");
      }
      $counter++;
    }
  }
}
if ($results)
{
  if ($counter % 3 != 0)
  {
    $content_tpl->parse("H_SECTION", "B_SECTION", true);
  }
  $content_tpl->parse("H_RESULTS", "B_RESULTS");
}
else
{
  $content_tpl->parse("H_NO_RESULTS", "B_NO_RESULTS");
}

?>
