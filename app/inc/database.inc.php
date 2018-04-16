<?php

$mysqli = new mysqli($cfg['db_host'], $cfg['db_username'], $cfg['db_password'], $cfg['db_name'], $cfg['db_port']);
if ($mysqli->connect_error)
{
  die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8');

function dbQuery($sql_query)
{
  global $mysqli;
  return($mysqli->query($sql_query));
}

function dbNumRows($data_ref)
{
  return($data_ref->num_rows);
}

function dbFetch($data_ref)
{
  return($data_ref->fetch_assoc());
}

function dbEscape($str)
{
  global $mysqli;
  return $mysqli->real_escape_string($str);
}

?>
