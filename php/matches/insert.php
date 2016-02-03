<?php

################################################################################
#
# $Id: insert.php,v 1.4 2006/05/01 14:55:10 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MATCH_REPORTED", "H_MESSAGE_MATCH_REPORTED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_COMMENT_ADDED", "H_MESSAGE_COMMENT_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PREV_MATCHES", "H_WARNING_PREV_MATCHES");
$content_tpl->set_block("F_CONTENT", "B_WARNING_REPORT", "H_WARNING_REPORT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_MAP", "H_WARNING_MAP");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_CROP", "H_CROP");

// matches-query
$id_match = intval($_REQUEST['opt']);
$matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = $id_match");
$matches_row = dbFetch($matches_ref);

// access for admins
// access for players when it is their own match
if ($user['usertype_admin'] or
    $user['uid'] == $matches_row['id_player1'] or $user['uid'] == $matches_row['id_player2']) {

  // check if the previous matches are played already
  $matchkey = $matches_row['bracket'] . '-' . $matches_row['round'] . '-' . $matches_row['match'];
  $prev_match_player1 = "";
  $prev_match_player2 = "";
  if (isset($matches[$matchkey]['prev_match_player1']))
  {
    $prev_match_player1 = $matches[$matchkey]['prev_match_player1'];
  }
  if (isset($matches[$matchkey]['prev_match_player2']))
  {
    $prev_match_player2 = $matches[$matchkey]['prev_match_player2'];
  }
  if (($prev_match_player1 == "" or $matches[$prev_match_player1]['confirmed'] != "0000-00-00 00:00:00") and
      ($prev_match_player2 == "" or $matches[$prev_match_player2]['confirmed'] != "0000-00-00 00:00:00"))
  {
    // screenshot filenames
    $sshot_dir = "data/screenshots/{$_REQUEST['sid']}/";
    for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
    {
      $dst_filename['m' . $i] = $sshot_dir .
	"{$_REQUEST['sid']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m$i";
    }

    if ($matches_row['submitted'] == "0000-00-00 00:00:00")
    {
      ////////////////////////////////////////////////////////////////////////////////
      // not played
      ////////////////////////////////////////////////////////////////////////////////

      if (isset($_REQUEST['not_played']) and $_REQUEST['not_played'] and $user['usertype_admin'])
      {
	$_REQUEST['comment_admin'] = str_replace(">", "&gt;", str_replace("<", "&lt;", $_REQUEST['comment_admin']));
	// wo for player1
	if ($_REQUEST['not_played'] == "wo1")
	{
	  $wo = $matches_row['id_player1'];
	  $bye = 0;
	  $out = 0;
	}
	// wo for player2
	elseif ($_REQUEST['not_played'] == "wo2")
	{
	  $wo = $matches_row['id_player2'];
	  $bye = 0;
	  $out = 0;
	}
	// bye
	elseif ($_REQUEST['not_played'] == "bye")
	{
	  $wo = 0;
	  $bye = 1;
	  $out = 0;
	}
	// out
	elseif ($_REQUEST['not_played'] == "both_out")
	{
	  $wo = 0;
	  $bye = 0;
	  $out = 1;
	}

	dbQuery("DELETE FROM `{$cfg['db_table_prefix']}maps` WHERE `id_match` = {$matches_row['id']}");
        $comment_admin = dbEscape($_REQUEST['comment_admin']);
	dbQuery("UPDATE `{$cfg['db_table_prefix']}matches` SET " .
		 "`wo` = $wo, `bye` = $bye, `out` = $out, " .
		 "`score_p1` = 0, `score_p2` = 0, " .
		 "`comment_admin` = '$comment_admin', " .
		 "`submitted` = NOW(), `submitter` = {$user['uid']} " . 
		 "WHERE `id` = {$matches_row['id']}");

	$content_tpl->parse("H_MESSAGE_MATCH_REPORTED", "B_MESSAGE_MATCH_REPORTED");
	$content_tpl->parse("H_MESSAGE", "B_MESSAGE");

	// cropping
	if ($cfg['convert'] != "")
	{
	  $content_tpl->set_var("I_BRACKET", $matches_row['bracket']);
	  $content_tpl->set_var("I_ROUND", $matches_row['round']);
	  $content_tpl->set_var("I_MATCH", $matches_row['match']);
	  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	  $content_tpl->set_var("I_ID_MATCH", $matches_row['id']);
	  for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	  {
	    if (isset($sshot['m' . $i]))
	    {
	      $content_tpl->parse("H_CROP", "B_CROP");
	    }
	  }
	}

	// delete screenshots
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

      ////////////////////////////////////////////////////////////////////////////////
      // played
      ////////////////////////////////////////////////////////////////////////////////

      else
      {
	$is_complete = 1;

	// check maps
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
	  else
	  {
	    if ($_REQUEST['score_m' . $i . '_p1'] > $_REQUEST['score_m' . $i . '_p2'])
	    {
	      $score_p1++;
	    }
	    elseif ($_REQUEST['score_m' . $i . '_p1'] < $_REQUEST['score_m' . $i . '_p2'])
	    {
	      $score_p2++;
	    }

	    // check screenshots
	    $src_file['m' . $i] = $_FILES['screenshot_m' . $i]['name'];
	    $src_filetype['m' . $i] = strtolower(array_pop(explode(".", $src_file['m' . $i])));
	    $tmp_file['m' . $i] = $_FILES['screenshot_m' . $i]['tmp_name'];

	    if (!$user['usertype_admin'] and $_REQUEST['id_map' . $i] != "" and
		(!is_uploaded_file($tmp_file['m' . $i]) or
		  $cfg['convert'] != "" and $src_filetype['m' . $i] != "jpg" and $src_filetype['m' . $i] != "tga" and
		  $src_filetype['m' . $i] != "gif" and $src_filetype['m' . $i] != "pcx" and $src_filetype['m' . $i] != "png" or
		  $cfg['convert'] == "" and $src_filetype['m' . $i] != "jpg")) {
	      $is_complete = 0;
	      $content_tpl->parse("H_WARNING_MAP", "B_WARNING_MAP");
	    }
	  }
	}
	if ($score_p1 < $matches_row['num_winmaps'] and $score_p2 < $matches_row['num_winmaps'])
	{
	  $is_complete = 0;
	  $content_tpl->parse("H_WARNING_REPORT", "B_WARNING_REPORT");
	}

	if ($is_complete)
	{
	  // check whether screenshot directory exists
	  if (!file_exists($sshot_dir))
	  {
	    mkdir($sshot_dir);
	    chmod($sshot_dir, 0777);
	  }

	  // screenshots
	  for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
	  {
	    $sshot['m' . $i] = false;
	    if ($_REQUEST['id_map' . $i] != "" and is_uploaded_file($tmp_file['m' . $i]))
	    {
	      $sshot['m' . $i] = true;
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
	  }

	  // match
	  dbQuery("UPDATE `{$cfg['db_table_prefix']}matches` SET " .
		   "`wo` = 0, " . 
		   "`out` = 0, " . 
		   "`score_p1` = $score_p1, " .
		   "`score_p2` = $score_p2, " .
		   "`comment_admin` = '', " .
		   "`submitted` = NOW(), " . 
		   "`submitter` = {$user['uid']} " . 
		   "WHERE `id` = {$matches_row['id']}");

	  // maps
	  dbQuery("DELETE FROM `{$cfg['db_table_prefix']}maps` " .
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

	      $comment_p1 = "";
	      $comment_p2 = "";
	      $comment_admin = "";
	      if ($user['uid'] == $matches_row['id_player1'])
	      {
		$comment_p1 = dbEscape($_REQUEST['comment_m' . $i]);
	      }
	      elseif ($user['uid'] == $matches_row['id_player2'])
	      {
		$comment_p2 = dbEscape($_REQUEST['comment_m' . $i]);
	      }
	      else
	      {
		$comment_admin = dbEscape($_REQUEST['comment_m' . $i]);
	      }

              $id_map = intval($_REQUEST['id_map' . $i]);
              $score_p1 = intval($_REQUEST['score_m' . $i . '_p1']);
              $score_p2 = intval($_REQUEST['score_m' . $i . '_p2']);
	      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}maps` " .
		       "(`id_match`,`id_map`,`score_p1`,`score_p2`,`comment_p1`,`comment_p2`,`comment_admin`,`num_map`) " .
		       "VALUES({$matches_row['id']},$id_map," .
		       "$score_p1,$score_p2," .
		       "'$comment_p1','$comment_p2'," .
		       "'$comment_admin',$i) ");
	    }
	  }

	  // send match report to irc
	  if ($section['admin_irc_channels'] != "")
	  {
	    $irc_channels = explode(";", $section['admin_irc_channels']);
	    foreach($irc_channels as $irc_channel) {
	      if ($cfg['bot_enabled'] and $section['bot_host'] != "" and $section['bot_port'] != "")
	      {
		$bot_socket = fsockopen($section['bot_host'], $section['bot_port']);
	      }
	      else
	      {
		$bot_socket = NULL;
	      }
	      if ($bot_socket and $section['admin_irc_channels'] != "")
	      {
		// player1
		if ($matches_row['id_player1'] > 0)
		{
		  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
				       "WHERE `id` = {$matches_row['id_player1']}");
		  $users_row = dbFetch($users_ref);
		  $player1 = $users_row['username'];
		}
		else
		{
		  $player1 = "-";
		}
		// player2
		if ($matches_row['id_player2'] > 0)
		{
		  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
				       "WHERE `id` = {$matches_row['id_player2']}");
		  $users_row = dbFetch($users_ref);
		  $player2 = $users_row['username'];
		}
		else
		{
		  $player2 = "-";
		}
		sleep(2);
		fwrite($bot_socket,
		       "{$section['bot_password']} $irc_channel {$section['name']} [$player1 vs $player2] reported - " .
		       "{$cfg['host']}{$cfg['path']}?sid={$_REQUEST['sid']}&mod=matches&act=edit&opt={$matches_row['id']}\r\n");
		fclose($bot_socket);
	      }
	    }
	  }

	  $content_tpl->parse("H_MESSAGE_MATCH_REPORTED", "B_MESSAGE_MATCH_REPORTED");
	  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
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
      // comment from player
      if ($matches_row['id_player1'] == $user['uid'])
      {
	$commentator = "p1";
      }
      elseif ($matches_row['id_player2'] == $user['uid'])
      {
	$commentator = "p2";
      }

      // maps-query
      $maps_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}maps` " .
			   "WHERE `id_match` = {$matches_row['id']} " . 
			   "ORDER BY `num_map`");
      while ($maps_row = dbFetch($maps_ref))
      {
	if ($maps_row['comment_' . $commentator] != "")
	{
	  $_REQUEST['comment_m' . $maps_row['num_map']] = $maps_row['comment_' . $commentator];
	}
        $comment = dbEscape($_REQUEST['comment_m' . $maps_row['num_map']]);
	dbQuery("UPDATE `{$cfg['db_table_prefix']}maps` SET " .
		 "`comment_$commentator` = '$comment' " .
		 "WHERE `id` = {$maps_row['id']}");
      }

      $content_tpl->parse("H_MESSAGE_COMMENT_ADDED", "B_MESSAGE_COMMENT_ADDED");
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    }
  }
  else
  {
    $content_tpl->parse("H_WARNING_PREV_MATCHES", "B_WARNING_PREV_MATCHES");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
