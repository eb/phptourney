<?php

################################################################################
#
# $Id: update.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_RULE_EDITED", "H_MESSAGE_RULE_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SUBJECT", "H_WARNING_SUBJECT");
$content_tpl->set_block("F_CONTENT", "B_WARNING_BODY", "H_WARNING_BODY");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for roots at network level
// access for headadmins at season level
if ($season['id'] == 0 and $user['usertype_root'] or $season['id'] > 0 and $user['usertype_headadmin'])
{
  $is_complete = 1;
  if ($_REQUEST['subject'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SUBJECT", "B_WARNING_SUBJECT");
  }
  if ($_REQUEST['body'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_BODY", "B_WARNING_BODY");
  }

  if ($is_complete)
  {
    $id_rule = intval($_REQUEST['opt']);
    $subject = dbEscape($_REQUEST['subject']);
    $body = dbEscape($_REQUEST['body']);
    dbQuery("UPDATE `{$cfg['db_table_prefix']}rules` SET `subject` = '$subject', `body` = '$body' " .
	     "WHERE `id` = $id_rule");
    $content_tpl->parse("H_MESSAGE_RULE_EDITED", "B_MESSAGE_RULE_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
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
