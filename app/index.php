<?php

require("inc/inc.php");

header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-type: text/html");

// Template files
$main_tpl = new Template();
$main_tpl->set_file("F_INDEX", "index.tpl.html");

$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR_OPTION", "H_SEASON_SELECTOR_OPTION");
$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR_OPTION_SELECTED", "H_SEASON_SELECTOR_OPTION_SELECTED");
$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR", "H_SEASON_SELECTOR");
$main_tpl->set_block("F_INDEX", "B_SIGNUP", "H_SIGNUP");
$main_tpl->set_block("F_INDEX", "B_ROOT_PANEL", "H_ROOT_PANEL");
$main_tpl->set_block("F_INDEX", "B_HEADADMIN_PANEL", "H_HEADADMIN_PANEL");
$main_tpl->set_block("F_INDEX", "B_ADMIN_PANEL", "H_ADMIN_PANEL");
$main_tpl->set_block("F_INDEX", "B_TOURNAMENT_PANEL", "H_TOURNAMENT_PANEL");

// Season dropdown-list
$seasons_ref = dbQuery("SELECT * FROM `seasons` " .
      		  "WHERE `deleted` = 0 ORDER BY `submitted` DESC");
if (dbNumRows($seasons_ref) > 0)
{
  while ($seasons_row = dbFetch($seasons_ref))
  {
    $main_tpl->set_var("I_ID_SEASON", $seasons_row['id']);
    $main_tpl->set_var("I_SEASON_NAME", $seasons_row['name']);
    if ($seasons_row['id'] != $season['id'])
    {
      $main_tpl->parse("H_SEASON_SELECTOR_OPTION", "B_SEASON_SELECTOR_OPTION", true);
    }
    else
    {
      $main_tpl->parse("H_SEASON_SELECTOR_OPTION", "B_SEASON_SELECTOR_OPTION_SELECTED", true);
    }
  }
  $main_tpl->parse("H_SEASON_SELECTOR", "B_SEASON_SELECTOR");
}

// Default action, if none is set
if (isset($season))
{
  if (!$_REQUEST['mod'] or !$_REQUEST['act'])
  {
    $_REQUEST['mod'] = "news";
    $_REQUEST['act'] = "view";
    $_REQUEST['opt'] = "1";
  }
}
$main_tpl->set_var("I_CONTENT", execAction());

// Navigation panels
if (isset($season))
{
  if ($season['status'] == "signups")
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_SIGNUP", "B_SIGNUP");
  }

  // Headadmin
  if ($user['usertype_headadmin'])
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_HEADADMIN_PANEL", "B_HEADADMIN_PANEL");
  }

  // Admin
  if ($user['usertype_admin'])
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_ADMIN_PANEL", "B_ADMIN_PANEL");
  }

  // Tournament
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->parse("H_TOURNAMENT_PANEL", "B_TOURNAMENT_PANEL");

  // Latest matches panel
  $_REQUEST['mod'] = "matches";
  $_REQUEST['act'] = "latest_matches";
  $_REQUEST['opt'] = "";
  $main_tpl->set_var("I_LATEST_MATCHES", execAction());
}

// Root navigation
if ($user['usertype_root'])
{
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->parse("H_ROOT_PANEL", "B_ROOT_PANEL");
}
// User panel
$_REQUEST['mod'] = "users";
$_REQUEST['act'] = "show";
$_REQUEST['opt'] = "";
$main_tpl->set_var("I_USER", execAction());

// Tourney / season name
$main_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
$main_tpl->set_var("I_ID_SEASON", "");
$main_tpl->set_var("I_SEASON_NAME", "");
if (isset($season))
{
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->set_var("I_SEASON_NAME", $season['name']);
}

// Parse and print the site
$main_tpl->pparse("PAGE", "F_INDEX");

?>
