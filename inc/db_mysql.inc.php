<?php

////////////////////////////////////////////////////////////////////////////////
// MySQL functions - Wrapper for MySQL
//
// $Id: db_mysql.inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
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

$mysqli_link = mysqli_connect('p:' . $cfg['db_host'], $cfg['db_username'], $cfg['db_password']);
mysqli_set_charset($mysqli_link, 'utf8');
mysqli_select_db($mysqli_link, $cfg['db_name']);

function dbQuery($sql_query)
{
  global $mysqli_link;
  return(mysqli_query($mysqli_link, $sql_query));
}

function dbNumRows($data_ref)
{
  global $mysqli_link;
  return(mysqli_num_rows($data_ref));
}

function dbFetch($data_ref)
{
  global $mysqli_link;
  return(mysqli_fetch_assoc($data_ref));
}

?>
