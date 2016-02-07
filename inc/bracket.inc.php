<?php

////////////////////////////////////////////////////////////////////////////////
// Bracket functions - Gets all users/matches from the database
//
// $Id: bracket.inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
//
// Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
////////////////////////////////////////////////////////////////////////////////

if (isset($season))
{
  $users = array(); // user array
  $matches = array(); // matches array

  // users-query
  $users_ref = dbQuery("SELECT U.`id`, U.`username`, C.`abbreviation` " .
		       "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}countries` C " .
		       "WHERE U.`id_country` = C.`id`");
  while ($users_row = dbFetch($users_ref))
  {
    $users[$users_row['id']] = $users_row;
  }

  // matches-query
  $matches_ref = dbQuery("SELECT `id`, `bracket`, `round`, `match`, `wo`, `out`, `bye`, " .
			 "`id_player1`, `id_player2`, `score_p1`, `score_p2`, `confirmed` " .
			 "FROM `{$cfg['db_table_prefix']}matches` " .
			 "WHERE `id_season` = {$season['id']} ");
  while ($matches_row = dbFetch($matches_ref))
  {
    // player1
    if ($matches_row['id_player1'] > 0)
    {
      $matches_row['player1'] = $users[$matches_row['id_player1']]['username'];
      $matches_row['country1'] = $users[$matches_row['id_player1']]['abbreviation'];
    }
    else
    {
      $matches_row['player1'] = "";
      $matches_row['country1'] = "";
    }
    // player2
    if ($matches_row['id_player2'] > 0)
    {
      $matches_row['player2'] = $users[$matches_row['id_player2']]['username'];
      $matches_row['country2'] = $users[$matches_row['id_player2']]['abbreviation'];
    }
    else
    {
      $matches_row['player2'] = "";
      $matches_row['country2'] = "";
    }
    $matches[$matches_row['bracket'] . '-' . $matches_row['round'] . '-' . $matches_row['match']] = $matches_row;
  }
  $keys = array_keys($matches);
  foreach($keys as $key) {
    recMatches($matches, $key);
  }
}

//================================================================================
// function recMatches
// Browses recursively through the bracket
//================================================================================

function recMatches(& $matches, $key) {
  if (hasNextWinnerMatch($matches[$key]))
  {
    $winner_bracket = getNextWinnerBracket($matches[$key]);
    $winner_round = getNextWinnerRound($matches[$key]);
    $winner_match = getNextWinnerMatch($matches[$key]);
    $winner_player = getNextWinnerPlayer($matches[$key]);
    $new_key = "$winner_bracket-$winner_round-$winner_match";
    if ($matches[$new_key]['id_' . $winner_player] == 0 and $matches[$key]['confirmed'] == "0000-00-00 00:00:00")
    {
      $matches[$new_key][$winner_player] = "Winner [$key]";
    }
    $matches[$new_key]['prev_match_' . $winner_player] = $key;
  }
  if (hasNextLoserMatch($matches[$key]))
  {
    $loser_bracket = getNextLoserBracket($matches[$key]);
    $loser_round = getNextLoserRound($matches[$key]);
    $loser_match = getNextLoserMatch($matches[$key]);
    $loser_player = getNextLoserPlayer($matches[$key]);
    $new_key = "$loser_bracket-$loser_round-$loser_match";
    if ($matches[$new_key]['id_' . $loser_player] == 0)
    {
      $matches[$new_key][$loser_player] = "Loser [$key]";
    }
    $matches[$new_key]['prev_match_' . $loser_player] = $key;
  }
}

?>
