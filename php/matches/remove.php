<?php

################################################################################
#
# $Id: remove.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_DELETE", "H_WARNING_DELETE");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_MATCH", "H_REMOVE_MATCH");

// access for admins only
if ($user['usertype_admin'])
{
  $id_match = intval($_REQUEST['opt']);
  $matches_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}matches` WHERE `id` = $id_match");
  $matches_row = dbFetch($matches_ref);
  if (isLastMatch($matches_row))
  {
    $content_tpl->set_var("I_ID_MATCH", $id_match);
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_REMOVE_MATCH", "B_REMOVE_MATCH");
  }
  else
  {
    $content_tpl->parse("H_WARNING_DELETE", "B_WARNING_DELETE");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
