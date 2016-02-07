<?php

################################################################################
#
# $Id: remove.php,v 1.1 2006/03/16 00:05:17 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_REMOVE_DEADLINE", "H_REMOVE_DEADLINE");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $id_deadline = intval($_REQUEST['opt']);
  $content_tpl->set_var("I_ID_DEADLINE", $id_deadline);
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_REMOVE_DEADLINE", "B_REMOVE_DEADLINE");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
