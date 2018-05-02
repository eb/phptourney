<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_USERNAME", "H_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_ADD_SEASON", "H_ADD_SEASON");

// Access for root only
if ($user['usertype_root'])
{
  $users_ref = dbQuery("SELECT * FROM `users` " .
			"ORDER BY `username` ASC");
  while ($users_row = dbFetch($users_ref))
  {
    $content_tpl->set_var("I_ID_USER", $users_row['id']);
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
    $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
    $content_tpl->parse("H_USERNAME", "B_USERNAME", true);
  }

  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->set_var("I_USERNAME", "");
  $content_tpl->set_var("I_EMAIL", "");
  $content_tpl->parse("H_ADD_SEASON", "B_ADD_SEASON");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
