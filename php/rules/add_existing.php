<?php

################################################################################
#
# $Id: add_existing.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_COPIED", "H_MESSAGE_COPIED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SEASON", "H_WARNING_SEASON");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins
if ($user['usertype_headadmin'])
{
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			 "WHERE `id` = {$_REQUEST['id_season']}");
  if ($seasons_row = dbFetch($seasons_ref))
  {
    $rules_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}rules` WHERE `id_season` = {$seasons_row['id']}");
    while ($rules_row = dbFetch($rules_ref))
    {
      $rules_row['subject'] = addslashes($rules_row['subject']);
      $rules_row['body'] = addslashes($rules_row['body']);
      dbQuery("INSERT INTO `{$cfg['db_table_prefix']}rules` (`id_season`, `subject`, `body`) " .
	      "VALUES ({$_REQUEST['sid']}, '{$rules_row['subject']}', '{$rules_row['body']}')");
    }
    $content_tpl->parse("H_MESSAGE_COPIED", "B_MESSAGE_COPIED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
  else
  {
    $content_tpl->parse("H_WARNING_SEASON", "B_WARNING_SEASON");
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
