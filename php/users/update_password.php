<?php

################################################################################
#
# $Id: update_password.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_PASSWORD_EDITED", "H_MESSAGE_PASSWORD_EDITED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PASSWORD", "H_WARNING_PASSWORD");
$content_tpl->set_block("F_CONTENT", "B_WARNING_PASSWORD_RETYPED", "H_WARNING_PASSWORD_RETYPED");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");

// access for the user
if (!$_REQUEST['opt'] and $user['uid'])
{
  $is_complete = 1;
  if ($_REQUEST['password'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_PASSWORD", "B_WARNING_PASSWORD");
  }
  if ($_REQUEST['password'] != $_REQUEST['password_retyped'])
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_PASSWORD_RETYPED", "B_WARNING_PASSWORD_RETYPED");
  }

  if ($is_complete)
  {
    $password = dbEscape(crypt($_REQUEST['password'], createSalt()));
    dbQuery("UPDATE `{$cfg['db_table_prefix']}users` SET " .
	     "`password` = '$password' " .
	     "WHERE `id` = {$user['uid']}");
    $content_tpl->parse("H_MESSAGE_PASSWORD_EDITED", "B_MESSAGE_PASSWORD_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }

  if (!$is_complete)
  {
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
}

// access for headadmins
elseif ($_REQUEST['opt'] != "" and ($user['usertype_headadmin'] or $user['usertype_root']))
{
  $is_complete = 1;
  if ($_REQUEST['password'] == "")
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_PASSWORD", "B_WARNING_PASSWORD");
  }
  if ($_REQUEST['password'] != $_REQUEST['password_retyped'])
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_PASSWORD_RETYPED", "B_WARNING_PASSWORD_RETYPED");
  }

  if ($is_complete)
  {
    $id_user = intval($_REQUEST['opt']);
    $password = dbEscape(crypt($_REQUEST['password'], createSalt()));
    dbQuery("UPDATE `{$cfg['db_table_prefix']}users` SET " .
	     "`password` = '$password' " .
	     "WHERE `id` = $id_user");
    $content_tpl->parse("H_MESSAGE_PASSWORD_EDITED", "B_MESSAGE_PASSWORD_EDITED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
  }

  if (!$is_complete)
  {
    $content_tpl->parse("H_WARNING", "B_WARNING");
    $content_tpl->parse("H_BACK", "B_BACK");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
