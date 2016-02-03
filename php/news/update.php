<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_NEWS_EDITED", "H_MESSAGE_NEWS_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_HEADING", "H_WARNING_HEADING");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// news-query
$id_news = intval($_REQUEST['opt']);
$news_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}news` WHERE `id` = $id_news AND `deleted` = 0");
$news_row = dbFetch($news_ref);

// access for roots [global news]
// access for headadmins [public / private news]
// access for admins [public / private news that they wrote themselves]
if ($news_row['id_season'] == 0 and $user['usertype_root'] or
    $news_row['id_season'] != 0 and ($user['usertype_headadmin'] or $user['uid'] == $news_row['id_user'])) {
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
    $heading = dbEscape($_REQUEST['heading']);
    $body = dbEscape($_REQUEST['body']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}news` " .
	     "SET `heading` = '$heading', " .
	     "`body` = '$body' " .
	     "WHERE `id` = {$news_row['id']}");
    $content_tpl->parse("H_MESSAGE_NEWS_EDITED", "B_MESSAGE_NEWS_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->set_var("I_OPT", $news_row['id_news_group']);
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
