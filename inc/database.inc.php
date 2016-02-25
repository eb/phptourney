<?php

$mysqli = new mysqli('p:' . $cfg['db_host'], $cfg['db_username'], $cfg['db_password'], $cfg['db_name']);
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
