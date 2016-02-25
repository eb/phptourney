<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_SEASON", "H_EDIT_SEASON");

// Access for root only
if ($user['usertype_root'])
{
  $id_season = intval($_REQUEST['opt']);
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `id` = $id_season AND `deleted`  = 0");
  $seasons_row = dbFetch($seasons_ref);
  $content_tpl->set_var("I_ID_SEASON_OPT", $id_season);
  $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($seasons_row['name']));

  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_SEASON", "B_EDIT_SEASON");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
