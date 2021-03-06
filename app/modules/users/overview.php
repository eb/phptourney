<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_USERS", "H_NO_USERS");
$content_tpl->set_block("F_CONTENT", "B_USER", "H_USER");
$content_tpl->set_block("F_CONTENT", "B_USERS", "H_USERS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_USERS", "H_OVERVIEW_USERS");

// Access for roots only
if ($user['usertype_root'])
{
  $users_ref = dbQuery("SELECT * " .
			"FROM `users` " .
			"ORDER BY `username` ASC");
  $user_counter = 0;
  if (dbNumRows($users_ref) <= 0)
  {
    $content_tpl->parse("H_NO_USERS", "B_NO_USERS");
  }
  else
  {
    while ($users_row = dbFetch($users_ref))
    {
      $content_tpl->set_var("I_USER_COUNTER", ++$user_counter);
      $content_tpl->set_var("I_ID_USER", $users_row['id']);
      $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
      $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
      $content_tpl->parse("H_USER", "B_USER", true);
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_USERS", "B_USERS");
  }
  $content_tpl->parse("H_OVERVIEW_USERS", "B_OVERVIEW_USERS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
