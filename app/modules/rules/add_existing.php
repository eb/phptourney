<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_COPIED", "H_MESSAGE_COPIED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SEASON", "H_WARNING_SEASON");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins
if ($user['usertype_headadmin'])
{
  $id_season = intval($_REQUEST['id_season']);
  $seasons_ref = dbQuery("SELECT * FROM `seasons` " .
			 "WHERE `id` = $id_season");
  if ($seasons_row = dbFetch($seasons_ref))
  {
    $rules_ref = dbQuery("SELECT * FROM `rules` WHERE `id_season` = {$seasons_row['id']}");
    while ($rules_row = dbFetch($rules_ref))
    {
      $subject = dbEscape($rules_row['subject']);
      $body = dbEscape($rules_row['body']);
      dbQuery("INSERT INTO `rules` (`id_season`, `subject`, `body`) " .
	      "VALUES ({$season['id']}, '$subject', '$body')");
    }
    $content_tpl->parse("H_MESSAGE_COPIED", "B_MESSAGE_COPIED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    $content_tpl->parse("H_WARNING_SEASON", "B_WARNING_SEASON");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
