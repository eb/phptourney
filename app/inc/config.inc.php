<?php

// Database
$cfg['db_host'] = getenv("PHPTOURNEY_DB_HOST") ?: "localhost";
$cfg['db_port'] = getenv("PHPTOURNEY_DB_PORT") ?: "3306";
$cfg['db_username'] = getenv("PHPTOURNEY_DB_USERNAME") ?: "phptourney";
$cfg['db_password'] = getenv("PHPTOURNEY_DB_PASSWORD") ?: "";
$cfg['db_name'] = getenv("PHPTOURNEY_DB_DATABASE") ?: "phptourney";
$cfg['db_table_prefix'] = getenv("PHPTOURNEY_DB_PREFIX") ?: "";

// Mail
$cfg['mail_from_address'] = "";     // I.e. "user@host"
$cfg['mail_reply_to_address'] = ""; // I.e. "user@host"
$cfg['mail_return_path'] = "";      // I.e. "user@host"
$cfg['mail_bcc_address'] = "";      // I.e. "user@host"

// Site
$cfg['tourney_name'] = "phpTourney"; // Name of the site
$cfg['host'] = "http://";            // Host where this script is installed
$cfg['path'] = "/";                  // Path to the script root
$cfg['convert'] = "convert";         // Path to image magick convert


ini_set("error_reporting", NULL);

if (getenv("PHPTOURNEY_DEBUG"))
{
  ini_set("error_reporting", E_ALL);
  ini_set("display_errors", "true");
}

?>
