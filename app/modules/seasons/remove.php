<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_SEASON", "H_REMOVE_SEASON");

// Access for root only
if ($user['usertype_root'])
{
  $id_season = intval($_REQUEST['opt']);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->set_var("I_ID_SEASON_OPT", $id_season);
  $content_tpl->parse("H_REMOVE_SEASON", "B_REMOVE_SEASON");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
