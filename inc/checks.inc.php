<?php

$this_path = dirname(__FILE__);

// Magic quotes
if (ini_get("magic_quotes_gpc") == "1")
{
  exit("'magic_quotes_gpc' has to be set to 'Off'");
}
if (ini_get("magic_quotes_runtime") == "1")
{
  exit("'magic_quotes_runtime' has to be set to 'Off'");
}

// Register globals
if (ini_get("register_globals") == "1")
{
  exit("'register_globals' has to be set to 'Off'");
}

// Writable screenshots dir
if (!is_writable("$this_path/../data/screenshots"))
{
  exit("the directory 'data/screenshots' has to be writable");
}

// Writable serverlists dir
if (!is_writable("$this_path/../data/serverlists"))
{
  exit("the directory 'data/serverlists' has to be writable");
}

// Writable version file
if (!is_writable("$this_path/../VERSION"))
{
  exit("the file 'VERSION' has to be writable");
}

?>
