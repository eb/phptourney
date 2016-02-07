<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

define("MAX_ROUNDS_SE", 9);
define("MIN_ROUNDS_SE", 1);
define("MAX_ROUNDS_DE", 9);
define("MIN_ROUNDS_DE", 2);

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_QUALIFICATION_CHECKED", "H_QUALIFICATION_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_QUALIFICATION_UNCHECKED", "H_QUALIFICATION_UNCHECKED");
$content_tpl->set_block("F_CONTENT", "B_SINGLE_ELIMINATION_SELECTED", "H_SINGLE_ELIMINATION_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_SINGLE_ELIMINATION_UNSELECTED", "H_SINGLE_ELIMINATION_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_DOUBLE_ELIMINATION_SELECTED", "H_DOUBLE_ELIMINATION_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_DOUBLE_ELIMINATION_UNSELECTED", "H_DOUBLE_ELIMINATION_UNSELECTED");
$content_tpl->set_block("F_CONTENT", "B_CHOOSE_TOURNEY_SYSTEM", "H_CHOOSE_TOURNEY_SYSTEM");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  // qualification
  if ($season['qualification'] == 1)
  {
      $content_tpl->parse("H_QUALIFICATION_CHECKED", "B_QUALIFICATION_CHECKED");
  }
  elseif ($season['qualification'] == 0)
  {
      $content_tpl->parse("H_QUALIFICATION_CHECKED", "B_QUALIFICATION_UNCHECKED");
  }

  // single elimination
  for ($i = MAX_ROUNDS_SE; $i >= MIN_ROUNDS_SE; $i--)
  {
    $num_participants = pow(2, $i);
    $content_tpl->set_var("I_SINGLE_ELIMINATION", $num_participants);
    if ($num_participants == $season['single_elimination'])
    {
      $content_tpl->parse("H_SINGLE_ELIMINATION_SELECTED", "B_SINGLE_ELIMINATION_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_SINGLE_ELIMINATION_SELECTED", "B_SINGLE_ELIMINATION_UNSELECTED", true);
    }
  }

  // double elimination
  for ($i = MAX_ROUNDS_DE; $i >= MIN_ROUNDS_DE; $i--)
  {
    $num_participants = pow(2, $i);
    $content_tpl->set_var("I_DOUBLE_ELIMINATION", $num_participants);
    if ($num_participants == $season['double_elimination'])
    {
      $content_tpl->parse("H_DOUBLE_ELIMINATION_SELECTED", "B_DOUBLE_ELIMINATION_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_DOUBLE_ELIMINATION_SELECTED", "B_DOUBLE_ELIMINATION_UNSELECTED", true);
    }
  }

  // win-maps
  $content_tpl->set_var("I_WINMAPS", $season['winmaps']);

  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_CHOOSE_TOURNEY_SYSTEM", "B_CHOOSE_TOURNEY_SYSTEM");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
