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

// Get a human-readable time difference between the given datetime and the current time
function getTimeDifferenceString($datetime)
{
  $dt = new DateTime($datetime);
  $current_dt = new DateTime();
  $diff = $current_dt->diff($dt);
  if ($diff->y > 0)
  {
    $years = $diff->y;
    return $years . ($years == 1 ? " year" : " years");
  }
  elseif ($diff->m > 0)
  {
    $months = $diff->m;
    return $months . ($months == 1 ? " month" : " months");
  }
  elseif ($diff->d > 0)
  {
    $days = $diff->d;
    if ($days < 7)
    {
      return $days . ($days == 1 ? " day" : " days");
    }
    else
    {
      $weeks = $diff->d % 7;
      return $weeks . ($weeks == 1 ? " week" : " weeks");
    }
  }
  elseif ($diff->h > 0)
  {
    $hours = $diff->h;
    return $hours . ($hours == 1 ? " hour" : " hours");
  }
  elseif ($diff->i > 0)
  {
    $minutes = $diff->i;
    return $minutes . ($minutes == 1 ? " minute" : " minutes");
  }
  else
  {
    $seconds = $diff->s;
    return $seconds . ($seconds == 1 ? " second" : " seconds");
  }
}

// Get a human-readable date for the given datetime
function getDateString($datetime)
{
  $dt = new DateTime($datetime);
  $date = $dt->format("M j, Y");
  return $date;
}

// Get a human-readable time for the given datetime
function getTimeString($datetime)
{
  $dt = new DateTime($datetime);
  $time = $dt->format("g:i A");
  return $time;
}

?>
