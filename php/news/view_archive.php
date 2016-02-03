<?php

################################################################################
#
# $Id: view_archive.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_NEWS", "H_VIEW_NO_NEWS");
$content_tpl->set_block("F_CONTENT", "B_COMMENTS_LINK", "H_COMMENTS_LINK");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NEWS", "H_VIEW_NEWS");

// access for admins [private news]
// access for guests [global / public news]
if ($user['usertype_admin'] or $_REQUEST['opt'] == 1)
{
  // news-query
  $id_news_group = intval($_REQUEST['opt']);
  $news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` " .
		       "WHERE `id_news_group` = $id_news_group " .
		       "AND `id_season` = {$_REQUEST['sid']} AND `deleted` = 0 " .
		       "ORDER BY `submitted` DESC");
  if (dbNumRows($news_ref) <= 0)
  {
    $content_tpl->parse("H_VIEW_NO_NEWS", "B_VIEW_NO_NEWS");
  }
  else
  {
    while ($news_row = dbFetch($news_ref))
    {
      // users-query
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$news_row['id_user']}");
      $users_row = dbFetch($users_ref);

      $content_tpl->set_var("I_ID_NEWS", $news_row['id']);
      $content_tpl->set_var("I_USERNAME", $users_row['username']);
      $content_tpl->set_var("I_HEADING", $news_row['heading']);
      $content_tpl->set_var("I_BODY", nl2br($news_row['body']));
      $content_tpl->set_var("I_SUBMITTED", $news_row['submitted']);

      // comments-query
      $comments_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news_comments` WHERE `id_news` = {$news_row['id']}");
      $content_tpl->set_var("I_NUM_COMMENTS", dbNumRows($comments_ref));
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->parse("H_COMMENTS_LINK", "B_COMMENTS_LINK");
      $content_tpl->parse("H_VIEW_NEWS", "B_VIEW_NEWS", true);
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
