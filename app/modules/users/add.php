<?php

$content_tpl->set_block("F_CONTENT", "B_ADD_COUNTRY", "H_ADD_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_SIGNUP", "H_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_ADD_ACCOUNT", "H_ADD_ACCOUNT");

// Countries
$countries_ref = dbQuery("SELECT * FROM `countries` " .
			  "WHERE `active` = 1 " .
			  "ORDER BY `name` ASC");
while ($countries_row = dbFetch($countries_ref))
{
  $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
  $content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));
  $content_tpl->parse("H_ADD_COUNTRY", "B_ADD_COUNTRY", true);
}

// Signup
if ($season['status'] == "signups")
{
  $content_tpl->parse("H_SIGNUP", "B_SIGNUP");
}
   
$content_tpl->set_var("I_ID_SEASON", $season['id']);
$content_tpl->parse("H_ADD_ACCOUNT", "B_ADD_ACCOUNT");

?>
