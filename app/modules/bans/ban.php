<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_IP_BANNED", "H_MESSAGE_IP_BANNED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_IP_ALREADY_BANNED", "H_WARNING_IP_ALREADY_BANNED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");

// Access for admins only
if ($user['usertype_admin'])
{
  $ip = dbEscape($_REQUEST['opt']);
  $bans_ref = dbQuery("SELECT * FROM `bans` " .
		       "WHERE `id_season` = {$season['id']} " .
		       "AND `ip` = '$ip'");
  if (dbNumRows($bans_ref) == 1)
  {
    $content_tpl->parse("H_WARNING_IP_ALREADY_BANNED", "B_WARNING_IP_ALREADY_BANNED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
  else
  {
    dbQuery("INSERT INTO `bans` (`id_season`, `ip`) " .
	     "VALUES ({$season['id']}, '$ip')");
    $content_tpl->parse("H_MESSAGE_IP_BANNED", "B_MESSAGE_IP_BANNED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
