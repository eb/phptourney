<?php

////////////////////////////////////////////////////////////////////////////////
// Request Handler - checks incoming HTTP GETs/POSTs
//
// $Id: request.inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
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

// check sid
if (!isset($_REQUEST['sid']) or !isWholePositiveNumber($_REQUEST['sid']))
{
  $_REQUEST['sid'] = 0;
}

// check sec
if (!isset($_REQUEST['sec']) or !isWord($_REQUEST['sec']))
{
  $_REQUEST['sec'] = "";
}

// get season and section data
$season_exists = false;
$section_exists = false;
if ($_REQUEST['sid'] != "")
{
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` WHERE `id` = {$_REQUEST['sid']} AND `deleted` = 0");
  if ($season = dbFetch($seasons_ref))
  {
    $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `id` = {$season['id_section']} AND `deleted` = 0");
    if ($section = dbFetch($sections_ref))
    {
      $season_exists = true;
      $section_exists = true;
      $_REQUEST['sec'] = $section['abbreviation'];
    }
  }
}
elseif ($_REQUEST['sec'] != "")
{
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `abbreviation` = '{$_REQUEST['sec']}' AND `deleted` = 0");
  if ($section = dbFetch($sections_ref))
  {
    $section_exists = true;
    $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			    "WHERE `id_section` = {$section['id']} AND `deleted` = 0 ORDER BY `submitted` DESC");
    if ($season = dbFetch($seasons_ref))
    {
      $season_exists = true;
      $_REQUEST['sid'] = $season['id'];
    }
  }
}

// check mod
if (!isset($_REQUEST['mod']) or !isWord($_REQUEST['mod']))
{
  $_REQUEST['mod'] = "";
}

// check act
if (!isset($_REQUEST['act']) or !isWord($_REQUEST['act']))
{
  $_REQUEST['act'] = "";
}

// check opt
if (!isset($_REQUEST['opt']))
{
  $_REQUEST['opt'] = "";
}

?>
