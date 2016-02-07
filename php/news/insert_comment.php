<?php

################################################################################
#
# $Id: insert_comment.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_COMMENT_ADDED", "H_MESSAGE_COMMENT_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SPAM", "H_WARNING_SPAM");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BAN", "H_WARNING_BAN");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// news-query
$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// access for admins [public / private news]
// access for users [public news]
if ($user['usertype_admin'] or $news_row['id_news_group'] == 1 and $user['uid'])
{
  $is_complete = 1;
  if ($_REQUEST['body'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_BODY", "B_WARNING_BODY");
  }
  // comments-query
  $minute = (date("i") + 55) % 60;
  $now = date("Y-m-d H:{$minute}:s");
  $comments_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news_comments` " .
			   "WHERE `id_news` = {$news_row['id']} " .
			   "AND `ip` = '{$_SERVER['REMOTE_ADDR']}' " .
			   "AND `submitted` > '$now'");
  if (dbNumRows($comments_ref) > 0 and $user['usertype_admin'] == 0)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SPAM", "B_WARNING_SPAM");
  }

  if ($is_complete)
  {
    preg_match("/(.*)\\.(.*)\\.(.*)\\.(.*)/", $_SERVER['REMOTE_ADDR'], $matches);
    // bans-query
    $bans_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}bans` " .
			 "WHERE `id_season` = {$_REQUEST['sid'] } " .
			 "AND (`ip` = '{$matches[1]}.*.*.*' " .
			 "OR `ip` = '{$matches[1]}.{$matches[2]}.*.*' " .
			 "OR `ip` = '{$matches[1]}.{$matches[2]}.{$matches[3]}.*' " . 
			 "OR `ip` = '{$matches[1]}.{$matches[2]}.{$matches[3]}.{$matches[4]}')");
    if (dbNumRows($bans_ref) > 0)
    {
      $is_complete = 0;
      $content_tpl->parse("H_WARNING_BAN", "B_WARNING_BAN");
    }
    else
    {
      $body = dbEscape($_REQUEST['body']);
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}news_comments` " .
	      "(`id_user`, `body`, `id_news`, `ip`, `submitted`) " .
	       "VALUES ('{$user['uid']}', " .
	       "'$body', " .
	       "{$news_row['id']}, " .
	       "'{$_SERVER['REMOTE_ADDR']}', " .
	       "NOW())");
      $content_tpl->parse("H_MESSAGE_COMMENT_ADDED", "B_MESSAGE_COMMENT_ADDED");
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->set_var("I_ID_NEWS", $id_news);
      $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
    }
  }

  if (!$is_complete)
  {
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
