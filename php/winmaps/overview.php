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
$content_tpl->set_block("F_CONTENT", "B_NO_MATCHES", "H_NO_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_NEW_WINMAPS", "H_NEW_WINMAPS");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_MATCHES", "H_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_WINMAPS", "H_OVERVIEW_WINMAPS");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $match_counter = 0;
  // matches-query [gf]
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `bracket` = 'gf' " .
			  "ORDER BY `round` DESC, `match` ASC");
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);
    $content_tpl->set_var("I_WINMAPS", $matches_row['num_winmaps']);
    $content_tpl->parse("H_NEW_WINMAPS", "B_NEW_WINMAPS");
    $content_tpl->parse("H_MATCH", "B_MATCH", true);
  }

  // matches-query [wb]
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `bracket` = 'wb' " .
			  "ORDER BY `round` DESC, `match` ASC");
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);
    $content_tpl->set_var("I_WINMAPS", $matches_row['num_winmaps']);
    $content_tpl->parse("H_NEW_WINMAPS", "B_NEW_WINMAPS");
    $content_tpl->parse("H_MATCH", "B_MATCH", true);
  }

  // matches-query [lb]
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `id_season` = {$_REQUEST['sid']} " .
			  "AND `bracket` = 'lb' " .
			  "ORDER BY `round` DESC, `match` ASC");
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);
    $content_tpl->set_var("I_WINMAPS", $matches_row['num_winmaps']);
    $content_tpl->parse("H_NEW_WINMAPS", "B_NEW_WINMAPS");
    $content_tpl->parse("H_MATCH", "B_MATCH", true);
  }
 
  if ($match_counter == 0)
  {
    $content_tpl->parse("H_NO_MATCHES", "B_NO_MATCHES");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_MATCHES", "B_MATCHES");
  $content_tpl->parse("H_OVERVIEW_WINMAPS", "B_OVERVIEW_WINMAPS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
