<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PLAYER_REJECTED", "H_MESSAGE_PLAYER_REJECTED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_RUNNING", "H_WARNING_TOURNEY_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
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
    $id_user = intval($_REQUEST['opt']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}season_users` SET `rejected` = 1 " .
	     "WHERE `id_user` = $id_user AND `id_season` = {$season['id']}");
    $content_tpl->parse("H_MESSAGE_PLAYER_REJECTED", "B_MESSAGE_PLAYER_REJECTED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
