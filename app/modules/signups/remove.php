<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_PLAYER", "H_REMOVE_PLAYER");

// Access for headadmins
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_RUNNING", "B_WARNING_TOURNEY_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    $id_player = intval($_REQUEST['opt']);
    $content_tpl->set_var("I_ID_PLAYER", $id_player);
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_REMOVE_PLAYER", "B_REMOVE_PLAYER");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
