<?php

////////////////////////////////////////////////////////////////////////////////
// Miscellaneous checks
//
// $Id: checks.inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
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

$this_path = dirname(__FILE__);

// magic quotes
if (ini_get("magic_quotes_gpc") != "1")
{
  exit("'magic_quotes_gpc' has to be set to 'On'");
}
if (ini_get("magic_quotes_sybase") == "1")
{
  exit("'magic_quotes_sybase' has to be set to 'Off'");
}
if (ini_get("magic_quotes_runtime") == "1")
{
  exit("'magic_quotes_runtime' has to be set to 'Off'");
}

// register globals
if (ini_get("register_globals") == "1")
{
  exit("'register_globals' has to be set to 'Off'");
}

// writable screenshots dir
if (!is_writable("$this_path/../data/screenshots"))
{
  exit("the directory 'data/screenshots' has to be writable");
}

// writable serverlists dir
if (!is_writable("$this_path/../data/serverlists"))
{
  exit("the directory 'data/serverlists' has to be writable");
}

// writable version file
if (!is_writable("$this_path/../VERSION"))
{
  exit("the file 'VERSION' has to be writable");
}

?>
