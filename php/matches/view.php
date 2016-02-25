<?php

$content_tpl->set_block("F_CONTENT", "B_NO_BRACKET", "H_NO_BRACKET");
$content_tpl->set_block("F_CONTENT", "B_BRACKET", "H_BRACKET");

if (($season['status'] == "bracket" or $season['status'] == "running" or $season['status'] == "finished"))
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_BRACKET", "B_BRACKET");
}
else
{
  $content_tpl->parse("H_NO_BRACKET", "B_NO_BRACKET");
}

?>
