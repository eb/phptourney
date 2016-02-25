<?php

$content_tpl->set_block("F_CONTENT", "B_NO_BRACKET", "H_NO_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_WB_ACTUAL_ROUND", "H_WB_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WB_DEADLINE", "H_WB_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_WB_ROUND", "H_WB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WB_PLAYED_MATCH", "H_WB_PLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_UNPLAYED_MATCH", "H_WB_UNPLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_WO_MATCH", "H_WB_WO_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_BYE_MATCH", "H_WB_BYE_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_OUT_MATCH", "H_WB_OUT_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_EMPTY_MATCH", "H_WB_EMPTY_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WB_PLAYER1", "H_WB_PLAYER1");
$content_tpl->set_block("F_CONTENT", "B_WB_PLAYER2", "H_WB_PLAYER2");
$content_tpl->set_block("F_CONTENT", "B_WB_DOUBLE_ELIMINATION", "H_WB_DOUBLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WB_SINGLE_ELIMINATION", "H_WB_SINGLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WINNERS_BRACKET", "H_WINNERS_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_LB_ACTUAL_ROUND", "H_LB_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_LB_DEADLINE", "H_LB_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_LB_ROUND", "H_LB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_LB_PLAYED_MATCH", "H_LB_PLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_UNPLAYED_MATCH", "H_LB_UNPLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_WO_MATCH", "H_LB_WO_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_BYE_MATCH", "H_LB_BYE_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_OUT_MATCH", "H_LB_OUT_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_EMPTY_MATCH", "H_LB_EMPTY_MATCH");
$content_tpl->set_block("F_CONTENT", "B_LB_PLAYER1", "H_LB_PLAYER1");
$content_tpl->set_block("F_CONTENT", "B_LB_PLAYER2", "H_LB_PLAYER2");
$content_tpl->set_block("F_CONTENT", "B_LOSERS_BRACKET", "H_LOSERS_BRACKET");

if (($season['status'] == "bracket" or $season['status'] == "running" or $season['status'] == "finished"))
{
  if ($_REQUEST['opt'] == "")
  {
    $_REQUEST['opt'] = "wb";
  }

  if ($_REQUEST['opt'] == "wb")
  {
    // WB
    // Set the wb_round names
    $num_wb_rounds = getNumWBRounds($season);
    for ($i = 0; $i <= $num_wb_rounds; ++$i)
    {
      $counter_wb_player[$i] = 0;
      $counter_wb_match[$i] = 1;
      $content_tpl->set_var("I_ROUND", $i + 1);

      // Deadline
      $content_tpl->set_var("H_WB_ACTUAL_ROUND", "");
      $content_tpl->set_var("H_WB_DEADLINE", "");
      $wb_round = $i + 1;
      $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			      "WHERE `id_season` = {$season['id']} AND `round` = 'wb$wb_round'");
      if ($deadline_row = dbFetch($deadline_ref))
      {
	if (isAfterPreDeadline($season, "wb", $i + 1) and isBeforePostDeadline($season, "wb", $i + 1))
	{
	  $content_tpl->parse("H_WB_ACTUAL_ROUND", "B_WB_ACTUAL_ROUND");
	}
	$content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
	$content_tpl->parse("H_WB_DEADLINE", "B_WB_DEADLINE");
      }

      $content_tpl->parse("H_WB_ROUND", "B_WB_ROUND", true);
    }

    // Calculate the order of the wb_rounds
    $order_wb_rounds = array(1);
    for ($i = 1; $i <= $num_wb_rounds; $i++)
    {
      $order_wb_rounds = array_merge($order_wb_rounds, array($i + 1), $order_wb_rounds);
    }

    // Set the matches
    foreach ($order_wb_rounds as $round)
    {
      $matchkey = "wb-" . $round . "-" . $counter_wb_match[$round - 1];
      $pre_space = ($round - 1) * 2;
      if ($pre_space == 0) $pre_space = 1;
      $counter_wb_player[$round - 1]++;
      if ($counter_wb_player[$round - 1] == 2)
      {
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	if (isset($matches[$matchkey]))
	{
          if ($matches[$matchkey]['id_player2'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country2']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player2']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player2']);
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	}
	$content_tpl->parse("H_WB_PLAYER1", "B_WB_PLAYER2", true);
	$counter_wb_player[$round - 1] = 0;
	$counter_wb_match[$round - 1]++;
      }
      elseif ($round == $num_wb_rounds + 1)
      {
	$matchkey = "wb-" . ($round - 1) . "-1";
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00" and
	    $matches[$matchkey]['score_p1'] > $matches[$matchkey]['score_p2'])
	{
          if ($matches[$matchkey]['id_player1'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country1']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player1']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player1']);
	}
	elseif ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00" and
		$matches[$matchkey]['score_p1'] < $matches[$matchkey]['score_p2'])
	{
          if ($matches[$matchkey]['id_player2'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country2']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player2']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player2']);
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	}
	$content_tpl->parse("H_WB_PLAYER1", "B_WB_PLAYER2", true);
	$counter_wb_player[$round - 1] = 0;
	$counter_wb_match[$round - 1]++;
      }
      else
      {
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	$content_tpl->set_var("I_V_SPACE", pow(2, $round) + 1);
	$content_tpl->parse("H_WB_PLAYED_MATCH", "");
	$content_tpl->parse("H_WB_UNPLAYED_MATCH", "");
	$content_tpl->parse("H_WB_WO_MATCH", "");
	$content_tpl->parse("H_WB_BYE_MATCH", "");
	$content_tpl->parse("H_WB_OUT_MATCH", "");
	$content_tpl->parse("H_WB_EMPTY_MATCH", "");
	if (isset($matches[$matchkey]))
	{
          if ($matches[$matchkey]['id_player1'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country1']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player1']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player1']);
	  $content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	  if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	  {
	    if ($matches[$matchkey]['wo'] != 0)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_WB_WO_MATCH", "B_WB_WO_MATCH");
	    }
	    elseif ($matches[$matchkey]['bye'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_WB_BYE_MATCH", "B_WB_BYE_MATCH");
	    }
	    elseif ($matches[$matchkey]['out'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_WB_OUT_MATCH", "B_WB_OUT_MATCH");
	    }
	    else
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_WB_PLAYED_MATCH", "B_WB_PLAYED_MATCH");
	    }
	  }
	  else
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("H_WB_UNPLAYED_MATCH", "B_WB_UNPLAYED_MATCH");
	  }
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	  $content_tpl->parse("H_WB_EMPTY_MATCH", "B_WB_EMPTY_MATCH");
	}
	$content_tpl->parse("H_WB_PLAYER1", "B_WB_PLAYER1", true);
      }
    }

    if ($season['double_elimination'] != "")
    {
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_WB_DOUBLE_ELIMINATION", "B_WB_DOUBLE_ELIMINATION");
    }
    else
    {
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_WB_SINGLE_ELIMINATION", "B_WB_SINGLE_ELIMINATION");
    }
    $content_tpl->parse("H_WINNERS_BRACKET", "B_WINNERS_BRACKET");
    $content_tpl->parse("H_RESULTS", "B_RESULTS");
  }
  elseif ($_REQUEST['opt'] == "lb" and $season['double_elimination'] != "")
  {
    // LB
    // Set the lb_round names
    $num_lb_rounds = getNumLBRounds($season);
    for ($i = 0; $i <= $num_lb_rounds; ++$i)
    {
      $counter_lb_player[$i] = 0;
      $counter_lb_match[$i] = 1;
      $content_tpl->set_var("I_ROUND", $i + 1);

      // Deadline
      $content_tpl->set_var("H_LB_ACTUAL_ROUND", "");
      $content_tpl->set_var("H_LB_DEADLINE", "");
      $lb_round = $i + 1;
      $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			      "WHERE `id_season` = {$season['id']} AND `round` = 'lb$lb_round'");
      if ($deadline_row = dbFetch($deadline_ref))
      {
	if (isAfterPreDeadline($season, "lb", $i + 1) and isBeforePostDeadline($season, "lb", $i + 1))
	{
	  $content_tpl->parse("H_LB_ACTUAL_ROUND", "B_LB_ACTUAL_ROUND");
	}
	$content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
	$content_tpl->parse("H_LB_DEADLINE", "B_LB_DEADLINE");
      }

      $content_tpl->parse("H_LB_ROUND", "B_LB_ROUND", true);
    }

    // Calculate the order of the lb_rounds
    $order_lb_rounds = array(1);
    for ($i = 1; $i <= $num_lb_rounds / 2; ++$i)
    {
      $order_lb_rounds = array_merge(array($i * 2), array($i * 2 + 1), $order_lb_rounds, array($i * 2), $order_lb_rounds);
    }

    // Set the matches
    foreach ($order_lb_rounds as $round)
    {
      $matchkey = "lb-" . $round . "-" . $counter_lb_match[$round - 1];
      if (($round / 2) == ceil ($round / 2))
      {
	if ($counter_lb_player[$round - 1] == 0) $pre_space = $round * 2 - 1;
	else $pre_space = ($round - 1) * 2;
	$content_tpl->set_var("I_V_SPACE", pow(2, $round / 2 + 1));
      }
      else
      {
	$pre_space = $round * 2 - 1;
	$content_tpl->set_var("I_V_SPACE", pow(2, ($round + 1) / 2 + 1) - 1);
      }
      $counter_lb_player[$round - 1]++;
      if ($counter_lb_player[$round - 1] == 2)
      {
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	if (isset($matches[$matchkey]))
	{
          if ($matches[$matchkey]['id_player2'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country2']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player2']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player2']);
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	}
	$content_tpl->parse("H_LB_PLAYER1", "B_LB_PLAYER2", true);

	$counter_lb_player[$round - 1] = 0;
	$counter_lb_match[$round - 1]++;
      }
      elseif ($round == $num_lb_rounds + 1)
      {
	$matchkey = "lb-" . ($round - 1) . "-1";
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00" and
	    $matches[$matchkey]['score_p1'] > $matches[$matchkey]['score_p2'])
	{
          if ($matches[$matchkey]['id_player1'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country1']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player1']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player1']);
	}
	elseif ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00" and
		$matches[$matchkey]['score_p1'] < $matches[$matchkey]['score_p2'])
	{
          if ($matches[$matchkey]['id_player2'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country2']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player2']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player2']);
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	}
	$content_tpl->parse("H_LB_PLAYER1", "B_LB_PLAYER2", true);
	$counter_lb_player[$round - 1] = 0;
	$counter_lb_match[$round - 1]++;
      }
      else
      {
	$content_tpl->set_var("I_PRE_SPACE", $pre_space);
	$content_tpl->parse("H_LB_PLAYED_MATCH", "");
	$content_tpl->parse("H_LB_UNPLAYED_MATCH", "");
	$content_tpl->parse("H_LB_WO_MATCH", "");
	$content_tpl->parse("H_LB_BYE_MATCH", "");
	$content_tpl->parse("H_LB_OUT_MATCH", "");
	$content_tpl->parse("H_LB_EMPTY_MATCH", "");
	if (isset($matches[$matchkey]))
	{
          if ($matches[$matchkey]['id_player1'] != 0)
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", htmlspecialchars($matches[$matchkey]['country1']));
          }
          else
          {
	    $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
          }
	  $content_tpl->set_var("I_PLAYER", htmlspecialchars($matches[$matchkey]['player1']));
	  $content_tpl->set_var("I_ID_USER", $matches[$matchkey]['id_player1']);
	  $content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	  if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	  {
	    if ($matches[$matchkey]['wo'] != 0)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_LB_WO_MATCH", "B_LB_WO_MATCH");
	    }
	    elseif ($matches[$matchkey]['bye'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_LB_BYE_MATCH", "B_LB_BYE_MATCH");
	    }
	    elseif ($matches[$matchkey]['out'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_LB_OUT_MATCH", "B_LB_OUT_MATCH");
	    }
	    else
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("H_LB_PLAYED_MATCH", "B_LB_PLAYED_MATCH");
	    }
	  }
	  else
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("H_LB_UNPLAYED_MATCH", "B_LB_UNPLAYED_MATCH");
	  }
	}
	else
	{
	  $content_tpl->set_var("I_COUNTRY_ABBREVIATION", "00");
	  $content_tpl->set_var("I_PLAYER", "");
	  $content_tpl->set_var("I_ID_USER", "");
	  $content_tpl->parse("H_LB_EMPTY_MATCH", "B_LB_EMPTY_MATCH");
	}
	$content_tpl->parse("H_LB_PLAYER1", "B_LB_PLAYER1", true);
      }
    }

    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_LOSERS_BRACKET", "B_LOSERS_BRACKET");
    $content_tpl->parse("H_RESULTS", "B_RESULTS");
  }
}
else
{
  $content_tpl->parse("H_NO_BRACKET", "B_NO_BRACKET");
}

?>
