<?php

////////////////////////////////////////////////////////////////////////////////
// database
////////////////////////////////////////////////////////////////////////////////

$cfg['db_host'] = "localhost";
$cfg['db_username'] = "root";
$cfg['db_password'] = "";
$cfg['db_name'] = "";
$cfg['db_table_prefix'] = "";

////////////////////////////////////////////////////////////////////////////////
// mail
////////////////////////////////////////////////////////////////////////////////

$cfg['mail_from_address'] = "";     // i.e. "user@host"
$cfg['mail_reply_to_address'] = ""; // i.e. "user@host"
$cfg['mail_return_path'] = "";      // i.e. "user@host"
$cfg['mail_bcc_address'] = "";      // i.e. "user@host"

////////////////////////////////////////////////////////////////////////////////
// bot
////////////////////////////////////////////////////////////////////////////////

$cfg['bot_enabled'] = false;         // enable the eggdrop bot
$cfg['bot_host'] = "";               // host where the eggdrop bot is running
$cfg['bot_port'] = 0;                // port where the eggdrop bot script is listening
$cfg['bot_password'] = "";           // password of the eggdrop bot script
$cfg['bot_public_targets'] = "";     // semicolon-separated list of IRC channels/nicknames for public messages
$cfg['bot_admin_targets'] = "";      // semicolon-separated list of IRC channels/nicknames for admin messages

////////////////////////////////////////////////////////////////////////////////
// misc
////////////////////////////////////////////////////////////////////////////////

$cfg['tourney_name'] = "phpTourney"; // name of the site
$cfg['host'] = "http://";            // host where this script is installed
$cfg['path'] = "/";                  // path to the script root
$cfg['convert'] = "convert";         // path to image magick convert

ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);

?>
