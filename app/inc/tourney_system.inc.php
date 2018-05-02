<?php

// Inserts winner's next round match
function insertWinnerMatch(& $match, $id_winner)
{
  global $cfg;

  if (hasNextWinnerMatch($match))
  {
    $winner_bracket = getNextWinnerBracket($match);
    $winner_round = getNextWinnerRound($match);
    $winner_match = getNextWinnerMatch($match);
    $winner_player = getNextWinnerPlayer($match);

    dbQuery("UPDATE `matches` SET " .
	    "`id_$winner_player` = $id_winner " .
	    "WHERE `id_season` = {$match['id_season']} " .
	    "AND `bracket` = '$winner_bracket' " .
	    "AND `round` = $winner_round " .
	    "AND `match` = $winner_match");

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$match['id_season']} " .
			   "AND `bracket` = '$winner_bracket' " .
			   "AND `round` = $winner_round " .
			   "AND `match` = $winner_match");
    $matches_row = dbFetch($matches_ref);
    return($matches_row);
  }
  elseif ($match['bracket'] == "gf" and $match['match'] == 1 and $match['id_player1'] == $id_winner)
  {
    dbQuery("UPDATE `matches` SET " .
	    "`id_player1` = 0 " .
	    "WHERE `id_season` = {$match['id_season']} " .
	    "AND `bracket` = 'gf' " .
	    "AND `round` = 1 " .
	    "AND `match` = 2");
  }
  return(NULL);
}

// Inserts loser's next round match
function insertLoserMatch(& $match, $id_loser)
{
  global $cfg;

  if (hasNextLoserMatch($match))
  {
    $loser_bracket = getNextLoserBracket($match);
    $loser_round = getNextLoserRound($match);
    $loser_match = getNextLoserMatch($match);
    $loser_player = getNextLoserPlayer($match);

    dbQuery("UPDATE `matches` SET " .
	    "`id_$loser_player` = $id_loser " .
	    "WHERE `id_season` = {$match['id_season']} " .
	    "AND `bracket` = '$loser_bracket' " .
	    "AND `round` = $loser_round " .
	    "AND `match` = $loser_match");

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$match['id_season']} " .
			   "AND `bracket` = '$loser_bracket' " .
			   "AND `round` = $loser_round " .
			   "AND `match` = $loser_match");
    $matches_row = dbFetch($matches_ref);
    return($matches_row);
  }
  elseif ($match['bracket'] == "gf" and $match['match'] == 1 and $match['id_player2'] == $id_loser)
  {
    dbQuery("UPDATE `matches` SET " .
	    "`id_player2` = 0 " .
	    "WHERE `id_season` = {$match['id_season']} " .
	    "AND `bracket` = 'gf' " .
	    "AND `round` = 1 " .
	    "AND `match` = 2");
  }
  return(NULL);
}

// Gets winner's next round match
function getWinnerMatch(& $match)
{
  global $cfg;
  global $season;

  if (hasNextWinnerMatch($match))
  {
    $winner_bracket = getNextWinnerBracket($match);
    $winner_round = getNextWinnerRound($match);
    $winner_match = getNextWinnerMatch($match);
    $winner_player = getNextWinnerPlayer($match);

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$season['id']} " .
			   "AND `bracket` = '$winner_bracket' " .
			   "AND `round` = $winner_round " .
			   "AND `match` = $winner_match");
    if ($matches_row = dbFetch($matches_ref))
    {
      return($matches_row);
    }
    else
    {
      return(NULL);
    }
  }
  return(NULL);
}

// Gets loser's next round match
function getLoserMatch(& $match)
{
  global $cfg;
  global $season;

  if (hasNextLoserMatch($match))
  {
    $loser_bracket = getNextLoserBracket($match);
    $loser_round = getNextLoserRound($match);
    $loser_match = getNextLoserMatch($match);
    $loser_player = getNextLoserPlayer($match);

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$season['id']} " .
			   "AND `bracket` = '$loser_bracket' " .
			   "AND `round` = $loser_round " .
			   "AND `match` = $loser_match");
    if ($matches_row = dbFetch($matches_ref))
    {
      return($matches_row);
    }
    else
    {
      return(NULL);
    }
  }
  return(NULL);
}

// Checks whether the given match is an editable match
function isLastMatch(& $match)
{
  global $cfg;

  $next_winner_match_played = false;
  if (hasNextWinnerMatch($match))
  {
    $winner_bracket = getNextWinnerBracket($match);
    $winner_round = getNextWinnerRound($match);
    $winner_match = getNextWinnerMatch($match);

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$match['id_season']} " .
			   "AND `bracket` = '$winner_bracket' " .
			   "AND `round` = $winner_round " .
			   "AND `match` = $winner_match " .
			   "AND `submitted` <> '0000-00-00 00:00:00'");
    if (dbNumRows($matches_ref) == 1)
    {
      $next_winner_match_played = true;
    }
  }

  $next_loser_match_played = false;
  if (hasNextLoserMatch($match))
  {
    $loser_bracket = getNextLoserBracket($match);
    $loser_round = getNextLoserRound($match);
    $loser_match = getNextLoserMatch($match);

    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			   "WHERE `id_season` = {$match['id_season']} " .
			   "AND `bracket` = '$loser_bracket' " .
			   "AND `round` = $loser_round " .
			   "AND `match` = $loser_match " .
			   "AND `submitted` <> '0000-00-00 00:00:00'");

    if (dbNumRows($matches_ref) == 1)
    {
      $next_loser_match_played = true;
    }
  }

  if ($next_winner_match_played or $next_loser_match_played)
  {
    return(false);
  }
  else
  {
    return(true);
  }
}

// Checks whether the winner of the given match has another match
function hasNextWinnerMatch(& $match)
{
  global $season;

  if ($match['bracket'] == "q")
  {
    return(true);
  }
  elseif ($match['bracket'] == "lb" and $match['round'] <= getNumLBRounds($season))
  {
    return(true);
  }
  elseif ($match['bracket'] == "wb" and $match['round'] < getNumWBRounds($season))
  {
    return(true);
  } elseif ($match['bracket'] == "wb" and
	    $match['round'] == getNumWBRounds($season) and
	    $season['double_elimination'] != "") {
    return(true);
  }
  elseif ($match['bracket'] == "gf" and $match['match'] == 1 and $match['id_player2'] == winnerOf($match))
  {
    return(true);
  }
  else
  {
    return(false);
  }
}

// Checks whether the loser of the given match has another match
function hasNextLoserMatch(& $match)
{
  global $season;

  if ($match['bracket'] == "wb" and
      $season['double_elimination'] != "" and
      $season['single_elimination'] / pow(2, $match['round'] - 1) <= $season['double_elimination']) {
    return(true);
  }
  elseif ($match['bracket'] == "gf" and $match['match'] == 1 and $match['id_player2'] == winnerOf($match))
  {
    return(true);
  }
  else
  {
    return(false);
  }
}

// Returns the bracket of the winner's next match
function getNextWinnerBracket(& $match)
{
  global $season;

  if ($match['bracket'] == "q")
  {
    return("wb");
  }
  elseif ($match['bracket'] == "wb" and $match['round'] < getNumWBRounds($season))
  {
    return("wb");
  } elseif ($match['bracket'] == "wb" and
	    $match['round'] == getNumWBRounds($season) and
	    $season['double_elimination'] != "") {
    return("gf");
  }
  elseif ($match['bracket'] == "lb" and $match['round'] < getNumLBRounds($season))
  {
    return("lb");
  } elseif ($match['bracket'] == "lb" and
	    $match['round'] == getNumLBRounds($season)) {
    return("gf");
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return("gf");
  }
}

// Returns the bracket of the loser's next match
function getNextLoserBracket(& $match)
{
  global $season;

  if ($match['bracket'] == "wb" and
	    $season['double_elimination'] != "" and
	    $season['single_elimination'] / pow(2, $match['round'] - 1) <= $season['double_elimination']) {
    return("lb");
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return("gf");
  }
}

// Returns the round of the winner's next match
function getNextWinnerRound(& $match)
{
  global $season;

  if ($match['bracket'] == "q")
  {
    return(1);
  }
  elseif ($match['bracket'] == "wb" and $match['round'] < getNumWBRounds($season))
  {
    return($match['round'] + 1);
  } elseif ($match['bracket'] == "wb" and
	    $match['round'] == getNumWBRounds($season) and
	    $season['double_elimination'] != "") {
    return(1);
  }
  elseif ($match['bracket'] == "lb" and $match['round'] < getNumLBRounds($season))
  {
    return($match['round'] + 1);
  } elseif ($match['bracket'] == "lb" and
	    $match['round'] == getNumLBRounds($season)) {
    return(1);
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return(1);
  }
}

// Returns the round of the loser's next match
function getNextLoserRound(& $match)
{
  global $season;

  if ($match['bracket'] == "wb" and
      $season['double_elimination'] != "" and
      $season['single_elimination'] / pow(2, $match['round'] - 1) <= $season['double_elimination']) {
    if ($season['single_elimination'] / pow(2, $match['round'] - 1) == $season['double_elimination'])
    {
      return(1);
    }
    else
    {
      return(($match['round'] - $season['single_elimination'] / $season['double_elimination']) * 2);
    }
  }
  elseif ($match['bracket'] == "wb" and $season['double_elimination'] != "")
  {
    return(1);
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return(1);
  }
}

// Returns the match of the winner's next match
function getNextWinnerMatch(& $match)
{
  global $season;

  if ($match['bracket'] == "q")
  {
    return($match['match']);
  }
  elseif ($match['bracket'] == "wb" and $match['round'] < getNumWBRounds($season))
  {
    if (1 & $match['match'])
    {
      return(($match['match'] + 1) / 2);
    }
    else
    {
      return($match['match'] / 2);
    }
  } elseif ($match['bracket'] == "wb" and
	    $match['round'] == getNumWBRounds($season) and
	    $season['double_elimination'] != "") {
    return(1);
  }
  elseif ($match['bracket'] == "lb" and $match['round'] < getNumLBRounds($season))
  {
    if (1 & $match['round'])
    {
      return($match['match']);
    }
    else
    {
      if (1 & $match['match'])
      {
	return(($match['match'] + 1) / 2);
      }
      else
      {
	return($match['match'] / 2);
      }
    }
  } elseif ($match['bracket'] == "lb" and
	    $match['round'] == getNumLBRounds($season)) {
    return(1);
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return(2);
  }
}

// Returns the match of the loser's next match
function getNextLoserMatch(& $match)
{ 
  global $season;

  if ($match['bracket'] == "wb" and
      $season['double_elimination'] != "" and
      $season['single_elimination'] / pow(2, $match['round'] - 1) <= $season['double_elimination']) {

    $lb_round = $match['round'] - log10($season['single_elimination']) / log10(2) + log10($season['double_elimination']) / log10(2);
    if ($lb_round == 1)
    {
      if (1 & $match['match'])
      {
	return(($match['match'] + 1) / 2);
      }
      else
      {
	return($match['match'] / 2);
      }
    }
    else
    {
      return(getDblElDrop($lb_round, $match['match']));
    }
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return(2);
  }
}

// Returns the player of the winner's next match
function getNextWinnerPlayer(& $match)
{
  global $season;

  if ($match['bracket'] == "q")
  {
    return("player2");
  }
  elseif ($match['bracket'] == "wb" and $match['round'] < getNumWBRounds($season))
  {
    if (1 & $match['match'])
    {
      return("player1");
    }
    else
    {
      return("player2");
    }
  } elseif ($match['bracket'] == "wb" and
	    $match['round'] == getNumWBRounds($season) and
	    $season['double_elimination'] != "") {
    return("player1");
  }
  elseif ($match['bracket'] == "lb" and $match['round'] < getNumLBRounds($season))
  {
    if (1 & $match['round'])
    {
      return("player2");
    }
    else
    {
      if (1 & $match['match'])
      {
	return("player1");
      }
      else
      {
	return("player2");
      }
    }
  } elseif ($match['bracket'] == "lb" and
	    $match['round'] == getNumLBRounds($season)) {
    return("player2");
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return("player2");
  }
}

// Returns the player of the loser's next match
function getNextLoserPlayer(& $match)
{
  global $season;

  if ($match['bracket'] == "wb" and
      $season['double_elimination'] != "" and
      $season['single_elimination'] / pow(2, $match['round'] - 1) <= $season['double_elimination']) {
    if ($match['round'] == $season['single_elimination'] / $season['double_elimination'])
    {
      if (1 & $match['match'])
      {
	return("player1");
      }
      else
      {
	return("player2");
      }
    }
    else
    {
      return("player1");
    }
  }
  elseif ($match['bracket'] == "gf" and $match['id_player2'] == winnerOf($match))
  {
    return("player1");
  }
}

// Returns the seeding of a given exponent of 2
function getSeeding($number)
{
  $counter = 0;
  $range[0] = array(1, $number);
  $matches[$counter++] = 1;

  for ($i = 0; $i < log10($number) / log10(2); ++$i)
  {
    $temp = $range;
    $index = 0;
    for ($j = $counter - 1; $j >= 0; --$j)
    {
      foreach($temp as $current) {
	if ($current[0] == $matches[$j])
	{
	  $range[$index++] = $current;
	}
      }
    }

    $temp = $range;
    $array_size = count($temp);
    for ($k = 0; $k < $array_size; ++$k)
    {
      $start = $temp[$k][0];
      $end = $temp[$k][0] + ($temp[$k][1] - $temp[$k][0] + 1) / 2 - 1;
      $range[$k * 2] = array($start, $end);

      $start = $temp[$k][0] + ($temp[$k][1] - $temp[$k][0] + 1) / 2;
      $end = $temp[$k][1];
      $range[$k * 2 + 1] = array($start, $end);

      $matches[$counter++] = $start;
    }
  }
  return($matches);
}

// Returns the drop to the LB
// Thanks to Frode Nilsen for the algorithm
function getDblElDrop($round, $match)
{
  global $season;

  $roundSize = $season['double_elimination'] / 2;
  $roundIndex = $match - 1;
  $roundNr = $round - 1;

  $key = $roundSize / 2;
  $nr = $roundIndex * (1 << $roundNr);
  for ($i = 1; $i <= $roundNr; $i++)
  {
    for ($a = $key, $b = 1; $a > 0; $a >>= 1, $b <<= 1)
    {
      if ($i % $b == 0)
      {
	if (($nr & $a) > 0) $nr -= $a;
	else $nr += $a;
      }
    }
  }
  $nr >>= $roundNr;
  return($nr + 1);
}

// Returns the number of winners bracket rounds of a given season
function getNumWBRounds(& $season)
{
  $num_participants = $season['single_elimination'];
  $num_wb_rounds = log10($num_participants) / log10(2);
  return($num_wb_rounds);
}

// Returns the number of losers bracket rounds of a given season divided by 2
function getNumLBRounds(& $season)
{
  $num_participants = $season['double_elimination'];
  $num_lb_rounds = (log10($num_participants) / log10(2) - 1) * 2;
  return($num_lb_rounds);
}

// Returns the number of matches in a winners bracket round of a given season
function getNumWBMatches(& $season, $i)
{
  $num_participants = $season['single_elimination'];
  $num_wb_matches = $num_participants / pow(2, $i);
  return($num_wb_matches);
}

// Returns the number of matches in a losers bracket round of a given season
function getNumLBMatches(& $season, $i)
{
  if ((1 & $i))
  {
    $i = ($i + 1) / 2;
  }
  else
  {
    $i = $i / 2;
  }
  $num_participants = $season['double_elimination'];
  $num_lb_matches = $num_participants / pow(2, $i);
  return($num_lb_matches);
}

// Checks whether a given round is after the pre-deadline
function isAfterPreDeadline(& $season, $bracket, $round) {
  global $cfg;

  $today = getdate();
  $year = $today['year'];
  $month = $today['mon'];
  $day = $today['mday'];

  $pre_deadline_round = $round - 1;
  $deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
			   "WHERE `id_season` = {$season['id']} " .
			   "AND `round` = '$bracket$pre_deadline_round'");
  if (dbNumRows($deadlines_ref) == 0)
  {
    return(true);
  }
  else
  {
    $deadlines_row = dbFetch($deadlines_ref);
    $deadline = $deadlines_row['deadline'];
    preg_match("/(\d\d\d\d)-\d\d-\d\d/", $deadlines_row['deadline'], $matches);
    $d_year = $matches[1];
    preg_match("/\d\d\d\d-(\d\d)-\d\d/", $deadlines_row['deadline'], $matches);
    $d_month = $matches[1];
    preg_match("/\d\d\d\d-\d\d-(\d\d)/", $deadlines_row['deadline'], $matches);
    $d_day = $matches[1];

    if ($year > $d_year or
	$year == $d_year and $month > $d_month or
	$year == $d_year and $month == $d_month and $day > $d_day)
    {
      return(true);
    }
    else
    {
      return(false);
    }
  }
}

// Checks whether a given round is before the post-deadline
function isBeforePostDeadline(& $season, $bracket, $round)
{
  global $cfg;

  $today = getdate();
  $year = $today['year'];
  $month = $today['mon'];
  $day = $today['mday'];

  $post_deadline_round = $round;
  $deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
			    "WHERE `id_season` = {$season['id']} " .
			    "AND `round` = '$bracket$post_deadline_round'");
  if (dbNumRows($deadlines_ref) == 0)
  {
    return(true);
  }
  else
  {
    $deadlines_row = dbFetch($deadlines_ref);
    $deadline = $deadlines_row['deadline'];
    preg_match("/(\d\d\d\d)-\d\d-\d\d/", $deadlines_row['deadline'], $matches);
    $d_year = $matches[1];
    preg_match("/\d\d\d\d-(\d\d)-\d\d/", $deadlines_row['deadline'], $matches);
    $d_month = $matches[1];
    preg_match("/\d\d\d\d-\d\d-(\d\d)/", $deadlines_row['deadline'], $matches);
    $d_day = $matches[1];

    if ($year < $d_year or
	$year == $d_year and $month < $d_month or
	$year == $d_year and $month == $d_month and $day <= $d_day) {
      return(true);
    }
    else
    {
      return(false);
    }
  }
}

// Returns the player-id of the winner of the given match
function winnerOf(& $match)
{
  global $cfg;

  if ($match['confirmed'] == "0000-00-00 00:00:00")
  {
    return(NULL);
  }
  if ($match['score_p1'] > $match['score_p2'])
  {
    return($match['id_player1']);
  }
  elseif ($match['score_p1'] < $match['score_p2'])
  {
    return($match['id_player2']);
  }
}

?>
