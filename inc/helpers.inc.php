<?php

// Executes the current module-action
function execAction()
{
  extract($GLOBALS);
  global $content_tpl;

  $act_file = "php/{$_REQUEST['mod']}/{$_REQUEST['act']}.php";
  if (file_exists($act_file))
  {
    $mod_name = ucwords(str_replace("_", " ", $_REQUEST['mod']));

    // Template files
    $content_tpl = new Template("html/{$_REQUEST['mod']}", "remove");
    $content_tpl->set_file("F_CONTENT", "{$_REQUEST['act']}.html");

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
