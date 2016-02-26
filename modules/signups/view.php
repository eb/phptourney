<?php

$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_SIGNUP", "H_VIEW_NO_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_VIEW_SIGNUP", "H_VIEW_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYERS", "H_NO_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_PLAYER_COL1", "H_PLAYER_COL1");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS_COL1", "H_PLAYERS_COL1");
$content_tpl->set_block("F_CONTENT", "B_PLAYER_COL2", "H_PLAYER_COL2");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS_COL2", "H_PLAYERS_COL2");
$content_tpl->set_block("F_CONTENT", "B_NO_COUNTRIES", "H_NO_COUNTRIES");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY", "H_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_PLAYERS", "H_OVERVIEW_PLAYERS");

if ($season['status'] == "signups")
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_VIEW_SIGNUP", "B_VIEW_SIGNUP");
}
else
{
  $content_tpl->parse("H_VIEW_NO_SIGNUP", "B_VIEW_NO_SIGNUP");
}

$users_ref = dbQuery("SELECT U.* " .
		      "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
		      "WHERE SU.`id_season` = {$season['id']} " .
		      "AND SU.`usertype_player` = 1 " .
		      "AND SU.`id_user` = U.`id` " .
		      "ORDER BY SU.`submitted` ASC");
if (dbNumRows($users_ref) <= 0)
{
  $content_tpl->parse("H_NO_PLAYERS", "B_NO_PLAYERS");
  $content_tpl->parse("H_NO_COUNTRIES", "B_NO_COUNTRIES");
}
else
{
  $player_counter = 0;
  $players_per_country = array();
  while ($users_row = dbFetch($users_ref))
  {
    $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
    $content_tpl->set_var("I_ID_USER", $users_row['id']);
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));

    $countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` WHERE `id` = {$users_row['id_country']}");
    $countries_row = dbFetch($countries_ref);
    if (isset($players_per_country[$countries_row['id']]))
    {
      $players_per_country[$countries_row['id']]++;
    }
    else
    {
      $players_per_country[$countries_row['id']] = 1;
    }
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($countries_row['abbreviation']));
    if ($player_counter % 2 == 1)
    {
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_PLAYER_COL1", "B_PLAYER_COL1", true);
    }
    elseif ($player_counter % 2 == 0)
    {
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_PLAYER_COL2", "B_PLAYER_COL2", true);
    }
  }
  $content_tpl->parse("H_PLAYERS_COL1", "B_PLAYERS_COL1");
  $content_tpl->parse("H_PLAYERS_COL2", "B_PLAYERS_COL2");
  $content_tpl->parse("H_PLAYERS_COL3", "B_PLAYERS_COL3");

  arsort($players_per_country);
  $id_countries = array_keys($players_per_country);
  foreach($id_countries as $id_country) {
    $countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` WHERE `id` = '$id_country'");
    $countries_row = dbFetch($countries_ref);
    $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($countries_row['abbreviation']));
    $content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));
    $content_tpl->set_var("I_NUMBER_PLAYERS", $players_per_country[$id_country]);
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_COUNTRY", "B_COUNTRY", true);
  }
}
$content_tpl->parse("H_OVERVIEW_PLAYERS", "B_OVERVIEW_PLAYERS");
$content_tpl->set_var("I_USERNAME", "");

?>
