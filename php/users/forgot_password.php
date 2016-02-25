<?php

$content_tpl->set_block("F_CONTENT", "B_FORGOT_PASSWORD", "H_FORGOT_PASSWORD");

$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_FORGOT_PASSWORD", "B_FORGOT_PASSWORD");

?>
