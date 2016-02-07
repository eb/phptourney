<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_BRACKET_CREATED", "H_MESSAGE_BRACKET_CREATED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_TOURNEY_RUNNING", "H_MESSAGE_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_CREATE_BRACKET", "H_CREATE_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_START_TOURNEY", "H_START_TOURNEY");
$content_tpl->set_block("F_CONTENT", "B_UNDO_BRACKET", "H_UNDO_BRACKET");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "running")
  {
    $content_tpl->parse("H_MESSAGE_TOURNEY_RUNNING", "B_MESSAGE_TOURNEY_RUNNING");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
  elseif ($season['status'] == "bracket")
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_START_TOURNEY", "B_START_TOURNEY");
    $content_tpl->parse("H_UNDO_BRACKET", "B_UNDO_BRACKET");
  }
  else
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_CREATE_BRACKET", "B_CREATE_BRACKET");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
