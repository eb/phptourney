<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_NEWS_ADDED", "H_MESSAGE_NEWS_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_HEADING", "H_WARNING_HEADING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for admins [public / private news]
if ($user['usertype_admin']) {
  $is_complete = 1;
  if ($_REQUEST['heading'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_HEADING", "B_WARNING_HEADING");
  }
  if ($_REQUEST['body'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_BODY", "B_WARNING_BODY");
  }

  if ($is_complete)
  {
    $id_news_group = intval($_REQUEST['opt']);
    $heading = dbEscape($_REQUEST['heading']);
    $body = dbEscape($_REQUEST['body']);
    dbQuery("INSERT INTO `{$cfg['db_table_prefix']}news` (`heading`, `body`, `id_season`, `id_user`, `id_news_group`, `submitted`) " .
	     "VALUES ('$heading', " .
	     "'$body', " .
	     "{$season['id']}, " .
	     "{$user['uid']}, " .
	     "$id_news_group, " .
	     "NOW())");

    $content_tpl->parse("H_MESSAGE_NEWS_ADDED", "B_MESSAGE_NEWS_ADDED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("I_OPT", $id_news_group);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
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
