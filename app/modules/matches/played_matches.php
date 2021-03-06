<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_PLAYED_MATCHES", "H_NO_PLAYED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_PLAYED_MATCH", "H_PLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_PLAYED_MATCHES", "H_PLAYED_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_PLAYED_MATCHES", "H_OVERVIEW_PLAYED_MATCHES");

// Access for players only
if ($user['usertype_player'])
{
  $matches_ref = dbQuery("SELECT * FROM `matches` " .
			  "WHERE `id_season` = {$season['id']} " .
			  "AND (`id_player1` = {$user['uid']} OR `id_player2` = {$user['uid']}) " .
			  "AND `confirmed` <> '0000-00-00 00:00:00' " .
			  "ORDER BY `confirmed` DESC");
  if (dbNumRows($matches_ref) <= 0)
  {
    $content_tpl->parse("H_NO_PLAYED_MATCHES", "B_NO_PLAYED_MATCHES");
  }
  else
  {
    $match_counter = 0;
    while ($matches_row = dbFetch($matches_ref))
    {
      $content_tpl->set_var("I_MATCH_COUNTER", ++$match_counter);
      // Match
      if ($matches_row['id_player1'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player1']}");
	$users_row = dbFetch($users_ref);
	$player1 = $users_row['username'];
      }
      else
      {
	$player1 = "-";
      }
      $content_tpl->set_var("I_PLAYER1", htmlspecialchars($player1));

      if ($matches_row['id_player2'] > 0)
      {
	$users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$matches_row['id_player2']}");
	$users_row = dbFetch($users_ref);
	$player2 = $users_row['username'];
      }
      else
      {
	$player2 = "-";
      }
      $content_tpl->set_var("I_PLAYER2", htmlspecialchars($player2));

      // Outcome
      $outcome = "{$matches_row['score_p1']} - {$matches_row['score_p2']}";

      $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
      $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
      $content_tpl->set_var("I_ROUND", $matches_row['round']);
      $content_tpl->set_var("I_MATCH", $matches_row['match']);
      $content_tpl->set_var("I_OUTCOME", $outcome);
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_PLAYED_MATCH", "B_PLAYED_MATCH", true);
    }
    $content_tpl->parse("H_PLAYED_MATCHES", "B_PLAYED_MATCHES");
  }
  $content_tpl->parse("H_OVERVIEW_PLAYED_MATCHES", "B_OVERVIEW_PLAYED_MATCHES");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
