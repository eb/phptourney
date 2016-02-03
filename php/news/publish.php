<?php

################################################################################
#
# $Id: publish.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_NEWS_PUBLISHED", "H_MESSAGE_NEWS_PUBLISHED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// news-query
$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// access for headadmins
// access for admins that wrote the news
if ($news_row['id_news_group'] == 2 and ($user['usertype_headadmin'] or $user['uid'] == $news_row['id_user']))
{
  // news-query
  dbQuery("UPDATE `{$cfg['db_table_prefix']}news` " .
	   "SET `id_news_group` = 1, " .
	   "`submitted` = NOW() " .
	   "WHERE `id` = {$news_row['id']}");
  dbQuery("UPDATE `{$cfg['db_table_prefix']}news_comments` " .
	  "SET `deleted` = 1 " .
	  "WHERE `id_news` = {$news_row['id']}");

  // send news to irc
  if ($section['public_irc_channels'] != "")
  {
    $irc_channels = explode(";", $section['public_irc_channels']);
    foreach($irc_channels as $irc_channel) {
      if ($section['bot_host'] != "" and $section['bot_port'] != "")
      {
	$bot_socket = fsockopen($section['bot_host'], $section['bot_port']);
      }
      else
      {
	$bot_socket = NULL;
      }
      if ($bot_socket)
      {
	sleep(2);
	fwrite($bot_socket,
		"{$section['bot_password']} $irc_channel {$section['name']} news updated: '{$news_row['heading']}' - " .
		"{$cfg['host']}{$cfg['path']}?sid={$_REQUEST['sid']}\r\n");
	fclose($bot_socket);
      }
    }
  }

  $content_tpl->parse("H_MESSAGE_NEWS_PUBLISHED", "B_MESSAGE_NEWS_PUBLISHED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
