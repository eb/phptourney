<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_CROP1", "H_CROP1");

$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `matches` WHERE `id` = $id_match");
$matches_row = dbFetch($matches_ref);

if ($user['usertype_admin']) {

  // Match
  $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
		       "FROM `users` U " .
		       "LEFT JOIN `countries` C " .
		       "ON U.`id_country` = C.`id` " .
		       "WHERE U.`id` = {$matches_row['id_player1']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", htmlspecialchars($users_row['abbreviation']));

  $users_ref = dbQuery("SELECT U.*, C.`abbreviation` " .
		       "FROM `users` U " .
		       "LEFT JOIN `countries` C " .
		       "ON U.`id_country` = C.`id` " .
		       "WHERE U.`id` = {$matches_row['id_player2']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", htmlspecialchars($users_row['abbreviation']));
  $content_tpl->parse("H_MATCH", "B_MATCH");

  // Screenshot
  $num_map = intval($_REQUEST['num_map']);
  $maps_ref = dbQuery("SELECT M2.`map` " .
		      "FROM `maps` M1,`mappool` M2 " .
		      "WHERE M1.`id_match` = {$matches_row['id']} AND M2.`id` = M1.`id_map` " .
		      "AND M1.`num_map` = $num_map");
  $maps_row = dbFetch($maps_ref);

  $sshot_dir = "data/screenshots/{$season['id']}/";
  $sshot_prefix = "{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$_REQUEST['num_map']}";
  $sshot = $sshot_dir . $sshot_prefix . ".jpg";
  $sshot_thumb = $sshot_dir . $sshot_prefix . "_thumb.jpg";

  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->set_var("I_ID_MATCH", $id_match);
  $content_tpl->set_var("I_NUM_MAP", $num_map);
  $content_tpl->set_var("I_MAP_NAME", htmlspecialchars($maps_row['map']));
  $content_tpl->set_var("I_SCREENSHOT", htmlspecialchars($sshot));
  $content_tpl->parse("H_CROP1", "B_CROP1");
}
else
{
  $content_tpl->parse("H_WARNING_ACCESS", "B_WARNING_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
