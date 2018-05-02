<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_MAP", "H_EDIT_MAP");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_map = intval($_REQUEST['opt']);
  $maps_ref = dbQuery("SELECT * FROM `mappool` WHERE `id` = $id_map AND `deleted` = 0");
  $maps_row = dbFetch($maps_ref);
  $content_tpl->set_var("I_ID_MAP", $id_map);
  $content_tpl->set_var("I_MAP", htmlspecialchars($maps_row['map']));
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_MAP", "B_EDIT_MAP");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
