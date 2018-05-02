<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MAP_ADDED", "H_MESSAGE_MAP_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_MAP", "H_WARNING_MAP");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{
  $is_complete = 1;
  if ($_REQUEST['map'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_MAP", "B_WARNING_MAP");
  }

  if ($is_complete)
  {
    $map = dbEscape($_REQUEST['map']);
    dbQuery("INSERT INTO `mappool` (`map`, `id_season`) " .
	     "VALUES ('$map', {$season['id']})");
    $content_tpl->parse("H_MESSAGE_MAP_ADDED", "B_MESSAGE_MAP_ADDED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }

  if (!$is_complete)
  {
    if (isset($_REQUEST['map']))
    {
      $content_tpl->set_var("I_MAP", htmlspecialchars($_REQUEST['map']));
    }
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
