<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_BANS", "H_NO_BANS");
$content_tpl->set_block("F_CONTENT", "B_BAN", "H_BAN");
$content_tpl->set_block("F_CONTENT", "B_BANS", "H_BANS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_BANS", "H_OVERVIEW_BANS");

// Access for headadmins only
if ($user['usertype_admin'])
{
  $bans_ref = dbQuery("SELECT * FROM `bans` " .
		       "WHERE `id_season` = {$season['id']} ORDER BY `ip` ASC");
  if (dbNumRows($bans_ref) <= 0)
  {
    $content_tpl->parse("H_NO_BANS", "B_NO_BANS");
  }
  else
  {
    $ban_counter = 0;
    while ($bans_row = dbFetch($bans_ref))
    {
      $content_tpl->set_var("I_BAN_COUNTER", ++$ban_counter);
      $content_tpl->set_var("I_ID_BAN", $bans_row['id']);
      $content_tpl->set_var("I_IP", htmlspecialchars($bans_row['ip']));
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_BAN", "B_BAN", true);
    }
    $content_tpl->parse("H_BANS", "B_BANS");
  }
  $content_tpl->parse("H_OVERVIEW_BANS", "B_OVERVIEW_BANS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
