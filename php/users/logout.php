<?php

################################################################################
#
# $Id: logout.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_LOGGED_OUT", "H_MESSAGE_LOGGED_OUT");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");

// unset cookie
$expire = time() - 3600;
foreach(array_keys($_COOKIE) as $key) {
  setcookie($key, "", $expire);
}
unsetUser();


$content_tpl->parse("H_MESSAGE_LOGGED_OUT", "B_MESSAGE_LOGGED_OUT");
$content_tpl->parse("H_MESSAGE", "B_MESSAGE");

?>
