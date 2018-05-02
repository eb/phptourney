<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_RULE_REMOVED", "H_MESSAGE_RULE_REMOVED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

if ($user['usertype_headadmin'])
{
  $id_rule = intval($_REQUEST['opt']);
  dbQuery("DELETE FROM `rules` WHERE `id` = $id_rule");
  $content_tpl->parse("H_MESSAGE_RULE_REMOVED", "B_MESSAGE_RULE_REMOVED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
