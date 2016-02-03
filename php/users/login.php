<?php

################################################################################
#
# $Id: login.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_LOGGED_IN", "H_MESSAGE_LOGGED_IN");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LOGIN_FAILED", "H_WARNING_LOGIN_FAILED");
$content_tpl->set_block("F_CONTENT", "B_WARNING_USERNAME", "H_WARNING_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");

$username = dbEscape($_REQUEST['username']);
$users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
		      "WHERE `username` = '$username'");
if ($users_row = dbFetch($users_ref))
{
  $password = crypt($_REQUEST['password'], substr($users_row['password'], 0, 2));
  if ($password == $users_row['password'])
  {
    // set cookie
    if (isset($_REQUEST['remember']))
    {
      // expiration in 1 year
      $expire = time() + 3600 * 24 * 365;
    }
    else
    {
      // session cookie expiration
      $expire = null;
    }
    $user_id_md5 = serialize(array($users_row['id'], md5($password)));
    setcookie("user_id", $user_id_md5, $expire, $cfg['path']);
    setUser($user_id_md5);

    $content_tpl->parse("H_MESSAGE_LOGGED_IN", "B_MESSAGE_LOGGED_IN");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }
  else
  {
    $content_tpl->parse("H_WARNING_LOGIN_FAILED", "B_WARNING_LOGIN_FAILED");
    $content_tpl->parse("H_WARNING", "B_WARNING");
  }
}
else
{
  $content_tpl->parse("H_WARNING_LOGIN_FAILED", "B_WARNING_LOGIN_FAILED");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
