<?php

// Database
$cfg['db_host'] = getenv("PHPTOURNEY_DB_HOST") ?: "localhost";
$cfg['db_port'] = getenv("PHPTOURNEY_DB_PORT") ?: "3306";
$cfg['db_username'] = getenv("PHPTOURNEY_DB_USERNAME") ?: "phptourney";
$cfg['db_password'] = getenv("PHPTOURNEY_DB_PASSWORD") ?: "";
$cfg['db_name'] = getenv("PHPTOURNEY_DB_DATABASE") ?: "phptourney";

// Mail
$cfg['mail_from'] = "";
$mail_from_name = getenv("PHPTOURNEY_MAIL_FROM_NAME");
$mail_from_address = getenv("PHPTOURNEY_MAIL_FROM_ADDRESS");
if ($mail_from_address)
{
  if ($mail_from_name)
  {
    $cfg['mail_from'] = $mail_from_name . "<" . $mail_from_address . ">";
  }
  else
  {
    $cfg['mail_from'] = $mail_from_address;
  }
}

// Site
$cfg['tourney_name'] = getenv("PHPTOURNEY_TOURNEY_NAME") ?: "phpTourney";
$cfg['host'] = getenv("PHPTOURNEY_HOST") ?: "http://";
$cfg['path'] = getenv("PHPTOURNEY_PATH") ?: "/";
$cfg['convert'] = getenv("PHPTOURNEY_CONVERT") ?: "convert";

// Cap
$cfg['cap_frontend_url'] = getenv("PHPTOURNEY_CAP_FRONTEND_URL") ?: "";
$cfg['cap_backend_url'] = getenv("PHPTOURNEY_CAP_BACKEND_URL") ?: "";
$cfg['cap_site_key'] = getenv("PHPTOURNEY_CAP_SITE_KEY") ?: "";
$cfg['cap_secret_key'] = getenv("PHPTOURNEY_CAP_SECRET_KEY") ?: "";

ini_set("error_reporting", NULL);

if (getenv("PHPTOURNEY_DEBUG"))
{
  ini_set("error_reporting", E_ALL);
  ini_set("display_errors", "true");
}

?>