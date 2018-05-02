<?php

// Check sid
if (!isset($_REQUEST['sid']) or !isWholePositiveNumber($_REQUEST['sid']))
{
  $_REQUEST['sid'] = 0;
}

// Get season data
$seasons_ref = dbQuery("SELECT * FROM `seasons` WHERE `id` = {$_REQUEST['sid']} AND `deleted` = 0");
$season = dbFetch($seasons_ref);

// Check mod
if (!isset($_REQUEST['mod']) or !isWord($_REQUEST['mod']))
{
  $_REQUEST['mod'] = "";
}

// Check act
if (!isset($_REQUEST['act']) or !isWord($_REQUEST['act']))
{
  $_REQUEST['act'] = "";
}

// Check opt
if (!isset($_REQUEST['opt']))
{
  $_REQUEST['opt'] = "";
}

?>
