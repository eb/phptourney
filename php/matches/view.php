<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_NO_BRACKET", "H_NO_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_BRACKET", "H_BRACKET");

if (($season['status'] == "bracket" or $season['status'] == "running" or $season['status'] == "finished"))
{
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_BRACKET", "B_BRACKET");
}
else
{
  $content_tpl->parse("H_NO_BRACKET", "B_NO_BRACKET");
}

?>
