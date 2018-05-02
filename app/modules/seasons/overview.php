<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_SEASONS", "H_NO_SEASONS");
$content_tpl->set_block("F_CONTENT", "B_SEASON", "H_SEASON");
$content_tpl->set_block("F_CONTENT", "B_SEASONS", "H_SEASONS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_SEASONS", "H_OVERVIEW_SEASONS");

// Access for root only
if ($user['usertype_root'])
{
  $seasons_ref = dbQuery("SELECT * FROM `seasons` " .
			  "WHERE `deleted` = 0 ORDER BY `submitted` DESC");
  if (dbNumRows($seasons_ref) <= 0)
  {
    $content_tpl->parse("H_NO_SEASONS", "B_NO_SEASONS");
  }
  else
  {
    $season_counter = 0;
    while ($seasons_row = dbFetch($seasons_ref))
    {
      $content_tpl->set_var("I_SEASON_COUNTER", ++$season_counter);
      $content_tpl->set_var("I_ID_SEASON_OPT", $seasons_row['id']);
      $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($seasons_row['name']));
      $content_tpl->parse("H_SEASON", "B_SEASON", true);
    }
    $content_tpl->parse("H_SEASONS", "B_SEASONS");
  }
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_OVERVIEW_SEASONS", "B_OVERVIEW_SEASONS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
