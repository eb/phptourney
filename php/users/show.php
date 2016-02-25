<?php

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
  $content_tpl->set_var("I_USERNAME", htmlspecialchars($user['username']));
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_LOGOUT", "B_LOGOUT");
}
else
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_LOGIN", "B_LOGIN");
}

?>
