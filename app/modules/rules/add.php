<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_ADD_RULE", "H_ADD_RULE");

if ($user['usertype_headadmin'])
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_ADD_RULE", "B_ADD_RULE");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
