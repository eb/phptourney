<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_NEXT_MATCH", "H_NO_NEXT_MATCH");
$content_tpl->set_block("F_CONTENT", "B_MARK_PLAYER1", "H_MARK_PLAYER1");
$content_tpl->set_block("F_CONTENT", "B_MARK_PLAYER2", "H_MARK_PLAYER2");
$content_tpl->set_block("F_CONTENT", "B_DEADLINE", "H_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_NEXT_MATCH", "H_NEXT_MATCH");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NEXT_MATCH", "H_VIEW_NEXT_MATCH");

// Access for players only
if ($user['usertype_player'])
{
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` " .
			  "WHERE `submitted` = '0000-00-00 00:00:00' " .
			  "AND `id_season` = {$season['id']} " .
			  "AND (`id_player1` = {$user['uid']} OR `id_player2` = {$user['uid']})");
  if ($matches_row = dbFetch($matches_ref))
  {
    if ($matches_row['id_player1'] != 0 and $matches_row['id_player2'] != 0)
    {
      $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
      $content_tpl->set_var("I_ROUND", $matches_row['round']);
      $content_tpl->set_var("I_MATCH", $matches_row['match']);

      $season_users_ref = dbQuery("SELECT SU.`seedlevel`, U.`username` " .
				   "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
				   "WHERE U.`id` = {$matches_row['id_player1']} " .
				   "AND U.`id` = SU.`id_user` AND `id_season` = {$season['id']}");
      $season_users_row = dbFetch($season_users_ref);
      $username_p1 = $season_users_row['username'];
      $seed_p1 = $season_users_row['seedlevel'];

      $season_users_ref = dbQuery("SELECT SU.`seedlevel`, U.`username` " .
				   "FROM `{$cfg['db_table_prefix']}season_users` SU, `{$cfg['db_table_prefix']}users` U " .
				   "WHERE U.`id` = {$matches_row['id_player2']} " .
				   "AND U.`id` = SU.`id_user` AND `id_season` = {$season['id']}");
      $season_users_row = dbFetch($season_users_ref);
      $username_p2 = $season_users_row['username'];
      $seed_p2 = $season_users_row['seedlevel'];

      if ($seed_p1 < $seed_p2)
      {
	$content_tpl->parse("H_MARK_PLAYER1", "B_MARK_PLAYER1");
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($username_p1));
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($username_p2));
      }
      elseif ($seed_p1 > $seed_p2)
      {
	$content_tpl->parse("H_MARK_PLAYER2", "B_MARK_PLAYER2");
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($username_p1));
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($username_p2));
      }

      $users_ref1 = dbQuery("SELECT U.*, C.`abbreviation` " .
			     "FROM `{$cfg['db_table_prefix']}users` U " .
			     "LEFT JOIN `{$cfg['db_table_prefix']}countries` C " .
			     "ON U.`id_country` = C.`id` " .
			     "WHERE U.`id` = {$matches_row['id_player1']}");
      $users_row1 = dbFetch($users_ref1);
      $content_tpl->set_var("I_ID_PLAYER1", $matches_row['id_player1']);
      $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER1", htmlspecialchars($users_row1['abbreviation']));

      $users_ref2 = dbQuery("SELECT U.*, C.`abbreviation` " .
			     "FROM `{$cfg['db_table_prefix']}users` U " .
			     "LEFT JOIN `{$cfg['db_table_prefix']}countries` C " .
			     "ON U.`id_country` = C.`id` " .
			     "WHERE U.`id` = {$matches_row['id_player2']}");
      $users_row2 = dbFetch($users_ref2);
      $content_tpl->set_var("I_ID_PLAYER2", $matches_row['id_player2']);
      $content_tpl->set_var("I_COUNTRY_ABBREVIATION_PLAYER2", htmlspecialchars($users_row2['abbreviation']));

      $round_pre = $matches_row['round'] - 1;
      $round_post = $matches_row['round'];
      $deadlines_ref1 = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` WHERE `id_season` = {$season['id']} " .
				 "AND `round` = '{$matches_row['bracket']}$round_pre'");
      $deadlines_ref2 = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` WHERE `id_season` = {$season['id']} " .
				 "AND `round` = '{$matches_row['bracket']}$round_post'");
      if (dbNumRows($deadlines_ref1) == 1 and dbNumRows($deadlines_ref2) == 1)
      {
	$deadlines_row1 = dbFetch($deadlines_ref1);
	$deadlines_row2 = dbFetch($deadlines_ref2);
	$deadline_pre = $deadlines_row1['deadline'];
	preg_match("/(\d\d\d\d)-\d\d-\d\d/", $deadline_pre, $matches);
	$year = $matches[1];
	preg_match("/\d\d\d\d-(\d\d)-\d\d/", $deadline_pre, $matches);
	$month = $matches[1];
	preg_match("/\d\d\d\d-\d\d-(\d\d)/", $deadline_pre, $matches);
	$day = $matches[1];
	$deadline_pre = date("Y-m-d", gmmktime(0, 0, 0, $month, $day + 1, $year));
	$deadline_post = $deadlines_row2['deadline'];
	$content_tpl->set_var("I_DEADLINE_PRE", htmlspecialchars($deadline_pre));
	$content_tpl->set_var("I_DEADLINE_POST", htmlspecialchars($deadline_post));
	$content_tpl->parse("H_DEADLINE", "B_DEADLINE");
      }
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
      $content_tpl->parse("H_NEXT_MATCH", "B_NEXT_MATCH");
    }
    else
    {
      $content_tpl->parse("H_NO_NEXT_MATCH", "B_NO_NEXT_MATCH");
    }
  }
  else
  {
    $content_tpl->parse("H_NO_NEXT_MATCH", "B_NO_NEXT_MATCH");
  }
  $content_tpl->parse("H_VIEW_NEXT_MATCH", "B_VIEW_NEXT_MATCH");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
