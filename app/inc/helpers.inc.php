<?php

// Executes the current module-action
function execAction()
{
  extract($GLOBALS);
  global $content_tpl;

  $act_file = "modules/{$_REQUEST['mod']}/{$_REQUEST['act']}.php";
  if (file_exists($act_file))
  {
    $mod_name = ucwords(str_replace("_", " ", $_REQUEST['mod']));

    // Template files
    $content_tpl = new Template("modules/{$_REQUEST['mod']}");
    $content_tpl->set_file("F_CONTENT", "{$_REQUEST['act']}.tpl.html");

    // Execute action
    require($act_file);

    // Get content
    $content_tpl->parse("PAGE", "F_CONTENT");
    $content = $content_tpl->get("PAGE");
    unset($content_tpl);

    $main_tpl->set_var("I_MODULE_NAME", $mod_name);
    return($content);
  }
  else
  {
    return("");
  }
}

// Creates a random salt consisting of two characters
function createSalt()
{
  return(chr(round(mt_rand(97, 122))) . chr(round(mt_rand(97, 122))));
}

// Checks whether the given string is an integer
function isWholePositiveNumber($string)
{
  return(is_numeric($string) and (intval($string) == floatval($string)) and intval($string) >= 0);
}

// Checks whether the given string contains only [a-zA-Z0-9_]
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

// Checks whether the given string is an IP
function isIp($string)
{
  return(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $string));
}

// Sends email
function sendMail($to, $subject, $message, $from_address)
{
  $headers = "";

  if (!empty($from_address))
  {
    $headers .=
      "From: " . $from_address . "\r\n";
  }

  $headers .=
    "X-Mailer: PHP/" . phpversion();

  mail($to, $subject, $message, $headers);
}

?>
