<?php

################################################################################
#
# $Id: add.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_ADD_Q_ROUND", "H_ADD_Q_ROUND");
$content_tpl->set_block("F_CONTENT", "B_ADD_WB_ROUND", "H_ADD_WB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_ADD_LB_ROUND", "H_ADD_LB_ROUND");
$content_tpl->set_block("F_CONTENT", "B_ADD_GF_ROUND", "H_ADD_GF_ROUND");
$content_tpl->set_block("F_CONTENT", "B_ADD_DEADLINE", "H_ADD_DEADLINE");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['qualification'] == 1)
  {
    $content_tpl->set_var("I_ROUND", 0);
    $content_tpl->parse("H_ADD_Q_ROUND", "B_ADD_Q_ROUND", true);
    $content_tpl->set_var("I_ROUND", 1);
    $content_tpl->parse("H_ADD_Q_ROUND", "B_ADD_Q_ROUND", true);
  }
  if ($season['single_elimination'] == "")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_SYSTEM", "B_WARNING_TOURNEY_SYSTEM");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    for ($i = 0; $i <= getNumWBRounds($season); $i++)
    {
      $content_tpl->set_var("I_ROUND", $i);
      $content_tpl->parse("H_ADD_WB_ROUND", "B_ADD_WB_ROUND", true);
    }
    for ($i = 0; $i <= getNumLBRounds($season); $i++)
    {
      $content_tpl->set_var("I_ROUND", $i);
      $content_tpl->parse("H_ADD_LB_ROUND", "B_ADD_LB_ROUND", true);
    }

    $content_tpl->set_var("I_ROUND", 0);
    $content_tpl->parse("H_ADD_GF_ROUND", "B_ADD_GF_ROUND", true);
    $content_tpl->set_var("I_ROUND", 1);
    $content_tpl->parse("H_ADD_GF_ROUND", "B_ADD_GF_ROUND", true);

    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_ADD_DEADLINE", "B_ADD_DEADLINE");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
