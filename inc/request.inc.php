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

// get season data
$seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` WHERE `id` = {$_REQUEST['sid']} AND `deleted` = 0");
$season = dbFetch($seasons_ref);

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
