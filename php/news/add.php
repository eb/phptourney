<?php

################################################################################
#
# $Id: add.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_ADD_NEWS", "H_ADD_NEWS");

// access for roots [global news]
// access for admins [public / private news]
if ($season['id'] == 0 and $user['usertype_root'] or
    $season['id'] != 0 and $user['usertype_admin']) {
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->set_var("I_OPT", htmlspecialchars($_REQUEST['opt']));
  $content_tpl->parse("H_ADD_NEWS", "B_ADD_NEWS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
