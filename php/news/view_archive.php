<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_NEWS", "H_VIEW_NO_NEWS");
$content_tpl->set_block("F_CONTENT", "B_COMMENTS_LINK", "H_COMMENTS_LINK");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NEWS", "H_VIEW_NEWS");

// Access for admins [private news]
// Access for guests [public news]
if ($user['usertype_admin'] or $_REQUEST['opt'] == 1)
{
  $id_news_group = intval($_REQUEST['opt']);
  $news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` " .
		       "WHERE `id_news_group` = $id_news_group " .
		       "AND `id_season` = {$season['id']} AND `deleted` = 0 " .
		       "ORDER BY `submitted` DESC");
  if (dbNumRows($news_ref) <= 0)
  {
    $content_tpl->parse("H_VIEW_NO_NEWS", "B_VIEW_NO_NEWS");
  }
  else
  {
    while ($news_row = dbFetch($news_ref))
    {
      $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = {$news_row['id_user']}");
      $users_row = dbFetch($users_ref);

      $content_tpl->set_var("I_ID_NEWS", $news_row['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->set_var("I_HEADING", htmlspecialchars($news_row['heading']));
      $content_tpl->set_var("I_BODY", Parsedown::instance()->text($news_row['body']));
      $content_tpl->set_var("I_SUBMITTED", htmlspecialchars($news_row['submitted']));

      $comments_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news_comments` WHERE `id_news` = {$news_row['id']}");
      $content_tpl->set_var("I_NUM_COMMENTS", dbNumRows($comments_ref));
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
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
