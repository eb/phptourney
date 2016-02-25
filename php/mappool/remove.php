<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_MAP", "H_REMOVE_MAP");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_map = intval($_REQUEST['opt']);
  $content_tpl->set_var("I_ID_MAP", $id_map);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_REMOVE_MAP", "B_REMOVE_MAP");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
