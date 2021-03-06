<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_NEWS_REMOVED", "H_MESSAGE_NEWS_REMOVED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// Access for headadmins [public / private news]
// Access for admins [public / private news that they wrote themselves]
if ($user['usertype_headadmin'] or $user['uid'] == $news_row['id_user']) {
  dbQuery("UPDATE `news` SET `deleted` = 1 WHERE `id` = $id_news");
  $content_tpl->parse("H_MESSAGE_NEWS_REMOVED", "B_MESSAGE_NEWS_REMOVED");
  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  $content_tpl->set_var("I_OPT", $news_row['id_news_group']);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
