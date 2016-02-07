<?php

################################################################################
#
# $Id: activation_login.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_ACTIVATION_LOGIN", "H_ACTIVATION_LOGIN");

$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_ACTIVATION_LOGIN", "B_ACTIVATION_LOGIN");

?>
