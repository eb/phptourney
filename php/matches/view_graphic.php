<?php

define("TOP_OFFSET", 100);
define("LEFT_OFFSET", 100);
define("ROW_HEIGHT", 13);
define("ROUND_WIDTH", 100);
define("CON_WIDTH", 10);

// template blocks
$content_tpl->set_block("F_CONTENT", "B_NO_BRACKET", "H_NO_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_BRACKET", "H_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_MATCH_PLAYER", "H_MATCH_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_MATCH_WINNER", "H_MATCH_WINNER");
$content_tpl->set_block("F_CONTENT", "B_MATCH_WO", "H_MATCH_WO");
$content_tpl->set_block("F_CONTENT", "B_MATCH_BYE", "H_MATCH_BYE");
$content_tpl->set_block("F_CONTENT", "B_MATCH_OUT", "H_MATCH_OUT");
$content_tpl->set_block("F_CONTENT", "B_CON", "H_CON");

if ($season['status'] == "bracket" or $season['status'] == "running" or $season['status'] == "finished")
{
  $matches = getAllMatches($season);
  $qualification = $season['qualification'];
  $single_elimination = $season['single_elimination'];
  $double_elimination = $season['double_elimination'];
  $num_wb_rounds = getNumWBRounds($season);
  $num_lb_rounds = getNumLBRounds($season);

  ////////////////////////////////////////////////////////////////////////////////
  // qualification
  ////////////////////////////////////////////////////////////////////////////////
  if ($qualification == 1)
  {
#    q();
#    q_con();
  }

  ////////////////////////////////////////////////////////////////////////////////
  // single elimination
  ////////////////////////////////////////////////////////////////////////////////
  if ($double_elimination == "")
  {
    for ($i = 0; $i < $num_wb_rounds; $i++)
    {
      wb($i);
#      wb_con($i + 1);
    }
#    se_winner($i - 1);
  }

  ////////////////////////////////////////////////////////////////////////////////
  // double elimination
  ////////////////////////////////////////////////////////////////////////////////
  else
  {
    for ($i = 0; $i < $num_wb_rounds; $i++)
    {
      $num_players = $single_elimination / pow(2, $i);
      if ($num_players > $double_elimination)
      {
	wb($i);
#	wb_con($i + 1);
      }
      elseif ($num_players == $double_elimination)
      {
	wb($i);
#	wb_con($i + 1);
      }
      elseif ($num_players == $double_elimination / 2)
      {
	wb($i);
#	lb($i);
#	wb_con($i + 1);
#	lb_el_con($i + 1);
      }
      else
      {
#	wb_empty($i);
#	lb_el($i);
#	wb_empty_con($i);
#	lb_con($i);
	wb($i);
#	lb($i);
#	wb_con($i + 1);
#	lb_el_con($i + 1);
      }
    }

#    wb_empty($i - 1);
#    lb_el($i);
#    gf_con($i - 1);
#    gf($i - 1);

#    if (true) {
#      gf2_con($i - 1);
#      gf($i - 1);
#    }

#    gf2_con($i - 1);
#    gf_winner($i - 1);
  }
  $content_tpl->parse("H_BRACKET", "B_BRACKET");
}
else
{
  $content_tpl->parse("H_NO_BRACKET", "B_NO_BRACKET");
}

################################################################################
#
#
################################################################################
function q() {
  global $single_elimination;
  global $rounds;
  global $brackets;
  global $matches_q;

  $bracket = & $brackets[count($brackets) - 1];
  for ($j = 0; $j < $single_elimination / 2; $j++)
  {
    if ($matches_q[$j])
    {
#      $winner = winner_is($_REQUEST['sid'], "q", 0, $j + 1);
      if ($winner == "p1")
      {
	array_push($bracket, "SPACE");
	array_push($bracket, "MATCH_WINNER1");
	array_push($bracket, "FIRST_MATCH");
	array_push($bracket, "MATCH_PLAYER2");
      }
      elseif ($winner == "p2")
      {
	array_push($bracket, "SPACE");
	array_push($bracket, "MATCH_PLAYER1");
	array_push($bracket, "FIRST_MATCH");
	array_push($bracket, "MATCH_WINNER2");
      }
      else
      {
	array_push($bracket, "SPACE");
	array_push($bracket, "MATCH_PLAYER1");
	array_push($bracket, "FIRST_MATCH");
	array_push($bracket, "MATCH_PLAYER2");
      }
    }
    else
    {
      array_push($bracket, "SPACE");
      array_push($bracket, "SPACE");
      array_push($bracket, "SPACE_MATCH");
      array_push($bracket, "SPACE");
    }
  }
}

################################################################################
#
#
################################################################################
function q_con() {
  global $single_elimination;
  global $rounds;
  global $brackets;
  global $matches_q;
  
  $bracket = & $brackets[count($brackets) - 1];
  for ($j = 0; $j < $single_elimination / 2; $j++)
  {
    if ($matches_q[$j])
    {
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_HOR");
      array_push($bracket, "CON_SPACE");
    }
    else
    {
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
    }
  }
}

################################################################################
#
#
################################################################################
function wb($i) {
  global $season;
  global $qualification;
  global $single_elimination;
  global $content_tpl;
  global $matches;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;

  $top = TOP_OFFSET;
  $left = LEFT_OFFSET + $i * (ROUND_WIDTH + CON_WIDTH * 3);
  for ($j = 0; $j < $num_matches; $j++)
  {
    $match_key = "wb-" . ($i + 1) . "-" . ($j + 1);
    for ($k = 0; $k < $num_spaces; $k++)
    {
      $top += ROW_HEIGHT;
    }
    $content_tpl->set_var("I_TOP", $top);
    $content_tpl->set_var("I_LEFT", $left);
    $content_tpl->set_var("I_PLAYER1", "");
    $content_tpl->set_var("I_PLAYER2", "");
    if ($matches[$match_key]['confirmed'] != "0000-00-00 00:00:00")
    {
      $content_tpl->set_var("I_SCORE", $matches[$match_key]['score1']);
      $content_tpl->set_var("I_PLAYER", $matches[$match_key]['player1']);
      if ($matches[$match_key]['wo'] == $matches[$match_key]['id_player1'])
      {
	$content_tpl->parse("I_PLAYER1", "B_MATCH_WO");
      }
      elseif ($matches[$match_key]['bye'] == 1 and $matches[$match_key]['id_player1'] > 0)
      {
	$content_tpl->parse("I_PLAYER1", "B_MATCH_BYE");
      }
      elseif ($matches[$match_key]['out'] == 1)
      {
	$content_tpl->parse("I_PLAYER1", "B_MATCH_OUT");
      }
      elseif ($matches[$match_key]['score1'] > $matches[$match_key]['score2'])
      {
	$content_tpl->parse("I_PLAYER1", "B_MATCH_WINNER");
      }
      else
      {
	$content_tpl->parse("I_PLAYER1", "B_MATCH_PLAYER");
      }
      $content_tpl->set_var("I_SCORE", $matches[$match_key]['score2']);
      $content_tpl->set_var("I_PLAYER", $matches[$match_key]['player2']);
      if ($matches[$match_key]['wo'] == $matches[$match_key]['id_player2'])
      {
	$content_tpl->parse("I_PLAYER2", "B_MATCH_WO");
      }
      elseif ($matches[$match_key]['bye'] == 1 and $matches[$match_key]['id_player2'] > 0)
      {
	$content_tpl->parse("I_PLAYER2", "B_MATCH_BYE");
      }
      elseif ($matches[$match_key]['out'] == 1)
      {
	$content_tpl->parse("I_PLAYER2", "B_MATCH_OUT");
      }
      elseif ($matches[$match_key]['score1'] < $matches[$match_key]['score2'])
      {
	$content_tpl->parse("I_PLAYER2", "B_MATCH_WINNER");
      }
      else
      {
	$content_tpl->parse("I_PLAYER2", "B_MATCH_PLAYER");
      }
    }
    else
    {
      $content_tpl->set_var("I_SCORE", $matches[$match_key]['score1']);
      $content_tpl->set_var("I_PLAYER", "gna");
#      $content_tpl->parse("I_PLAYER1", "B_MATCH_PLAYER");
      $content_tpl->parse("I_PLAYER1", "B_MATCH_PLAYER");
      $content_tpl->set_var("I_SCORE", $matches[$match_key]['score2']);
#      $content_tpl->set_var("I_PLAYER", $matches[$match_key]['player2']);
      $content_tpl->set_var("I_PLAYER", "bla");
      $content_tpl->parse("I_PLAYER2", "B_MATCH_PLAYER");
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("I_ID_MATCH", $matches[$match_key]['id']);
    $content_tpl->set_var("I_MATCH_KEY", $match_key);
    $content_tpl->parse("I_MATCHES", "B_MATCH", true);

    $top += ROW_HEIGHT * 3;
    for ($k = 0; $k < $num_spaces; $k++)
    {
      $top += ROW_HEIGHT;
    }
      $top += ROW_HEIGHT;
  }
}

################################################################################
#
#
################################################################################
function se_winner($i) {
  global $single_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
    array_push($bracket, "WINNER");
    array_push($bracket, "SPACE");
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function lb($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / 2 / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 1) * 2 + (pow(2, $num_lb_round - 1) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  for ($k = 0; $k < $top_space; $k++)
  {
    array_push($bracket, "SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "SPACE");
    array_push($bracket, "LOSER");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "MATCH_PLAYER1");
    array_push($bracket, "MATCH");
    array_push($bracket, "MATCH_PLAYER2");
    array_push($bracket, "SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function lb_el($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space; $k++)
  {
    array_push($bracket, "SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "MATCH_PLAYER1");
    array_push($bracket, "MATCH");
    array_push($bracket, "MATCH_PLAYER2");
    array_push($bracket, "SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function wb_empty($i) {
  global $single_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
    array_push($bracket, "EMPTY");
    array_push($bracket, "SPACE");
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function wb_con($i) {
  global $single_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  $num_vers = pow(2, $i) - 1;

  if ($num_matches < 1)
  {
    for ($j = 1; $j <= $single_elimination * 2; $j++)
    {
      if ($j == $single_elimination)
      {
	array_push($bracket, "CON_HOR");
      }
      else
      {
	array_push($bracket, "CON_SPACE");
      }
    }
  }

  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces - $num_vers; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_TOP");
    for ($l = 0; $l < $num_vers; $l++)
    {
      array_push($bracket, "CON_VER");
    }
    array_push($bracket, "CON_MIDDLE");
    for ($l = 0; $l < $num_vers; $l++)
    {
      array_push($bracket, "CON_VER");
    }
    array_push($bracket, "CON_BOTTOM");
    for ($k = 0; $k < $num_spaces - $num_vers; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_SPACE");
  }
}

################################################################################
#
#
################################################################################
function wb_empty_con($i) {
  global $single_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $bracket = array();
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_HOR");
    array_push($bracket, "CON_SPACE");
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_SPACE");
  }
}

################################################################################
#
#
################################################################################
function lb_con($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);

  $num_spaces = pow(2, $num_lb_round + 1) - 1;
  $num_vers = pow(2, $num_lb_round) - 1;
  $num_top_spaces = $num_vers - ($num_lb_round - 2) * 2;
  $num_bottom_spaces = $num_spaces - $num_top_spaces;

  if ($num_lb_round == 1)
  {
    for ($j = 1; $j <= $num_matches; $j++)
    {
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_SPACE");
      array_push($bracket, "CON_TOP");
      array_push($bracket, "CON_MIDDLE");
      array_push($bracket, "CON_BOTTOM");
      array_push($bracket, "CON_SPACE");
    }
  }
  else
  {
    for ($j = 1; $j <= $num_matches; $j++)
    {
      for ($k = 1; $k <= $num_top_spaces; $k++)
      {
	array_push($bracket, "CON_SPACE");
      }
      array_push($bracket, "CON_TOP");
      for ($k = 1; $k <= $num_vers; $k++)
      {
	array_push($bracket, "CON_VER");
      }
      array_push($bracket, "CON_MIDDLE");
      for ($k = 1; $k <= $num_vers; $k++)
      {
	array_push($bracket, "CON_VER");
      }
      array_push($bracket, "CON_BOTTOM");
      for ($k = 1; $k <= $num_bottom_spaces; $k++)
      {
	array_push($bracket, "CON_SPACE");
      }
    }
  }
}

################################################################################
#
#
################################################################################
function lb_el_con($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i);
  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);

  $num_spaces = pow(2, $num_lb_round + 1) - 5;
  $num_top_spaces = ($num_spaces + 1) / 2 - 1 - ($num_lb_round - 2) * 2;
  $num_bottom_spaces = $num_spaces - $num_top_spaces;

  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 1; $k <= $num_top_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_TOP");
    array_push($bracket, "CON_VER");
    array_push($bracket, "CON_MIDDLE");
    array_push($bracket, "CON_VER");
    array_push($bracket, "CON_BOTTOM");
    for ($k = 1; $k <= $num_bottom_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
  }
}

################################################################################
#
#
################################################################################
function lb_finale($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space; $k++)
  {
    array_push($bracket, "SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "MATCH_PLAYER1");
    array_push($bracket, "MATCH");
    array_push($bracket, "MATCH_PLAYER2");
    array_push($bracket, "SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function gf($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    for ($k = 0; $k < $num_spaces - 1; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "MATCH_PLAYER1");
    array_push($bracket, "MATCH");
    array_push($bracket, "MATCH_PLAYER2");
  }

  $i++;

  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space - 1; $k++)
  {
    array_push($bracket, "SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "SPACE");
  }
}

################################################################################
#
#
################################################################################
function gf_con($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_TOP");
    array_push($bracket, "CON_VER");
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_VER");
    }
    array_push($bracket, "CON_MIDDLE");
  }

  $i++;

  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space; $k++)
  {
    array_push($bracket, "CON_VER");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "CON_VER");
    array_push($bracket, "CON_BOTTOM");
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "CON_SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "CON_SPACE");
  }
}

################################################################################
#
#
################################################################################
function gf2_con($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "CON_SPACE");
    }
    array_push($bracket, "CON_HOR");
  }

  $i++;

  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space; $k++)
  {
    array_push($bracket, "CON_SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    array_push($bracket, "CON_SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "CON_SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "CON_SPACE");
  }
}

################################################################################
#
#
################################################################################
function gf_winner($i) {
  global $single_elimination;
  global $double_elimination;
  global $rounds;
  global $brackets;

  $bracket = & $brackets[count($brackets) - 1];
  $num_matches = $single_elimination / pow(2, $i) / 2;
  $num_spaces = (pow(2, $i) * 4 - 4) / 2;
  for ($j = 1; $j <= $num_matches; $j++)
  {
    for ($k = 0; $k < $num_spaces; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    for ($k = 0; $k < $num_spaces - 1; $k++)
    {
      array_push($bracket, "SPACE");
    }
    array_push($bracket, "SPACE");
    array_push($bracket, "WINNER");
    array_push($bracket, "SPACE");
  }

  $i++;

  $num_wb_rounds = log10($single_elimination) / log10(2);
  $num_lb_rounds = log10($double_elimination) / log10(2);
  $num_lb_round = $i - ($num_wb_rounds - $num_lb_rounds);
  $num_matches = $double_elimination / pow(2, $num_lb_round);

  $bottom_space = ($num_lb_round - 2) * 2 + (pow(2, $num_lb_round - 2) - 1) * 4;
  $normal_space = (pow(2, $num_lb_round  - 1) - 2) * 4;
  $top_space = $normal_space - $bottom_space;

  $top_space += 2;
  $normal_space +=4;
  $bottom_space += 2;

  for ($k = 0; $k < $top_space - 1; $k++)
  {
    array_push($bracket, "SPACE");
  }

  for ($k = 1; $k <= $num_matches; $k++)
  {
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    array_push($bracket, "SPACE");
    if ($k != $num_matches)
    {
      for ($j = 0; $j < $normal_space; $j++){
	array_push($bracket, "SPACE");
      }
    }
  }

  for ($k = 0; $k < $bottom_space; $k++)
  {
    array_push($bracket, "SPACE");
  }
}

?>
