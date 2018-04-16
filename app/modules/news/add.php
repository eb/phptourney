<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_ADD_NEWS", "H_ADD_NEWS");

// Access for admins [public / private news]
if ($user['usertype_admin']) {
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
