<?php

// Database
$cfg['db_host'] = getenv("PHPTOURNEY_DB_HOST") ?: "localhost";
$cfg['db_port'] = getenv("PHPTOURNEY_DB_PORT") ?: "3306";
$cfg['db_username'] = getenv("PHPTOURNEY_DB_USERNAME") ?: "phptourney";
$cfg['db_password'] = getenv("PHPTOURNEY_DB_PASSWORD") ?: "";
$cfg['db_name'] = getenv("PHPTOURNEY_DB_DATABASE") ?: "phptourney";

// Mail
$cfg['mail_from_address'] = getenv("PHPTOURNEY_MAIL_FROM_ADDRESS") ?: "";

// Site
$cfg['tourney_name'] = getenv("PHPTOURNEY_TOURNEY_NAME") ?: "phpTourney";
$cfg['host'] = getenv("PHPTOURNEY_HOST") ?: "http://";
$cfg['path'] = getenv("PHPTOURNEY_PATH") ?: "/";
$cfg['convert'] = getenv("PHPTOURNEY_CONVERT") ?: "convert";

ini_set("error_reporting", NULL);

if (getenv("PHPTOURNEY_DEBUG"))
{
  ini_set("error_reporting", E_ALL);
  ini_set("display_errors", "true");
}

?>

