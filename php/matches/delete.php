<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_MATCH_REMOVED", "H_MESSAGE_MATCH_REMOVED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_DELETE", "H_WARNING_DELETE");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");

// Access for admins only
if ($user['usertype_admin'])
{
  $id_match = intval($_REQUEST['opt']);
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = $id_match");
  $matches_row = dbFetch($matches_ref);
  if (isLastMatch($matches_row))
  {
    dbQuery("UPDATE `{$cfg['db_table_prefix']}matches` SET " .
	     "`submitted` = '0000-00-00 00:00:00', " . 
	     "`confirmed` = '0000-00-00 00:00:00' " . 
	     "WHERE `id` = {$matches_row['id']}");

    // Delete screenshots
    for ($i = 1; $i <= $matches_row['num_winmaps'] * 2 - 1; $i++)
    {
      $sshot_dir = "data/screenshots/{$season['id']}/";
      $dst_file = $sshot_dir .
	"{$season['id']}-{$matches_row['bracket']}-{$matches_row['round']}-{$matches_row['match']}-m{$i}";
      if (file_exists($dst_file . ".jpg"))
      {
	unlink($dst_file . ".jpg");
      }
      if (file_exists($dst_file . "_thumb.jpg"))
      {
	unlink($dst_file . "_thumb.jpg");
      }
    }

    // Delete next matches
    $winner_match = insertWinnerMatch($matches_row, 0);
    $loser_match = insertLoserMatch($matches_row, 0);

    $content_tpl->parse("H_MESSAGE_MATCH_REMOVED", "B_MESSAGE_MATCH_REMOVED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }
  else
  {
    $content_tpl->parse("H_WARNING_DELETE", "B_WARNING_DELETE");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
