<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_RULE", "H_EDIT_RULE");

if ($user['usertype_headadmin'])
{
  $id_rule = intval($_REQUEST['opt']);
  $rules_ref = dbQuery("SELECT * FROM `rules` WHERE `id` = $id_rule");
  $rules_row = dbFetch($rules_ref);
  $content_tpl->set_var("I_ID_RULE", $id_rule);
  $content_tpl->set_var("I_SUBJECT", htmlspecialchars($rules_row['subject']));
  $content_tpl->set_var("I_BODY", htmlspecialchars($rules_row['body']));
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_RULE", "B_EDIT_RULE");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
