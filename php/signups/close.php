<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_SIGNUPS_CLOSED", "H_WARNING_SIGNUPS_CLOSED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "signups")
  {
    dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `status` = '' WHERE `id` = {$season['id']}");
  }
  $content_tpl->parse("H_WARNING_SIGNUPS_CLOSED", "B_WARNING_SIGNUPS_CLOSED");
  $content_tpl->parse("H_WARNING", "B_WARNING");
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
