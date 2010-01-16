<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_BANS", "H_VIEW_BANS");

// access for headadmins only
if ($user['usertype_admin'])
{
  $ip = $_REQUEST['opt'];
  $content_tpl->set_var("I_IP", $ip);
  $ipc = preg_replace("/(.*\\.).*/", "$1*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPC", $ipc);
  $ipb = preg_replace("/(.*\\.).*\\..*/", "$1*.*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPB", $ipb);
  $ipa = preg_replace("/(.*\\.).*\\..*\\..*/", "$1*.*.*", $_REQUEST['opt']);
  $content_tpl->set_var("I_IPA", $ipa);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_VIEW_BANS", "B_VIEW_BANS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
