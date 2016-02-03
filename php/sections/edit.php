<?php

################################################################################
#
# $Id: edit.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_SECTION", "H_EDIT_SECTION");

// access for root only
if ($user['usertype_root'])
{
  $id_section = intval($_REQUEST['opt']);
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `id` = $id_section AND `deleted` = 0");
  $sections_row = dbFetch($sections_ref);
  $content_tpl->set_var("I_ID_SECTION", $_REQUEST['opt']);
  $content_tpl->set_var("I_NAME", $sections_row['name']);
  $content_tpl->set_var("I_ABBREVIATION", $sections_row['abbreviation']);
  $content_tpl->set_var("I_ADMIN_IRC_CHANNELS", $sections_row['admin_irc_channels']);
  $content_tpl->set_var("I_PUBLIC_IRC_CHANNELS", $sections_row['public_irc_channels']);
  $content_tpl->set_var("I_BOT_HOST", $sections_row['bot_host']);
  $content_tpl->set_var("I_BOT_PORT", $sections_row['bot_port']);
  $content_tpl->set_var("I_BOT_PASSWORD", $sections_row['bot_password']);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_SECTION", "B_EDIT_SECTION");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
