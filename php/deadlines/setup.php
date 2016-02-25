<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_SYSTEM", "H_WARNING_TOURNEY_SYSTEM");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_LB", "H_LB");
$content_tpl->set_block("F_CONTENT", "B_SETUP_DEADLINES", "H_SETUP_DEADLINES");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['single_elimination'] == "")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_SYSTEM", "B_WARNING_TOURNEY_SYSTEM");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    if ($season['double_elimination'] != "")
    {
      $content_tpl->parse("H_LB", "B_LB");
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_SETUP_DEADLINES", "B_SETUP_DEADLINES");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
