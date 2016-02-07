<?php

################################################################################
#
# $Id: create.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_BRACKET_CREATED", "H_MESSAGE_BRACKET_CREATED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BRACKET_CREATED", "H_WARNING_BRACKET_CREATED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['single_elimination'] == "")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_SYSTEM", "B_WARNING_TOURNEY_SYSTEM");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "bracket")
  {
    $content_tpl->parse("H_WARNING_BRACKET_CREATED", "B_WARNING_BRACKET_CREATED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_RUNNING", "B_WARNING_TOURNEY_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    // process seeding-groups
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `id_season` = {$season['id']} " .
				 "AND `rejected` = 0 " .
				 "AND `seedgroup` > 0 " .
				 "AND `usertype_player` = 1 " .
				 "ORDER BY `seedgroup` ASC, `submitted` ASC");
    $seeding_groups = array();
    while ($season_users_row = dbFetch($season_users_ref))
    {
      if (!isset($seeding_groups[$season_users_row['seedgroup']]))
      {
	$seeding_groups[$season_users_row['seedgroup']] = array();
      }
      array_push($seeding_groups[$season_users_row['seedgroup']], $season_users_row['id_user']);
    }

    $seedlevel = 1;
    ksort($seeding_groups);

    foreach($seeding_groups as $seeding_group) {
      $sg_size = count($seeding_group);
      for ($i = 0; $i < $sg_size; $i++)
      {
	srand((float)microtime() * 10000000);
	$rand_key = array_rand($seeding_group);
	$uid = $seeding_group[$rand_key];
	unset($seeding_group[$rand_key]);
	dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `seedlevel` = $seedlevel " .
		 "WHERE `id_user` = $uid AND `id_season` = {$season['id']}");
	$seedlevel++;
      }
    }
    // seed unseeded players
    $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				 "WHERE `id_season` = {$season['id']} " .
				 "AND `rejected` = 0 " .
				 "AND `seedgroup` = 0 " .
				 "AND `usertype_player` = 1 " .
				 "ORDER BY `submitted` ASC");
    while ($season_users_row = dbFetch($season_users_ref))
    {
      dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `seedlevel` = $seedlevel " .
	       "WHERE `id` = {$season_users_row['id']} AND `id_season` = {$season['id']}");
      $seedlevel++;
    }

    // set all players who signed up too late to rejected
    if ($season['qualification'] == 1)
    {
      dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `rejected` = 1 " .
	       "WHERE `id_season` = {$season['id']} " .
	       "AND `rejected` = 0 " .
	       "AND `seedlevel` > {$season['single_elimination']} + {$season['single_elimination']} / 2 " .
	       "AND `usertype_player` = 1");

      // unset qualification if no qualification games were created
      $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_season` = {$season['id']} " .
				   "AND `seedlevel` > {$season['single_elimination']} " .
				   "AND `usertype_player` = 1");
      if (dbNumRows($season_users_ref) == 0)
      {
	dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `qualification` = 0 " .
		 "WHERE `id` = {$season['id']}");
      }
    }
    else
    {
      dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `rejected` = 1 " .
	       "WHERE `id_season` = {$season['id']} " .
	       "AND `rejected` = 0 " .
	       "AND `seedlevel` > {$season['single_elimination']} " .
	       "AND `usertype_player` = 1");
    }

    // set up the first round of matches
    $num_winmaps = $season['winmaps'];
    $num_matches = $season['single_elimination'] / 2;
    $matches = getSeeding($num_matches);

    $counter = 1;
    foreach($matches as $match) {
      // season_users-query [player1]
      $seedlevel = $counter;
      $users_ref = dbQuery("SELECT U.`id` " .
			    "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}season_users` SU " .
			    "WHERE SU.`id_season` = {$season['id']} " .
			    "AND SU.`usertype_player` = 1 " .
			    "AND SU.`seedlevel` = $seedlevel " .
			    "AND U.`id` = SU.`id_user`");
      if ($users_row = dbFetch($users_ref))
      {
	$id_player1 = $users_row['id'];
      }

      // season_users-query [player2]
      $seedlevel = $season['single_elimination'] - $counter + 1;
      $users_ref = dbQuery("SELECT U.`id` " .
			    "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}season_users` SU " .
			    "WHERE SU.`id_season` = {$season['id']} " .
			    "AND SU.`usertype_player` = 1 " .
			    "AND SU.`seedlevel` = $seedlevel " .
			    "AND U.`id` = SU.`id_user`");
      if ($users_row = dbFetch($users_ref))
      {
	$id_player2 = $users_row['id'];
      }

      $qualification = 0;
      if ($season['qualification'] == 1 and isset($id_player2))
      {
	// season_users-query [player3]
	$seedlevel = $season['single_elimination'] + $counter;
	$users_ref = dbQuery("SELECT U.`id` " .
			      "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}season_users` SU " .
			      "WHERE SU.`id_season` = {$season['id']} " .
			      "AND SU.`usertype_player` = 1 " .
			      "AND SU.`seedlevel` = $seedlevel " .
			      "AND U.`id` = SU.`id_user`");
	if ($users_row = dbFetch($users_ref))
	{
	  $id_player3 = $users_row['id'];
	}
	if (isset($id_player1) and isset($id_player2) and isset($id_player3))
	{
	  $qualification = 1;
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` (`id_season`, `bracket`, `round`, `match`, `id_player1`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'wb', 1, $match, $id_player1, $num_winmaps)");
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` (`id_season`, `bracket`, `round`, `match`, `id_player1`, `id_player2`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'q', 1, $match, $id_player2, $id_player3, $num_winmaps)");
	}
      }

      if (!$qualification)
      {
	if (isset($id_player1) and isset($id_player2))
	{
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` (`id_season`, `bracket`, `round`, `match`, `id_player1`, `id_player2`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'wb', 1, $match, $id_player1, $id_player2, $num_winmaps)");
	}
	elseif (isset($id_player1) and !isset($id_player2))
	{
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		   "(`id_season`, `bracket`, `round`, `match`, `id_player1`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'wb', 1, $match, $id_player1, $num_winmaps)");
	}
	elseif (!isset($id_player1) and isset($id_player2))
	{
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		   "(`id_season`, `bracket`, `round`, `match`, `id_player2`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'wb', 1, $match, $id_player2, $num_winmaps)");
	}
	else
	{
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		   "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'wb', 1, $match, $num_winmaps)");
	}
      }
      unset($id_player1);
      unset($id_player2);
      unset($id_player3);
      $counter++;
    }
    
    // set up the remaining rounds of matches
    for ($i = 2; $i <= getNumWBRounds($season); $i++)
    {
      $num_matches = $season['single_elimination'] / pow(2, $i);
      for ($j = 1; $j <= $num_matches; $j++)
      {
	dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		 "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
		 "VALUES ({$season['id']}, 'wb', $i, $j, $num_winmaps)");
      }
    }
    if ($season['double_elimination'] != "")
    {
      for ($i = 1; $i <= getNumLBRounds($season) / 2; $i++)
      {
	$num_matches = $season['double_elimination'] / pow(2, $i + 1);
	for ($j = 1; $j <= $num_matches; $j++)
	{
	  $num_round = $i * 2 - 1;
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		   "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'lb', $num_round, $j, $num_winmaps)");
	  $num_round = $i * 2;
	  dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
		   "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
		   "VALUES ({$season['id']}, 'lb', $num_round, $j, $num_winmaps)");
	}
      }
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
	       "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
	       "VALUES ({$season['id']}, 'gf', 1, 1, $num_winmaps)");
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}matches` " .
	       "(`id_season`, `bracket`, `round`, `match`, `num_winmaps`) " .
	       "VALUES ({$season['id']}, 'gf', 1, 2, $num_winmaps)");
    }

    // set season-status to bracket
    dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `status` = 'bracket' WHERE `id` = {$season['id']}");
    $content_tpl->parse("H_MESSAGE_BRACKET_CREATED", "B_MESSAGE_BRACKET_CREATED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
