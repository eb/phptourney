<?php

################################################################################
#
# $Id: view_comments.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NEWS", "H_VIEW_NEWS");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_COMMENTS", "H_VIEW_NO_COMMENTS");
$content_tpl->set_block("F_CONTENT", "B_BANS", "H_BANS");
$content_tpl->set_block("F_CONTENT", "B_VIEW_COMMENT", "H_VIEW_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_VIEW_COMMENTS", "H_VIEW_COMMENTS");
$content_tpl->set_block("F_CONTENT", "B_ADD_COMMENT", "H_ADD_COMMENT");
$content_tpl->set_block("F_CONTENT", "B_LOGIN_TO_COMMENT", "H_LOGIN_TO_COMMENT");

// news-query
$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = '$id_news' AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// access for admins [private news]
// access for guests [global / public news]
if ($user['usertype_admin'] or $news_row['id_news_group'] == 1)
{
  // users-query
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = '{$news_row['id_user']}'");
  $users_row = dbFetch($users_ref);

  $content_tpl->set_var("I_ID_NEWS", $news_row['id']);
  $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_HEADING", htmlspecialchars($news_row['heading']));
  $content_tpl->set_var("I_BODY", Parsedown::instance()->text($news_row['body']));
  $content_tpl->set_var("I_SUBMITTED", htmlspecialchars($news_row['submitted']));
  $content_tpl->parse("H_VIEW_NEWS", "B_VIEW_NEWS", true);

  // comments-query
  $comments_ref = dbQuery("SELECT NC.*, U.`username` " .
			  "FROM `{$cfg['db_table_prefix']}news_comments` NC " .
			  "LEFT JOIN `{$cfg['db_table_prefix']}users` U " .
			  "ON NC.`id_user` = U.`id` " .
			  "WHERE `id_news` = {$news_row['id']} " .
			  "AND `deleted` = 0 " .
			  "ORDER BY `submitted`");
  if (dbNumRows($comments_ref) <= 0)
  {
    $content_tpl->parse("H_VIEW_NO_COMMENTS", "B_VIEW_NO_COMMENTS");
  }
  else
  {
    $counter = 1;
    while ($comments_row = dbFetch($comments_ref))
    {
      $content_tpl->set_var("I_COUNTER", $counter);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($comments_row['username']));
      if ($user['usertype_admin'])
      {
	$ip = $comments_row['ip'];
      }
      else
      {
	$ip = preg_replace("/(.*\\.).*/", "$1xxx", $comments_row['ip']);
      }
      $content_tpl->set_var("I_IP", htmlspecialchars($ip));
      $content_tpl->set_var("I_BODY", nl2br(htmlspecialchars($comments_row['body'])));
      $content_tpl->set_var("I_SUBMITTED", htmlspecialchars($comments_row['submitted']));

      if ($user['usertype_admin'] == 1)
      {
	$ip = $comments_row['ip'];
	$content_tpl->set_var("I_IP", htmlspecialchars($ip));
	$content_tpl->set_var("I_ID_SEASON", $season['id']);
	$content_tpl->parse("H_BANS", "B_BANS");
      }

      $username = dbEscape($comments_row['username']);
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `username` = '$username'");
      $users_row = dbFetch($users_ref);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->parse("H_VIEW_COMMENT", "B_VIEW_COMMENT", true);
      $counter++;
    }
  }
  $content_tpl->parse("H_VIEW_COMMENTS", "B_VIEW_COMMENTS");

  $content_tpl->set_var("I_BODY", "");
  if ($user['uid'])
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_ADD_COMMENT", "B_ADD_COMMENT");
  }
  else
  {
    $content_tpl->parse("H_LOGIN_TO_COMMENT", "B_LOGIN_TO_COMMENT");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
