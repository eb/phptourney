<?php

////////////////////////////////////////////////////////////////////////////////
// Helper functions - Miscellaneous useful functions
//
// $Id: helpers.inc.php,v 1.3 2006/03/23 11:41:25 eb Exp $
//
// Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
////////////////////////////////////////////////////////////////////////////////

//================================================================================
// function execAction
// executes the current module-action
//================================================================================

function execAction()
{
  extract($GLOBALS);
  global $content_tpl;

  $act_file = "php/{$_REQUEST['mod']}/{$_REQUEST['act']}.php";
  if (file_exists($act_file))
  {
    $mod_name = ucwords(str_replace("_", " ", $_REQUEST['mod']));

    // template files
    $content_tpl = new Template("html/{$_REQUEST['mod']}", "remove");
    $content_tpl->set_file("F_CONTENT", "{$_REQUEST['act']}.html");

    // execute action
    require($act_file);

    // get content
    $content_tpl->parse("PAGE", "F_CONTENT");
    $content = $content_tpl->get("PAGE");
    unset($content_tpl);

    $main_tpl->set_var("I_MODULE_NAME", $mod_name);
    return($content);
  }
  else
  {
    $main_tpl->parse("H_FILE_NOT_FOUND", "B_FILE_NOT_FOUND");
    return("");
  }
}

//================================================================================
// function createSalt
// Creates a random salt consisting of two characters
//================================================================================

function createSalt()
{
  return(chr(round(mt_rand(97, 122))) . chr(round(mt_rand(97, 122))));
}

//================================================================================
// function isWholePositiveNumber
// Checks whether the given string is an integer
//================================================================================

function isWholePositiveNumber($string)
{
  return(is_numeric($string) and (intval($string) == floatval($string)) and intval($string) >= 0);
}

//================================================================================
// function isWord
// Checks whether the given string contains only [a-zA-Z0-9_]
//================================================================================

function isWord($string)
{
  $matches = array();
  preg_match("/[a-zA-Z0-9_]*/", $string, $matches);
  if ($matches[0] == $string)
  {
    return(true);
  }
  else
  {
    return(false);
  }
}

//================================================================================
// function addSpacesToLongWords
// Cuts strings in pieces
//================================================================================

function addSpacesToLongWords($string)
{
  $counter = 0;
  for ($i = 0; $i < strlen($string); $i++)
  {
    if (preg_match("/[^ \\t\\n]/", substr($string, $i, 1)))
    {
      $counter++;
    }
    else
    {
      $counter = 0;
    }
    if ($counter == 80)
    {
      $counter = 0;
      $string = substr($string, 0, $i + 1) . " " . substr($string, $i + 1);
      $i++;
    }
  }
  return($string);
}

//================================================================================
// function sendMail
// Wrapper function for mail()
//================================================================================

function sendMail($to, $subject, $message, $from_address, $reply_to_address, $return_path, $bcc_address)
{
  $headers =
    "From: " . $from_address . "\r\n" .
    "Reply-To: " . $reply_to_address . "\r\n";

  if (!empty($bcc_address))
  {
    $headers .=
      "Bcc: " . $bcc_address . "\r\n";
  }

  $headers .=
    "X-Mailer: PHP/" . phpversion();

  if (ini_get("safe_mode"))
  {
    mail($to, $subject, $message, $headers);
  }
  else
  {
    $parameters = "-f{$return_path}";
    mail($to, $subject, $message, $headers, $parameters);
  }
}

?>
