<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_DEADLINES", "H_NO_DEADLINES");
$content_tpl->set_block("F_CONTENT", "B_DEADLINE", "H_DEADLINE");
$content_tpl->set_block("F_CONTENT", "B_DEADLINES", "H_DEADLINES");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_DEADLINES", "H_OVERVIEW_DEADLINES");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $deadlines_ref = dbQuery("SELECT * FROM `deadlines` " .
			    "WHERE `id_season` = {$season['id']} ORDER BY `deadline` ASC");
  if (dbNumRows($deadlines_ref) <= 0)
  {
    $content_tpl->parse("H_NO_DEADLINES", "B_NO_DEADLINES");
  }
  else
  {
    $deadline_counter = 0;
    while ($deadlines_row = dbFetch($deadlines_ref))
    {
      $content_tpl->set_var("I_DEADLINE_COUNTER", ++$deadline_counter);
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->set_var("I_ID_DEADLINE", $deadlines_row['id']);
      $content_tpl->set_var("I_ROUND", htmlspecialchars($deadlines_row['round']));
      $content_tpl->set_var("I_DEADLINE", htmlspecialchars($deadlines_row['deadline']));
      $content_tpl->parse("H_DEADLINE", "B_DEADLINE", true);
    }
    $content_tpl->parse("H_DEADLINES", "B_DEADLINES");
  }
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_OVERVIEW_DEADLINES", "B_OVERVIEW_DEADLINES");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
