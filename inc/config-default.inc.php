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

$cfg['mail_enabled'] = false;
$cfg['mail_from_address'] = "";     // i.e. "user@host"
$cfg['mail_reply_to_address'] = ""; // i.e. "Name <user@host>"
$cfg['mail_return_path'] = "";      // i.e. "user@host"
$cfg['mail_bcc_address'] = "";      // i.e. "user@host"

////////////////////////////////////////////////////////////////////////////////
// misc
////////////////////////////////////////////////////////////////////////////////

$cfg['tourney_name'] = "phpTourney Network";  // name of the site
$cfg['host'] = "http://";                     // host where this script is installed
$cfg['path'] = "/";                           // path to the script root
$cfg['bot_enabled'] = false;                  // enable the eggdrop bot
$cfg['convert'] = "convert";                  // path to image magick convert

?>
