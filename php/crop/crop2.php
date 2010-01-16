<?php

################################################################################
#
# $Id: crop2.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_CROP2", "H_CROP2");

// matches-query
$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = {$_REQUEST['opt']}");
$matches_row = dbFetch($matches_ref);

if ($user['usertype_admin'] or
    ($matches_row['id_player1'] == $user['uid'] or $matches_row['id_player2'] == $user['uid']) and
    $matches_row['confirmed'] == "0000-00-00 00:00:00") {

  ////////////////////////////////////////////////////////////////////////////////
  // match
  ////////////////////////////////////////////////////////////////////////////////

  $users_ref = dbQuery("SELECT {$cfg['db_table_prefix']}users.*, {$cfg['db_table_prefix']}countries.`abbreviation` " .
		       "FROM `{$cfg['db_table_prefix']}users` " .
		       "LEFT JOIN `{$cfg['db_table_prefix']}countries` " .
		       "ON {$cfg['db_table_prefix']}users.`id_country` = {$cfg['db_table_prefix']}countries.`id` " .
		       "WHERE {$cfg['db_table_prefix']}users.`id` = {$matches_row['id_player1']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER1", $users_row['username']);
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", $users_row['abbreviation']);

  $users_ref = dbQuery("SELECT {$cfg['db_table_prefix']}users.*, {$cfg['db_table_prefix']}countries.`abbreviation` " .
		       "FROM `{$cfg['db_table_prefix']}users` " .
		       "LEFT JOIN `{$cfg['db_table_prefix']}countries` " .
		       "ON {$cfg['db_table_prefix']}users.`id_country` = {$cfg['db_table_prefix']}countries.`id` " .
		       "WHERE {$cfg['db_table_prefix']}users.`id` = {$matches_row['id_player2']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER2", $users_row['username']);
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", $users_row['abbreviation']);
  $content_tpl->parse("H_MATCH", "B_MATCH");

  ////////////////////////////////////////////////////////////////////////////////
  // screenshot
  ////////////////////////////////////////////////////////////////////////////////

  // maps-query
  $maps_ref = dbQuery("SELECT M2.`map` " .
		      "FROM `{$cfg['db_table_prefix']}maps` M1,`{$cfg['db_table_prefix']}mappool` M2 " .
		      "WHERE M1.`id_match` = {$matches_row['id']} AND M2.`id` = M1.`id_map` " .
		      "AND M1.`num_map` = {$_REQUEST['num_map']}");
  $maps_row = dbFetch($maps_ref);

  $sshot_dir = "data/screenshots/{$_REQUEST['sid']}/";
  $sshot_prefix = "{$_REQUEST['sid']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$_REQUEST['num_map']}";
  $sshot = $sshot_dir . $sshot_prefix . ".jpg";
  $sshot_thumb = $sshot_dir . $sshot_prefix . "_thumb.jpg";

  $content_tpl->set_var("I_CROP1_X", $_REQUEST['x']);
  $content_tpl->set_var("I_CROP1_Y", $_REQUEST['y']);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->set_var("I_ID_MATCH", $_REQUEST['opt']);
  $content_tpl->set_var("I_NUM_MAP", $_REQUEST['num_map']);
  $content_tpl->set_var("I_MAP_NAME", $maps_row['map']);
  $content_tpl->set_var("I_SCREENSHOT", $sshot);
  $content_tpl->parse("H_CROP2", "B_CROP2");
}
else
{
  $content_tpl->parse("H_WARNING_ACCESS", "B_WARNING_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
