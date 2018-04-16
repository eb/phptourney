<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_CROP3", "H_CROP3");

$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = $id_match");
$matches_row = dbFetch($matches_ref);

if ($user['usertype_admin']) {

  // Match
  $users_ref = dbQuery("SELECT {$cfg['db_table_prefix']}users.*, {$cfg['db_table_prefix']}countries.`abbreviation` " .
		       "FROM `{$cfg['db_table_prefix']}users` " .
		       "LEFT JOIN `{$cfg['db_table_prefix']}countries` " .
		       "ON {$cfg['db_table_prefix']}users.`id_country` = {$cfg['db_table_prefix']}countries.`id` " .
		       "WHERE {$cfg['db_table_prefix']}users.`id` = {$matches_row['id_player1']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", htmlspecialchars($users_row['abbreviation']));

  $users_ref = dbQuery("SELECT {$cfg['db_table_prefix']}users.*, {$cfg['db_table_prefix']}countries.`abbreviation` " .
		       "FROM `{$cfg['db_table_prefix']}users` " .
		       "LEFT JOIN `{$cfg['db_table_prefix']}countries` " .
		       "ON {$cfg['db_table_prefix']}users.`id_country` = {$cfg['db_table_prefix']}countries.`id` " .
		       "WHERE {$cfg['db_table_prefix']}users.`id` = {$matches_row['id_player2']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", htmlspecialchars($users_row['abbreviation']));
  $content_tpl->parse("H_MATCH", "B_MATCH");

  // Screenshot
  $num_map = intval($_REQUEST['num_map']);
  $maps_ref = dbQuery("SELECT M2.`map` " .
		      "FROM `{$cfg['db_table_prefix']}maps` M1,`{$cfg['db_table_prefix']}mappool` M2 " .
		      "WHERE M1.`id_match` = {$matches_row['id']} AND M2.`id` = M1.`id_map` " .
		      "AND M1.`num_map` = $num_map");
  $maps_row = dbFetch($maps_ref);

  $sshot_dir = "data/screenshots/{$season['id']}/";
  $sshot_prefix = "{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$_REQUEST['num_map']}";
  $sshot = $sshot_dir . $sshot_prefix . ".jpg";
  $sshot_thumb = $sshot_dir . $sshot_prefix . "_thumb.jpg";

  $crop1_x = $_REQUEST['crop1_x'];
  $crop1_y = $_REQUEST['crop1_y'];
  $crop2_x = $_REQUEST['x'];
  $crop2_y = $_REQUEST['y'];

  if ($crop2_x < $crop1_x)
  {
    $tmp = $crop1_x;
    $crop1_x = $crop2_x;
    $crop2_x = $tmp;
  }

  if ($crop2_y < $crop1_y)
  {
    $tmp = $crop1_y;
    $crop1_y = $crop2_y;
    $crop2_y = $tmp;
  }

  $width = $crop2_x - $crop1_x;
  $height = $crop2_y - $crop1_y;

  `{$cfg['convert']} -crop {$width}x{$height}+{$crop1_x}+{$crop1_y} $sshot $sshot_thumb`;
  `{$cfg['convert']} -geometry 320 $sshot_thumb $sshot_thumb`;

  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->set_var("I_ID_MATCH", $id_match);
  $content_tpl->set_var("I_NUM_MAP", $num_map);
  $content_tpl->set_var("I_MAP_NAME", htmlspecialchars($maps_row['map']));
  $content_tpl->set_var("I_SCREENSHOT_THUMB", htmlspecialchars($sshot_thumb));
  $content_tpl->parse("H_CROP3", "B_CROP3");
}
else
{
  $content_tpl->parse("H_WARNING_ACCESS", "B_WARNING_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
