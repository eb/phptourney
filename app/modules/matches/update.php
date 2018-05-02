<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MATCH_CONFIRMED", "H_MESSAGE_MATCH_CONFIRMED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_REPORT", "H_WARNING_NO_REPORT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_EDIT", "H_WARNING_EDIT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_REPORT", "H_WARNING_REPORT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_MAP", "H_WARNING_MAP");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SCREENSHOT", "H_WARNING_SCREENSHOT");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_CROP", "H_CROP");
$content_tpl->set_block("F_CONTENT", "B_MAIL_SUBJECT", "H_MAIL_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_MAIL_DEADLINE", "H_MAIL_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_MAIL_BODY", "H_MAIL_BODY");

$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `matches` WHERE `id` = $id_match");
if ($matches_row = dbFetch($matches_ref))
{
  if ($user['usertype_admin'])
  {
    // Screenshot filenames
    $sshot_dir = "data/screenshots/{$season['id']}/";
    for ($i = 1; $i <= 5; $i++)
    {
      $dst_filename['m' . $i] = $sshot_dir .
	"{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m$i";
    }

    if (isLastMatch($matches_row))
    {

      // Not played
      if (isset($_REQUEST['not_played']) and $_REQUEST['not_played'] and $user['usertype_admin'])
      {
	$_REQUEST['comment_admin'] = str_replace(">", "&gt;", str_replace("<", "&lt;", $_REQUEST['comment_admin']));
	// Wo for player1
	if ($_REQUEST['not_played'] == "wo1")
	{
	  $wo = $matches_row['id_player1'];
	  $bye = 0;
	  $out = 0;
	}
	// Wo for player2
	elseif ($_REQUEST['not_played'] == "wo2")
	{
	  $wo = $matches_row['id_player2'];
	  $bye = 0;
	  $out = 0;
	}
	// Bye
	elseif ($_REQUEST['not_played'] == "bye")
	{
	  $wo = 0;
	  $bye = 1;
	  $out = 0;
	}
	// Out
	elseif ($_REQUEST['not_played'] == "both_out")
	{
	  $wo = 0;
	  $bye = 0;
	  $out = 1;
	}

	dbQuery("DELETE FROM `maps` WHERE `id_match` = {$matches_row['id']}");
        $comment_admin = dbEscape($_REQUEST['comment_admin']);
	dbQuery("UPDATE `matches` SET " .
		 "`wo` = $wo, `bye` = $bye, `out` = $out, " .
		 "`score_p1` = 0, `score_p2` = 0, " .
		 "`comment_admin` = '$comment_admin', " .
		 "`confirmed` = NOW(), `confirmer` = {$user['uid']} " . 
		 "WHERE `id` = {$matches_row['id']}");

	// Insert next matches
	if ($wo != 0 and $wo == $matches_row['id_player1'])
	{
	  $old_winner_match = getWinnerMatch($matches_row);
	  $new_winner_match = insertWinnerMatch($matches_row, $matches_row['id_player1']);
	  notifyPlayers($new_winner_match, $old_winner_match);
	  $old_loser_match = getLoserMatch($matches_row);
	  $new_loser_match = insertLoserMatch($matches_row, $matches_row['id_player2']);
	  notifyPlayers($new_loser_match, $old_loser_match);
	}
	elseif ($wo != 0 and $wo == $matches_row['id_player2'])
	{
	  $old_winner_match = getWinnerMatch($matches_row);
	  $new_winner_match = insertWinnerMatch($matches_row, $matches_row['id_player2']);
	  notifyPlayers($new_winner_match, $old_winner_match);
	  $old_loser_match = getLoserMatch($matches_row);
	  $new_loser_match = insertLoserMatch($matches_row, $matches_row['id_player1']);
	  notifyPlayers($new_loser_match, $old_loser_match);
	}
	elseif ($bye == 1)
	{
	  if ($matches_row['id_player1'] != 0)
	  {
	    $old_winner_match = getWinnerMatch($matches_row);
	    $new_winner_match = insertWinnerMatch($matches_row, $matches_row['id_player1']);
	    notifyPlayers($new_winner_match, $old_winner_match);
	  }
	  elseif ($matches_row['id_player2'] != 0)
	  {
	    $old_winner_match = getWinnerMatch($matches_row);
	    $new_winner_match = insertWinnerMatch($matches_row, $matches_row['id_player2']);
	    notifyPlayers($new_winner_match, $old_winner_match);
	  }
	  else
	  {
	    $old_winner_match = getWinnerMatch($matches_row);
	    $new_winner_match = insertWinnerMatch($matches_row, 0);
	    notifyPlayers($new_winner_match, $old_winner_match);
	  }
	  $old_loser_match = getLoserMatch($matches_row);
	  $new_loser_match = insertLoserMatch($matches_row, 0);
	  notifyPlayers($new_loser_match, $old_loser_match);
	}
	elseif ($out == 1)
	{
	  $new_winner_match = insertWinnerMatch($matches_row, 0);
	  $new_loser_match = insertLoserMatch($matches_row, 0);
	}

	// Tourney finished?
	if ($new_winner_match == NULL)
	{
	  dbQuery("UPDATE `seasons` SET `status` = 'finished' " .
		   "WHERE `id` = {$season['id']}");
	}

	$content_tpl->parse("H_MESSAGE_MATCH_CONFIRMED", "B_MESSAGE_MATCH_CONFIRMED");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");

	// Delete screenshots
	for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	{
	  if (file_exists($dst_filename['m' . $i] . ".jpg"))
	  {
	    unlink($dst_filename['m' . $i] . ".jpg");
	  }
	  if (file_exists($dst_filename['m' . $i] . "_thumb.jpg"))
	  {
	    unlink($dst_filename['m' . $i] . "_thumb.jpg");
	  }
	}
      }

      // Played
      else
      {
	$is_complete = 1;

	// Check maps
	$score_p1 = 0;
	$score_p2 = 0;
	for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	{
	  if (($_REQUEST['id_map' . $i] == "" or
		$_REQUEST['score_m' . $i . '_p1'] == "" or $_REQUEST['score_m' . $i . '_p2'] == "" or
		$_REQUEST['score_m' . $i . '_p1'] == $_REQUEST['score_m' . $i . '_p2']) and
	      $score_p1 < $matches_row['num_winmaps'] and $score_p2 < $matches_row['num_winmaps']) {
	    $is_complete = 0;
	    $content_tpl->parse("H_WARNING_MAP", "B_WARNING_MAP");
	  }
	  elseif ($_REQUEST['score_m' . $i . '_p1'] > $_REQUEST['score_m' . $i . '_p2'])
	  {
	    $score_p1++;
	  }
	  elseif ($_REQUEST['score_m' . $i . '_p1'] < $_REQUEST['score_m' . $i . '_p2'])
	  {
	    $score_p2++;
	  }
	}
	if ($score_p1 < $matches_row['num_winmaps'] and $score_p2 < $matches_row['num_winmaps'])
	{
	  $is_complete = 0;
	  $content_tpl->parse("H_WARNING_REPORT", "B_WARNING_REPORT");
	}

	if ($is_complete)
	{
	  // Check whether screenshot directory exists
	  if (!file_exists($sshot_dir))
	  {
	    mkdir($sshot_dir);
	    chmod($sshot_dir, 0777);
	  }

	  // Screenshots
	  for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	  {
	    $sshot['m' . $i] = false;
	    $src_file['m' . $i] = $_FILES['screenshot_m' . $i]['name'];
	    $tmp_file['m' . $i] = $_FILES['screenshot_m' . $i]['tmp_name'];
	    if (is_uploaded_file($tmp_file['m' . $i]))
	    {
	      $sshot['m' . $i] = true;
	      $src_filetype['m' . $i] = array_pop(explode(".", $src_file['m' . $i]));
	      if (file_exists($dst_filename['m' . $i] . ".jpg"))
	      {
		unlink($dst_filename['m' . $i] . ".jpg");
	      }
	      if (file_exists($dst_filename['m' . $i] . "_thumb.jpg"))
	      {
		unlink($dst_filename['m' . $i] . "_thumb.jpg");
	      }
	      move_uploaded_file($tmp_file['m' . $i], $dst_filename['m' . $i] . "_tmp.{$src_filetype['m' . $i]}");
	      chmod($dst_filename['m' . $i] . "_tmp.{$src_filetype['m' . $i]}", 0646);
	      if ($cfg['convert'] != "")
	      {
		`{$cfg['convert']} {$dst_filename['m' . $i]}_tmp.{$src_filetype['m' . $i]} {$dst_filename['m' . $i]}.jpg`;
		`{$cfg['convert']} -geometry 320 {$dst_filename['m' . $i]}_tmp.{$src_filetype['m' . $i]} {$dst_filename['m' . $i]}_thumb.jpg`;
	      }
	      if (!file_exists("{$dst_filename['m' . $i]}.jpg"))
	      {
		copy("{$dst_filename['m' . $i]}_tmp.{$src_filetype['m' . $i]}", "{$dst_filename['m' . $i]}.{$src_filetype['m' . $i]}");
	      }
	      if (!file_exists("{$dst_filename['m' . $i]}_thumb.jpg"))
	      {
		copy("{$dst_filename['m' . $i]}_tmp.{$src_filetype['m' . $i]}", "{$dst_filename['m' . $i]}_thumb.{$src_filetype['m' . $i]}");
	      }
	      unlink($dst_filename['m' . $i] . "_tmp.{$src_filetype['m' . $i]}");
	    }
	    elseif (file_exists($dst_filename['m' . $i] . ".jpg"))
	    {
	      $sshot['m' . $i] = true;
	    }
	  }

	  // Match
	  dbQuery("UPDATE `matches` SET " .
		   "`wo` = 0, " . 
		   "`out` = 0, " . 
		   "`score_p1` = $score_p1, " .
		   "`score_p2` = $score_p2, " .
		   "`comment_admin` = '', " .
		   "`confirmed` = NOW(), " . 
		   "`confirmer` = {$user['uid']} " . 
		   "WHERE `id` = {$matches_row['id']}");

	  // Maps
	  dbQuery("DELETE FROM `maps` " .
		   "WHERE `id_match` = {$matches_row['id']}");
	  for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	  {
	    if ($_REQUEST['id_map' . $i] != "")
	    {
	      if ($_REQUEST['score_m' . $i . '_p1'] == "")
	      {
		$_REQUEST['score_m' . $i . '_p1'] = 0;
	      }
	      if ($_REQUEST['score_m' . $i . '_p2'] == "")
	      {
		$_REQUEST['score_m' . $i . '_p2'] = 0;
	      }
	      $_REQUEST['comment_m' . $i . '_admin'] = str_replace(">", "&gt;", str_replace("<", "&lt;", $_REQUEST['comment_m' . $i . '_admin']));
	      $_REQUEST['comment_m' . $i . '_p1'] = str_replace(">", "&gt;", str_replace("<", "&lt;", $_REQUEST['comment_m' . $i . '_p1']));
	      $_REQUEST['comment_m' . $i . '_p2'] = str_replace(">", "&gt;", str_replace("<", "&lt;", $_REQUEST['comment_m' . $i . '_p2']));

              $id_map = intval($_REQUEST['id_map' . $i]);
              $score_p1 = intval($_REQUEST['score_m' . $i . '_p1']);
              $score_p2 = intval($_REQUEST['score_m' . $i . '_p2']);
              $comment_p1 = dbEscape($_REQUEST['comment_m' . $i . '_p1']);
              $comment_p2 = dbEscape($_REQUEST['comment_m' . $i . '_p2']);
              $comment_admin = dbEscape($_REQUEST['comment_m' . $i . '_admin']);
	      dbQuery("INSERT INTO `maps` " .
		       "(`id_match`,`id_map`,`score_p1`,`score_p2`,`comment_p1`,`comment_p2`,`comment_admin`,`num_map`) " .
		       "VALUES({$matches_row['id']},$id_map," .
		       "$score_p1,$score_p2," .
		       "'$comment_p1','$comment_p2'," .
		       "'$comment_admin',$i) ");
	    }
	  }

	  // Insert next matches
	  if ($score_p1 > $score_p2)
	  {
	    $id_winner = $matches_row['id_player1'];
	    $id_loser = $matches_row['id_player2'];
	  }
	  elseif ($score_p1 < $score_p2)
	  {
	    $id_winner = $matches_row['id_player2'];
	    $id_loser = $matches_row['id_player1'];
	  }
	  $old_winner_match = getWinnerMatch($matches_row);
	  $new_winner_match = insertWinnerMatch($matches_row, $id_winner);
	  notifyPlayers($new_winner_match, $old_winner_match);
	  $old_loser_match = getLoserMatch($matches_row);
	  $new_loser_match = insertLoserMatch($matches_row, $id_loser);
	  notifyPlayers($new_loser_match, $old_loser_match);

	  // Tourney finished?
	  if ($new_winner_match == NULL)
	  {
	    dbQuery("UPDATE `seasons` SET `status` = 'finished' " .
		     "WHERE `id` = {$season['id']}");
	  }

	  $content_tpl->parse("H_MESSAGE_MATCH_CONFIRMED", "B_MESSAGE_MATCH_CONFIRMED");
	  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");

	  // Cropping
	  if ($cfg['convert'] != "")
	  {
	    $content_tpl->set_var("I_BRACKET", htmlspecialchars($matches_row['bracket']));
	    $content_tpl->set_var("I_ROUND", $matches_row['round']);
	    $content_tpl->set_var("I_MATCH", $matches_row['match']);
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	    for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	    {
	      if (isset($sshot['m' . $i]))
	      {
		$content_tpl->parse("H_CROP", "B_CROP");
	      }
	    }
	  }

	  // Delete screenshots
	  for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	  {
	    if ($_REQUEST['id_map' . $i] == "" and file_exists($dst_filename['m' . $i] . ".jpg"))
	    {
	      unlink($dst_filename['m' . $i] . ".jpg");
	    }
	    if ($_REQUEST['id_map' . $i] == "" and file_exists($dst_filename['m' . $i] . "_thumb.jpg"))
	    {
	      unlink($dst_filename['m' . $i] . "_thumb.jpg");
	    }
	  }
	}

	if (!$is_complete)
	{
	  $content_tpl->parse("H_WARNING", "B_WARNING");
	  $content_tpl->parse("H_BACK", "B_BACK");
	}
      }
    }
    else
    {
      $content_tpl->parse("H_WARNING_EDIT", "B_WARNING_EDIT");
      $content_tpl->parse("H_WARNING", "B_WARNING");
    }
  }
  else
  {
    $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_REPORT", "B_WARNING_NO_REPORT");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

// Notifies the players per mail, when a new match is avaiable
function notifyPlayers($new_match, $old_match) {
  global $cfg;
  global $content_tpl;
  global $season;

  if ($new_match['bracket'] == "gf" and $new_match['match'] == 2)
  {
    return;
  }

  if ($old_match != NULL and $new_match != NULL)
  {
    if ($new_match['id_player1'] > 0 and $new_match['id_player2'] > 0)
    {
      if ($old_match['id_player1'] != $new_match['id_player1'] or
	  $old_match['id_player2'] != $new_match['id_player2']) {
	$users_ref1 = dbQuery("SELECT * FROM `users` " .
			       "WHERE `id` = {$new_match['id_player1']}");
	$users_row1 = dbFetch($users_ref1);
	$users_ref2 = dbQuery("SELECT * FROM `users` " .
			       "WHERE `id` = {$new_match['id_player2']}");
	$users_row2 = dbFetch($users_ref2);

	// Mail to player1
	if ($new_match['id_player1'] > 0 and $new_match['id_player2'] > 0)
	{
		if ($users_row1['notify'] == 1)
		{
			$to = $users_row1['email'];

			// Subject
			$content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
			$content_tpl->set_var("I_SEASON_NAME", $season['name']);
			$content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
			$subject = $content_tpl->get("MAIL_SUBJECT");

			// Message
			$content_tpl->set_var("I_PLAYER", $users_row1['username']);
			$content_tpl->set_var("I_OPPONENT", $users_row2['username']);
			$content_tpl->set_var("I_IRC_CHANNEL", $users_row2['irc_channel']);
			$content_tpl->set_var("I_BRACKET", $new_match['bracket']);
			$content_tpl->set_var("I_ROUND", $new_match['round']);
			$content_tpl->set_var("I_MATCH", $new_match['match']);
			$content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$season['id']}");
			$deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
				"WHERE `id_season` = {$season['id']} " .
				"AND `round` = '{$new_match['bracket']}{$new_match['round']}'");
			if ($deadlines_row = dbFetch($deadlines_ref))
			{
				$content_tpl->set_var("I_DEADLINE", $deadlines_row['deadline']);
				$content_tpl->parse("H_MAIL_DEADLINE", "B_MAIL_DEADLINE");
			}
			$content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
			$message = $content_tpl->get("MAIL_BODY");

			sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path'], $cfg['mail_bcc_address']);
		}

		// Mail to player2
		if ($users_row2['notify'] == 1)
		{
			$to = $users_row2['email'];

			// Subject
			$content_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
			$content_tpl->set_var("I_SEASON_NAME", $season['name']);
			$content_tpl->parse("MAIL_SUBJECT", "B_MAIL_SUBJECT");
			$subject = $content_tpl->get("MAIL_SUBJECT");

			// Message
			$content_tpl->set_var("I_PLAYER", $users_row2['username']);
			$content_tpl->set_var("I_OPPONENT", $users_row1['username']);
			$content_tpl->set_var("I_IRC_CHANNEL", $users_row1['irc_channel']);
			$content_tpl->set_var("I_BRACKET", $new_match['bracket']);
			$content_tpl->set_var("I_ROUND", $new_match['round']);
			$content_tpl->set_var("I_MATCH", $new_match['match']);
			$content_tpl->set_var("I_URL", $cfg['host'] . $cfg['path'] . "index.php?sid={$season['id']}");
			$deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
				"WHERE `id_season` = {$season['id']} " .
				"AND `round` = '{$new_match['bracket']}{$new_match['round']}'");
			if ($deadlines_row = dbFetch($deadlines_ref))
			{
				$content_tpl->set_var("I_DEADLINE", $deadlines_row['deadline']);
				$content_tpl->parse("H_MAIL_DEADLINE", "B_MAIL_DEADLINE");
			}
			$content_tpl->parse("MAIL_BODY", "B_MAIL_BODY");
			$message = $content_tpl->get("MAIL_BODY");

			sendMail($to, $subject, $message, $cfg['mail_from_address'], $cfg['mail_reply_to_address'], $cfg['mail_return_path'], $cfg['mail_bcc_address']);
		}
	}
      }
    }
  }
}

?>
