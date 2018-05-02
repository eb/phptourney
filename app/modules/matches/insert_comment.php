<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_COMMENT_ADDED", "H_MESSAGE_COMMENT_ADDED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SPAM", "H_WARNING_SPAM");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BAN", "H_WARNING_BAN");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

$id_match = intval($_REQUEST['opt']);
$content_tpl->set_var("I_ID_MATCH", $id_match);

// Access for users
if ($user['uid'])
{
  $is_complete = 1;
  if ($_REQUEST['body'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_BODY", "B_WARNING_BODY");
  }
  $minute = (date("i") + 55) % 60;
  $now = date("Y-m-d H:{$minute}:s");
  $comments_ref = dbQuery("SELECT * FROM `match_comments` " .
			   "WHERE `id_match` = $id_match " .
			   "AND `ip` = '{$_SERVER['REMOTE_ADDR']}' " .
			   "AND `submitted` > '$now'");
  if (dbNumRows($comments_ref) > 0)
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SPAM", "B_WARNING_SPAM");
  }

  if ($is_complete)
  {
    preg_match("/(.*)\\.(.*)\\.(.*)\\.(.*)/", $_SERVER['REMOTE_ADDR'], $matches);
    $bans_ref = dbQuery("SELECT * FROM `bans` " .
			 "WHERE `id_season` = {$season['id']} " .
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
      dbQuery("INSERT INTO `match_comments` " .
	       "(`id_user`, `body`, `id_match`, `ip`, `submitted`) " .
	       "VALUES ('{$user['uid']}', " .
	       "'$body', " .
	       "$id_match, " .
	       "'{$_SERVER['REMOTE_ADDR']}', " .
	       "NOW())");
      $content_tpl->parse("H_MESSAGE_COMMENT_ADDED", "B_MESSAGE_COMMENT_ADDED");
      $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
      $content_tpl->set_var("I_ID_SEASON", $season['id']);
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
