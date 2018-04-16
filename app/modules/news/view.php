<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_NEWS", "H_VIEW_NO_NEWS");
$content_tpl->set_block("F_CONTENT", "B_ADD", "H_ADD");
$content_tpl->set_block("F_CONTENT", "B_PUBLISH", "H_PUBLISH");
$content_tpl->set_block("F_CONTENT", "B_PUBLISH_EDIT_DELETE", "H_PUBLISH_EDIT_DELETE");
$content_tpl->set_block("F_CONTENT", "B_COMMENTS_LINK", "H_COMMENTS_LINK");
$content_tpl->set_block("F_CONTENT", "B_NEWS_ARCHIVE", "H_NEWS_ARCHIVE");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NEWS", "H_VIEW_NEWS");

$id_news_group = intval($_REQUEST['opt']);

if ($user['usertype_admin'])
{
  $content_tpl->set_var("I_OPT", $id_news_group);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_ADD", "B_ADD");
}

// Access for admins [private news]
// Access for guests [public news]
if ($user['usertype_admin'] or $id_news_group == 1)
{
  $news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` " .
		       "WHERE `id_news_group` = $id_news_group AND `id_season` = {$season['id']} AND `deleted` = 0 " .
		       "ORDER BY `submitted` DESC LIMIT 0, 5");
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

      if ($user['usertype_admin'])
      {
	$content_tpl->set_var("I_ID_SEASON", $season['id']);
	if ($id_news_group == 2)
	{
	  $content_tpl->parse("H_PUBLISH", "B_PUBLISH");
	}
	$content_tpl->parse("H_PUBLISH_EDIT_DELETE", "B_PUBLISH_EDIT_DELETE");
      }

      $comments_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news_comments` WHERE `id_news` = {$news_row['id']} AND `deleted` = 0");
      $content_tpl->set_var("I_NUM_COMMENTS", dbNumRows($comments_ref));
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
      $content_tpl->parse("H_COMMENTS_LINK", "B_COMMENTS_LINK");
      $content_tpl->parse("H_VIEW_NEWS", "B_VIEW_NEWS", true);
    }
    $content_tpl->set_var("I_OPT", $id_news_group);
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_NEWS_ARCHIVE", "B_NEWS_ARCHIVE");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
