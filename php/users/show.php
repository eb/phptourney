<?php

################################################################################
#
# $Id: show.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_LOGIN", "H_LOGIN");
$content_tpl->set_block("F_CONTENT", "B_PLAYER", "H_PLAYER");
$content_tpl->set_block("F_CONTENT", "B_LOGOUT", "H_LOGOUT");

if ($user['uid'])
{
  if ($user['usertype_player'])
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("I_ID_USER", $user['uid']);
    $content_tpl->parse("H_PLAYER", "B_PLAYER");
  }
  $content_tpl->set_var("I_USERNAME", $user['username']);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_LOGOUT", "B_LOGOUT");
}
else
{
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_LOGIN", "B_LOGIN");
}

?>
