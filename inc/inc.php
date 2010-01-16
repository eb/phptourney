<?php

////////////////////////////////////////////////////////////////////////////////
// Includes - All needed includes for the whole site
//
// $Id: inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
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

// the directory containing this file
$inc_path = dirname(__FILE__);

// helper functions
require("$inc_path/helpers.inc.php");

// misc checks
require("$inc_path/checks.inc.php");

// global config
require("$inc_path/config.inc.php");

// database
require("$inc_path/db_mysql.inc.php");

// template-class of the phplib
require("$inc_path/template.inc.php");

// http-requests
require("$inc_path/request.inc.php");

// checks the cookies of the user
require("$inc_path/user.inc.php");

// tourney-system logic
require("$inc_path/tourney_system.inc.php");

// bracket
require("$inc_path/bracket.inc.php");

?>
