<?php

$content_tpl->set_block("F_CONTENT", "B_NO_PLAYERS", "H_NO_PLAYERS");
$content_tpl->set_block("F_CONTENT", "B_PLAYER_COL1", "H_PLAYER_COL1");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS_COL1", "H_PLAYERS_COL1");
$content_tpl->set_block("F_CONTENT", "B_PLAYER_COL2", "H_PLAYER_COL2");
$content_tpl->set_block("F_CONTENT", "B_PLAYERS_COL2", "H_PLAYERS_COL2");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_PLAYERS", "H_OVERVIEW_PLAYERS");

$id_country = intval($_REQUEST['opt']);
$countries_ref = dbQuery("SELECT * FROM `countries` WHERE `id` = $id_country");
$countries_row = dbFetch($countries_ref);
$content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));

$users_ref = dbQuery("SELECT U.* " .
		      "FROM `users` U, `season_users` SU " .
		      "WHERE SU.`id_user` = U.`id` AND SU.`id_season` = {$season['id']} AND U.`id_country` = $id_country " .
		      "AND SU.`usertype_player` = 1 AND SU.`rejected` = 0 ORDER BY SU.`submitted` ASC");
if (dbNumRows($users_ref) <= 0)
{
  $content_tpl->parse("H_NO_PLAYERS", "B_NO_PLAYERS");
}
else
{
  $player_counter = 0;
  while ($users_row = dbFetch($users_ref))
  {
    $content_tpl->set_var("I_PLAYER_COUNTER", ++$player_counter);
    $content_tpl->set_var("I_ID_USER", $users_row['id']);
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));

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
}
$content_tpl->parse("H_OVERVIEW_PLAYERS", "B_OVERVIEW_PLAYERS");
$content_tpl->set_var("I_USERNAME", "");

?>
