<?php

################################################################################
#
# $Id: view_text.php,v 1.2 2006/03/25 22:38:22 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_NO_BRACKET", "H_NO_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_MATCH", "H_MATCH");
$content_tpl->set_block("F_CONTENT", "B_PLAYED_MATCH", "H_PLAYED_MATCH");
$content_tpl->set_block("F_CONTENT", "B_WO_MATCH", "H_WO_MATCH");
$content_tpl->set_block("F_CONTENT", "B_BYE_MATCH", "H_BYE_MATCH");
$content_tpl->set_block("F_CONTENT", "B_OUT_MATCH", "H_OUT_MATCH");
$content_tpl->set_block("F_CONTENT", "B_EMPTY_MATCH", "H_EMPTY_MATCH");
$content_tpl->set_block("F_CONTENT", "B_Q_ACTUAL_ROUND", "H_Q_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_Q_DEADLINE", "H_Q_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_Q_ROUND", "H_Q_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WB_ACTUAL_ROUND", "H_WB_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WB_DEADLINE", "H_WB_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_WB_ROUND", "H_WB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_GF_ACTUAL_ROUND", "H_GF_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_GF_DEADLINE", "H_GF_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_GF_ROUND", "H_GF_ROUND");
$content_tpl->set_block("F_CONTENT", "B_WB_DOUBLE_ELIMINATION", "H_WB_DOUBLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WB_SINGLE_ELIMINATION", "H_WB_SINGLE_ELIMINATION");
$content_tpl->set_block("F_CONTENT", "B_WB_BRACKET", "H_WB_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_LB_ACTUAL_ROUND", "H_LB_ACTUAL_ROUND");
$content_tpl->set_block("F_CONTENT", "B_LB_DEADLINE", "H_LB_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_LB_ROUND", "H_LB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_LB_BRACKET", "H_LB_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_BRACKET", "H_BRACKET");

$content_tpl->set_var("I_ID_SEASON", $season['id']);

if (($season['status'] == "bracket" and $user['usertype_admin']) or $season['status'] == "running" or $season['status'] == "finished")
{
  ////////////////////////////////////////////////////////////////////////////////
  // qualification
  ////////////////////////////////////////////////////////////////////////////////

  if ($season['qualification'] == 1)
  {
    $content_tpl->set_var("I_MATCH", "");
    for ($j = 1; $j <= $season['single_elimination'] / 2; $j++)
    {
      $matchkey = "q-1-$j";
      if (!empty($matches[$matchkey]))
      {
	$content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	$content_tpl->set_var("I_MATCH_NUMBER", $j);
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($matches[$matchkey]['player1']));
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($matches[$matchkey]['player2']));

	if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	{
	  if ($matches[$matchkey]['wo'] != 0)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_WO_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['out'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_OUT_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['bye'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_BYE_MATCH", true);
	  }
	  else
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_PLAYED_MATCH", true);
	  }
	}
	else
	{
	  $content_tpl->parse("I_MATCH", "B_MATCH", true);
	}
      }
      else
      {
	$content_tpl->parse("I_MATCH", "B_EMPTY_MATCH", true);
      }
    }

    // deadline
    $content_tpl->set_var("H_Q_ACTUAL_ROUND", "");
    $content_tpl->set_var("H_Q_DEADLINE", "");
    $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			    "WHERE `id_season` = {$season['id']} AND `round` = 'q1'");
    if ($deadline_row = dbFetch($deadline_ref))
    {
      if (isAfterPreDeadline($season, "q", 1) and isBeforePostDeadline($season, "q", 1))
      {
	$content_tpl->parse("H_Q_ACTUAL_ROUND", "B_Q_ACTUAL_ROUND");
      }
      $content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
      $content_tpl->parse("H_Q_DEADLINE", "B_Q_DEADLINE");
    }

    $content_tpl->parse("H_Q_ROUND", "B_Q_ROUND");
  }

  ////////////////////////////////////////////////////////////////////////////////
  // winners bracket
  ////////////////////////////////////////////////////////////////////////////////

  for ($i = 1; $i <= getNumWBRounds($season); $i++)
  {
#    $num_empty_matches = pow(2, $i - 1) - 1;
    $content_tpl->set_var("I_MATCH", "");
    for ($j = 1; $j <= getNumWBMatches($season, $i); $j++)
    {
      $matchkey = "wb-$i-$j";
      if (!empty($matches[$matchkey]))
      {
	$content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	$content_tpl->set_var("I_MATCH_NUMBER", $j);
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($matches[$matchkey]['player1']));
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($matches[$matchkey]['player2']));

	if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	{
	  if ($matches[$matchkey]['wo'] != 0)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_WO_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['out'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_OUT_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['bye'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_BYE_MATCH", true);
	  }
	  else
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_PLAYED_MATCH", true);
	  }
	}
	else
	{
	  $content_tpl->parse("I_MATCH", "B_MATCH", true);
	}
      }
#      for ($k = 0; $k < $num_empty_matches; $k++)
#      {
#	$content_tpl->parse("I_MATCH", "B_EMPTY_MATCH", true);
#      }
    }

    $content_tpl->set_var("I_ROUND", $i);

    // deadline
    $content_tpl->set_var("H_WB_ACTUAL_ROUND", "");
    $content_tpl->set_var("H_WB_DEADLINE", "");
    $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			     "WHERE `id_season` = {$season['id']} AND `round` = 'wb$i'");
    if ($deadline_row = dbFetch($deadline_ref))
    {
      if (isAfterPreDeadline($season, "wb", $i) and isBeforePostDeadline($season, "wb", $i))
      {
	$content_tpl->parse("H_WB_ACTUAL_ROUND", "B_WB_ACTUAL_ROUND");
      }
      $content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
      $content_tpl->parse("H_WB_DEADLINE", "B_WB_DEADLINE");
    }
    $content_tpl->parse("H_WB_ROUND", "B_WB_ROUND", true);
  }

  ////////////////////////////////////////////////////////////////////////////////
  // grand finale
  ////////////////////////////////////////////////////////////////////////////////

  if ($season['double_elimination'] != "")
  {
    $content_tpl->set_var("I_MATCH", "");
    for ($j = 1; $j <= 2; $j++)
    {
      $matchkey = "gf-1-$j";
      if (!empty($matches[$matchkey]))
      {
	$content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	$content_tpl->set_var("I_MATCH_NUMBER", $j);
	$content_tpl->set_var("I_PLAYER1", htmlspecialchars($matches[$matchkey]['player1']));
	$content_tpl->set_var("I_PLAYER2", htmlspecialchars($matches[$matchkey]['player2']));

	if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	{
	  if ($matches[$matchkey]['wo'] != 0)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_WO_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['out'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_OUT_MATCH", true);
	  }
	  elseif ($matches[$matchkey]['bye'] == 1)
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_BYE_MATCH", true);
	  }
	  else
	  {
	    $content_tpl->set_var("I_ID_SEASON", $season['id']);
	    $content_tpl->parse("I_MATCH", "B_PLAYED_MATCH", true);
	  }
	}
	elseif ($matches[$matchkey]['match'] == 1 or hasNextWinnerMatch($matches["gf-1-1"]))
	{
	  $content_tpl->parse("I_MATCH", "B_MATCH", true);
	}
      }
    }

    // deadline
    $content_tpl->set_var("H_GF_ACTUAL_ROUND", "");
    $content_tpl->set_var("H_GF_DEADLINE", "");
    $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			     "WHERE `id_season` = {$season['id']} AND `round` = 'gf1'");
    if ($deadline_row = dbFetch($deadline_ref))
    {
      if (isAfterPreDeadline($season, "gf", 1) and isBeforePostDeadline($season, "gf", 1))
      {
	$content_tpl->parse("H_GF_ACTUAL_ROUND", "B_GF_ACTUAL_ROUND");
      }
      $content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
      $content_tpl->parse("H_GF_DEADLINE", "B_GF_DEADLINE");
    }

    $content_tpl->parse("H_GF_ROUND", "B_GF_ROUND");
  }
  if ($season['double_elimination'] != "")
  {
    $content_tpl->parse("H_WB_DOUBLE_ELIMINATION", "B_WB_DOUBLE_ELIMINATION");
  }
  else
  {
    $content_tpl->parse("H_WB_SINGLE_ELIMINATION", "B_WB_SINGLE_ELIMINATION");
  }
  $content_tpl->parse("H_WB_BRACKET", "B_WB_BRACKET");

  ////////////////////////////////////////////////////////////////////////////////
  // losers bracket
  ////////////////////////////////////////////////////////////////////////////////

  if ($season['double_elimination'] != "")
  {
    for ($i = 1; $i <= getNumLBRounds($season); $i++)
    {
      $content_tpl->set_var("I_MATCH", "");
      for ($j = 1; $j <= getNumLBMatches($season, $i); $j++)
      {
	$matchkey = "lb-$i-$j";
	if (!empty($matches[$matchkey]))
        {
	  $content_tpl->set_var("I_ID_MATCH", $matches[$matchkey]['id']);
	  $content_tpl->set_var("I_MATCH_NUMBER", $j);
	  $content_tpl->set_var("I_PLAYER1", htmlspecialchars($matches[$matchkey]['player1']));
	  $content_tpl->set_var("I_PLAYER2", htmlspecialchars($matches[$matchkey]['player2']));

	  if ($matches[$matchkey]['confirmed'] != "0000-00-00 00:00:00")
	  {
	    if ($matches[$matchkey]['wo'] != 0)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("I_MATCH", "B_WO_MATCH", true);
	    }
	    elseif ($matches[$matchkey]['out'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("I_MATCH", "B_OUT_MATCH", true);
	    }
	    elseif ($matches[$matchkey]['bye'] == 1)
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("I_MATCH", "B_BYE_MATCH", true);
	    }
	    else
	    {
	      $content_tpl->set_var("I_ID_SEASON", $season['id']);
	      $content_tpl->parse("I_MATCH", "B_PLAYED_MATCH", true);
	    }
	  }
	  else
	  {
	    $content_tpl->parse("I_MATCH", "B_MATCH", true);
	  }
	}
      }

      $content_tpl->set_var("I_ROUND", $i);

      // deadline
      $content_tpl->set_var("H_LB_ACTUAL_ROUND", "");
      $content_tpl->set_var("H_LB_DEADLINE", "");
      $deadline_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}deadlines` " .
			      "WHERE `id_season` = {$season['id']} AND `round` = 'lb$i'");
      if ($deadline_row = dbFetch($deadline_ref))
      {
	if (isAfterPreDeadline($season, "lb", $i) and isBeforePostDeadline($season, "lb", $i))
	{
	  $content_tpl->parse("H_LB_ACTUAL_ROUND", "B_LB_ACTUAL_ROUND");
	}
	$content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadline_row['deadline']));
	$content_tpl->parse("H_LB_DEADLINE", "B_LB_DEADLINE");
      }
      $content_tpl->parse("H_LB_ROUND", "B_LB_ROUND", true);
    }
    $content_tpl->parse("H_LB_BRACKET", "B_LB_BRACKET");
  }
  $content_tpl->parse("H_BRACKET", "B_BRACKET");
}
else
{
  $content_tpl->parse("H_NO_BRACKET", "B_NO_BRACKET");
}

?>
