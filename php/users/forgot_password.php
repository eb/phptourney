<?php

################################################################################
#
# $Id: forgot_password.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_FORGOT_PASSWORD", "H_FORGOT_PASSWORD");

$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_FORGOT_PASSWORD", "B_FORGOT_PASSWORD");

?>
