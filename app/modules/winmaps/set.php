<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_WINMAPS_SET", "H_MESSAGE_WINMAPS_SET");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_NOT_RUNNING", "H_WARNING_TOURNEY_NOT_RUNNING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_TOURNEY_FINISHED", "H_WARNING_TOURNEY_FINISHED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] != "running")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_NOT_RUNNING", "B_WARNING_TOURNEY_NOT_RUNNING");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  elseif ($season['status'] == "finished")
  {
    $content_tpl->parse("H_WARNING_TOURNEY_FINISHED", "B_WARNING_TOURNEY_FINISHED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  else
  {
    $matches_ref = dbQuery("SELECT * FROM `matches` " .
			    "WHERE `id_season` = {$season['id']}");
    while ($matches_row = dbFetch($matches_ref))
    {
      if (isset($_REQUEST[$matches_row['id']]))
      {
        $num_winmaps = intval($_REQUEST[$matches_row['id']]);
	dbQuery("UPDATE `matches` SET " .
		 "`num_winmaps` = $num_winmaps " .
		 "WHERE `id` = {$matches_row['id']}");
      }
    }
    $content_tpl->parse("H_MESSAGE_WINMAPS_SET", "B_MESSAGE_WINMAPS_SET");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
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
