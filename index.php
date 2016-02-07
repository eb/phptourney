<?php

////////////////////////////////////////////////////////////////////////////////
// Main Site - Framework for the site
//           - Loader for the content modules
//
// $Id: index.php,v 1.3 2006/05/01 14:55:10 eb Exp $
//
// Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
////////////////////////////////////////////////////////////////////////////////

require("inc/inc.php");

header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-type: text/html");

// template files
$main_tpl = new Template("html", "remove");
$main_tpl->set_file("F_INDEX", "index.html");

// template blocks
$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR_OPTION", "H_SEASON_SELECTOR_OPTION");
$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR_OPTION_SELECTED", "H_SEASON_SELECTOR_OPTION_SELECTED");
$main_tpl->set_block("F_INDEX", "B_SEASON_SELECTOR", "H_SEASON_SELECTOR");
$main_tpl->set_block("F_INDEX", "B_SIGNUP", "H_SIGNUP");
$main_tpl->set_block("F_INDEX", "B_ROOT_PANEL", "H_ROOT_PANEL");
$main_tpl->set_block("F_INDEX", "B_HEADADMIN_PANEL", "H_HEADADMIN_PANEL");
$main_tpl->set_block("F_INDEX", "B_ADMIN_PANEL", "H_ADMIN_PANEL");
$main_tpl->set_block("F_INDEX", "B_TOURNAMENT_PANEL", "H_TOURNAMENT_PANEL");
$main_tpl->set_block("F_INDEX", "B_NETWORK_PANEL", "H_NETWORK_PANEL");

// read version
$fh_version = fopen("VERSION", "r");
$main_tpl->set_var("I_VERSION", fread($fh_version, filesize("VERSION")));
fclose($fh_version);

// season dropdown-list
$seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
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

// default action, if none is set
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

// navigation panels
if (isset($season))
{
  if ($season['status'] == "signups")
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_SIGNUP", "B_SIGNUP");
  }

  // headadmin
  if ($user['usertype_headadmin'])
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_HEADADMIN_PANEL", "B_HEADADMIN_PANEL");
  }

  // admin
  if ($user['usertype_admin'])
  {
    $main_tpl->set_var("I_ID_SEASON", $season['id']);
    $main_tpl->parse("H_ADMIN_PANEL", "B_ADMIN_PANEL");
  }

  // tournament
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->parse("H_TOURNAMENT_PANEL", "B_TOURNAMENT_PANEL");

  // latest matches panel
  $_REQUEST['mod'] = "matches";
  $_REQUEST['act'] = "latest_matches";
  $_REQUEST['opt'] = "";
  $main_tpl->set_var("I_LATEST_MATCHES", execAction());
}

// root navigation
if ($user['usertype_root'])
{
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->parse("H_ROOT_PANEL", "B_ROOT_PANEL");
}
// user panel
$_REQUEST['mod'] = "users";
$_REQUEST['act'] = "show";
$_REQUEST['opt'] = "";
$main_tpl->set_var("I_USER", execAction());

// network panel
$main_tpl->parse("H_NETWORK_PANEL", "B_NETWORK_PANEL");

// tourney / season name
$main_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
$main_tpl->set_var("I_ID_SEASON", "");
$main_tpl->set_var("I_SEASON_NAME", "");
if (isset($season))
{
  $main_tpl->set_var("I_ID_SEASON", $season['id']);
  $main_tpl->set_var("I_SEASON_NAME", $season['name']);
}

// parse and print the site
$main_tpl->pparse("PAGE", "F_INDEX");

?>
