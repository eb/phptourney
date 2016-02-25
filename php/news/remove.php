<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_NEWS", "H_REMOVE_NEWS");

$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// Access for headadmins [public / private news]
// Access for admins [public / private news that they wrote themselves]
if ($user['usertype_headadmin'] or $user['uid'] == $news_row['id_user']) {
  $content_tpl->set_var("I_OPT", $news_row['id_news_group']);
  $content_tpl->set_var("I_ID_NEWS", $news_row['id']);
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_REMOVE_NEWS", "B_REMOVE_NEWS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
