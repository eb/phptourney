<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_NEWS_PUBLISHED", "H_MESSAGE_NEWS_PUBLISHED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// Access for headadmins
// Access for admins that wrote the news
if ($news_row['id_news_group'] == 2 and ($user['usertype_headadmin'] or $user['uid'] == $news_row['id_user']))
{
  dbQuery("UPDATE `{$cfg['db_table_prefix']}news` " .
	   "SET `id_news_group` = 1, " .
	   "`submitted` = NOW() " .
	   "WHERE `id` = {$news_row['id']}");
  dbQuery("UPDATE `{$cfg['db_table_prefix']}news_comments` " .
	  "SET `deleted` = 1 " .
	  "WHERE `id_news` = {$news_row['id']}");

  // Send news to irc
  if ($cfg['bot_enabled'] and $cfg['bot_public_targets'] != "")
  {
    $irc_channels = explode(";", $cfg['bot_public_targets']);
    foreach($irc_channels as $irc_channel) {
      if ($cfg['bot_host'] != "" and $cfg['bot_port'] != "")
      {
	$bot_socket = fsockopen($cfg['bot_host'], $cfg['bot_port']);
      }
      else
      {
	$bot_socket = NULL;
      }
      if ($bot_socket)
      {
	sleep(2);
	fwrite($bot_socket,
		"{$cfg['bot_password']} $irc_channel {$cfg['tourney_name']} news updated: '{$news_row['heading']}' - " .
		"{$cfg['host']}{$cfg['path']}?sid={$season['id']}\r\n");
	fclose($bot_socket);
      }
    }
  }

  $content_tpl->parse("H_MESSAGE_NEWS_PUBLISHED", "B_MESSAGE_NEWS_PUBLISHED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
