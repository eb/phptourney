<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_LOGGED_OUT", "H_MESSAGE_LOGGED_OUT");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");

// Unset cookie
$expire = time() - 3600;
foreach(array_keys($_COOKIE) as $key) {
  setcookie($key, "", $expire);
}
unsetUser();


$content_tpl->parse("H_MESSAGE_LOGGED_OUT", "B_MESSAGE_LOGGED_OUT");
$content_tpl->parse("H_MESSAGE", "B_MESSAGE");

?>
