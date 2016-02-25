<?php

require("inc/inc.php");

// Template files
$main_tpl = new Template("html", "remove");
$main_tpl->set_file("F_FULL", "full.html");

// Read version
$fh_version = fopen("VERSION", "r");
$main_tpl->set_var("I_VERSION", fread($fh_version, filesize("VERSION")));
fclose($fh_version);

$main_tpl->set_var("I_TOURNEY_NAME", $cfg['tourney_name']);
$main_tpl->set_var("I_SEASON_NAME", "");
if (isset($season))
{
  $main_tpl->set_var("I_SEASON_NAME", $season['name']);
}

// Default action, if none is set
if (!$_REQUEST['mod'] or !$_REQUEST['act'])
{
  $_REQUEST['mod'] = "news";
  $_REQUEST['act'] = "view";
  $_REQUEST['opt'] = "1";
}
$main_tpl->set_var("I_CONTENT", execAction());

// Parse and print the site
$main_tpl->pparse("PAGE", "F_FULL");

?>
