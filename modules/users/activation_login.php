<?php

$content_tpl->set_block("F_CONTENT", "B_ACTIVATION_LOGIN", "H_ACTIVATION_LOGIN");

$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_ACTIVATION_LOGIN", "B_ACTIVATION_LOGIN");

?>
