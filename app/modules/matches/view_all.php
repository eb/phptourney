<?php

$content_tpl->set_block("F_CONTENT", "B_NO_MATCHES", "H_NO_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_MATCHES", "H_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_VIEW_ALL_MATCHES", "H_VIEW_ALL_MATCHES");

$matches_ref = dbQuery("SELECT * FROM `matches` " .
			"WHERE `id_season` = {$season['id']} " .
			"AND `confirmed` <> '0000-00-00 00:00:00' " .
			"AND `wo` = 0 " .
			"AND `bye` = 0 " .
			"AND `out` = 0 " .
			"ORDER BY `confirmed` DESC");
if (dbNumRows($matches_ref) <= 0)
{
  $content_tpl->parse("H_NO_MATCHES", "B_NO_MATCHES");
}
else
{
  $match_counter = 0;
  while ($matches_row = dbFetch($matches_ref))
  {
    $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
    $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
    $content_tpl->set_var("I_ROUND", $matches_row['round']);
    $content_tpl->set_var("I_MATCH", $matches_row['match']);
    if ($matches_row['id_player1'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player1']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER1", htmlspecialchars($users_row['username']));
    }
    else
    {
      $content_tpl->set_var("I_PLAYER1", "-");
    }
    if ($matches_row['id_player2'] > 0)
    {
      $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player2']}");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_PLAYER2", htmlspecialchars($users_row['username']));
    }
    else
    {
      $content_tpl->set_var("I_PLAYER2", "-");
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_MATCH", "B_MATCH", true);
  }
  $content_tpl->parse("H_MATCHES", "B_MATCHES");
}
$content_tpl->parse("H_VIEW_ALL_MATCHES", "B_VIEW_ALL_MATCHES");

?>
