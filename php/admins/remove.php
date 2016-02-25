<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_ADMIN", "H_REMOVE_ADMIN");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_user = intval($_REQUEST['opt']);
  $content_tpl->set_var("I_ID_USER", $id_user);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_REMOVE_ADMIN", "B_REMOVE_ADMIN");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
