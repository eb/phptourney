<?php

$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY", "H_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY_SELECTED", "H_COUNTRY_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_NOTIFY_UNCHECKED", "H_NOTIFY_UNCHECKED");
$content_tpl->set_block("F_CONTENT", "B_NOTIFY_CHECKED", "H_NOTIFY_CHECKED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_PROFILE", "H_EDIT_PROFILE");

// Access for the user to edit his own profile
if ((!isset($_REQUEST['opt']) or $_REQUEST['opt'] == "") and $user['uid'])
{
  $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = {$user['uid']}");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_OPT", "");
  $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_PASSWORD", htmlspecialchars($users_row['password']));
  $content_tpl->set_var("I_PASSWORD_RETYPED", htmlspecialchars($users_row['password']));
  $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
  $content_tpl->set_var("I_IRC_CHANNEL", htmlspecialchars($users_row['irc_channel']));

  $countries_ref = dbQuery("SELECT * FROM `countries` " .
			    "WHERE `active` = 1 " .
			    "ORDER BY `name` ASC");
  while ($countries_row = dbFetch($countries_ref))
  {
    $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
    $content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));
    if ($countries_row['id'] == $users_row['id_country'])
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY", true);
    }
  }
  if ($users_row['notify'] == 1)
  {
    $content_tpl->parse("H_NOTIFY_CHECKED", "B_NOTIFY_CHECKED");
  }
  else
  {
    $content_tpl->parse("H_NOTIFY_UNCHECKED", "B_NOTIFY_UNCHECKED");
  }
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_PROFILE", "B_EDIT_PROFILE");
}

// Access for the headadmin to edit any profile
elseif ($_REQUEST['opt'] != "" and ($user['usertype_headadmin'] or $user['usertype_root']))
{
  $id_user = intval($_REQUEST['opt']);
  $users_ref = dbQuery("SELECT * FROM `users` WHERE `id` = $id_user");
  $users_row = dbFetch($users_ref);
  $content_tpl->set_var("I_OPT", $id_user);
  $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
  $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
  $content_tpl->set_var("I_IRC_CHANNEL", htmlspecialchars($users_row['irc_channel']));

  $countries_ref = dbQuery("SELECT * FROM `countries` " .
			    "WHERE `active` = 1 " .
			    "ORDER BY `name` ASC");
  while ($countries_row = dbFetch($countries_ref))
  {
    $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
    $content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));
    if ($countries_row['id'] == $users_row['id_country'])
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_COUNTRY_SELECTED", "B_COUNTRY", true);
    }
  }
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_EDIT_PROFILE", "B_EDIT_PROFILE");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
