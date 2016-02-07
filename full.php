<?php

////////////////////////////////////////////////////////////////////////////////
// Fullscreen Site
//
// $Id: full.php,v 1.2 2006/05/01 14:55:10 eb Exp $
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

// template files
$main_tpl = new Template("html", "remove");
$main_tpl->set_file("F_FULL", "full.html");

// read version
$fh_version = fopen("VERSION", "r");
$main_tpl->set_var("I_VERSION", fread($fh_version, filesize("VERSION")));
fclose($fh_version);

$main_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
$main_tpl->set_var("I_SEASON_NAME", "");
if (isset($season))
{
  $main_tpl->set_var("I_SEASON_NAME", $season['name']);
}

// default action, if none is set
if (!$_REQUEST['mod'] or !$_REQUEST['act'])
{
  $_REQUEST['mod'] = "news";
  $_REQUEST['act'] = "view";
  $_REQUEST['opt'] = "1";
}
$main_tpl->set_var("I_CONTENT", execAction());

// parse and print the site
$main_tpl->pparse("PAGE", "F_FULL");

?>
