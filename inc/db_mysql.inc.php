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

// connect to db
mysql_pconnect($cfg['db_host'], $cfg['db_username'], $cfg['db_password']);

// set charset
mysql_set_charset('utf8');

// select the database
mysql_select_db($cfg['db_name']);

//================================================================================
// function dbQuery
// wrapper for mysql_query
//================================================================================

function dbQuery($sql_query)
{
  return(mysql_query($sql_query));
}

//================================================================================
// function dbNumRows
// wrapper for mysql_num_rows
//================================================================================

function dbNumRows($data_ref)
{
  return(mysql_num_rows($data_ref));
}

//================================================================================
// function dbFetch
// wrapper for mysql_fetch_array
//================================================================================

function dbFetch($data_ref)
{
  return(mysql_fetch_array($data_ref, MYSQL_ASSOC));
}

//================================================================================
// function dbSQL
// executes sql
//================================================================================

function dbSQL($sql)
{
  // split sql into separate lines
  $sql_lines = explode("\n", $sql);

  for ($i = 0; $i < count($sql_lines); $i++)
  {
    // remove comments
    if (substr($sql_lines[$i], 0, 1) == "#")
    {
      array_splice($sql_lines, $i, 1);
      $i--;
    }
    // remove empty lines
    elseif ($sql_lines[$i] == "")
    {
      array_splice($sql_lines, $i, 1);
      $i--;
    }
  }

  // join the separate lines again
  $cleaned_sql = implode("", $sql_lines);

  // split sql into separate queries
  $sql_queries = array();
  $quoted = false;
  $query = "";
  for ($i = 0; $i < strlen($cleaned_sql); $i++)
  {
    $pre_pre_char = $pre_char;
    $pre_char = $char;
    $char = substr($cleaned_sql, $i, 1);
    if ($char == "'" and !$quoted and ($pre_char != "\\" or $pre_pre_char == "\\"))
    {
      $quoted = true;
      $query .= $char;
    }
    elseif ($char == "'" and $quoted and ($pre_char != "\\" or $pre_pre_char == "\\"))
    {
      $quoted = false;
      $query .= $char;
    }
    elseif ($char == ";" and !$quoted)
    {
      array_push($sql_queries, $query);
      $query = "";
    }
    else
    {
      $query .= $char;
    }
  }

  // execute the queries
  for ($i = 0; $i < count($sql_queries); $i++)
  {
    dbQuery($sql_queries[$i]);
  }
}

?>
