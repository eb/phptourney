<?php

################################################################################
#
# $Id: close.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_SIGNUPS_CLOSED", "H_WARNING_SIGNUPS_CLOSED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  if ($season['status'] == "signups")
  {
    dbQuery("UPDATE `{$cfg['db_table_prefix']}seasons` SET `status` = '' WHERE `id` = {$_REQUEST['sid']}");
  }
  $content_tpl->parse("H_WARNING_SIGNUPS_CLOSED", "B_WARNING_SIGNUPS_CLOSED");
  $content_tpl->parse("H_WARNING", "B_WARNING");
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
