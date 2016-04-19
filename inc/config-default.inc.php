<?php

// Database
$cfg['db_host'] = "localhost";
$cfg['db_username'] = "root";
$cfg['db_password'] = "";
$cfg['db_name'] = "";
$cfg['db_table_prefix'] = "";

// Mail
$cfg['mail_from_address'] = "";     // I.e. "user@host"
$cfg['mail_reply_to_address'] = ""; // I.e. "user@host"
$cfg['mail_return_path'] = "";      // I.e. "user@host"
$cfg['mail_bcc_address'] = "";      // I.e. "user@host"

// Bot
$cfg['bot_enabled'] = false;     // Enable the eggdrop bot
$cfg['bot_host'] = "";           // Host where the eggdrop bot is running
$cfg['bot_port'] = 0;            // Port where the eggdrop bot script is listening
$cfg['bot_password'] = "";       // Password of the eggdrop bot script
$cfg['bot_public_targets'] = ""; // Semicolon-separated list of IRC channels/nicknames for public messages
$cfg['bot_admin_targets'] = "";  // Semicolon-separated list of IRC channels/nicknames for admin messages

// Site
$cfg['tourney_name'] = "phpTourney"; // Name of the site
$cfg['host'] = "http://";            // Host where this script is installed
$cfg['path'] = "/";                  // Path to the script root
$cfg['convert'] = "convert";         // Path to image magick convert

$cfg['index_template_file'] = "index.tpl.html"; // File name of the template for index.php
$cfg['full_template_file'] = "full.tpl.html";   // File name of the template for full.php

ini_set("error_reporting", E_ALL);
ini_set("display_errors", true);

?>
