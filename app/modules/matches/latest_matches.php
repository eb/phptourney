<?php

$content_tpl->set_block("F_CONTENT", "B_NO_LATEST_MATCHES", "H_NO_LATEST_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_LATEST_MATCH", "H_LATEST_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LATEST_MATCHES", "H_LATEST_MATCHES");

$matches_ref = dbQuery("SELECT * FROM `matches` " .
			"WHERE `id_season` = {$season['id']} " .
			"AND `confirmed` <> '0000-00-00 00:00:00' " .
			"AND `bye` = 0 AND `wo` = 0 AND `out` = 0 " .
			"ORDER BY `confirmed` DESC " .
			"LIMIT 0, 10");
if (dbNumRows($matches_ref) <= 0)
{
  $content_tpl->parse("H_NO_LATEST_MATCHES", "B_NO_LATEST_MATCHES");
}
else
{
  $content_tpl->set_var("H_LATEST_MATCH", "");
  while ($matches_row = dbFetch($matches_ref))
  {
    $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player1']}");
    $users_row = dbFetch($users_ref);
    $player1 = $users_row['username'];

    $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player2']}");
    $users_row = dbFetch($users_ref);
    $player2 = $users_row['username'];

    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_PLAYER1", htmlspecialchars($player1));
    $content_tpl->set_var("I_PLAYER2", htmlspecialchars($player2));
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_LATEST_MATCH", "B_LATEST_MATCH", true);
  }
}
$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_LATEST_MATCHES", "B_LATEST_MATCHES");

?>
