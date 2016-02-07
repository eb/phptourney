<?php

################################################################################
#
# $Id: add.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_SECTION", "H_SECTION");
$content_tpl->set_block("F_CONTENT", "B_USERNAME", "H_USERNAME");
$content_tpl->set_block("F_CONTENT", "B_ADD_SEASON", "H_ADD_SEASON");

// access for root only
if ($user['usertype_root'])
{
  // sections-query
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `deleted` = 0 ORDER BY `name` ASC");
  while ($sections_row = dbFetch($sections_ref))
  {
    $content_tpl->set_var("I_ID_SECTION", $sections_row['id']);
    $content_tpl->set_var("I_SECTION_NAME", htmlspecialchars($sections_row['name']));
    $content_tpl->parse("H_SECTION", "B_SECTION", true);
  }

  // users-query
  $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` " .
			"ORDER BY `username` ASC");
  while ($users_row = dbFetch($users_ref))
  {
    $content_tpl->set_var("I_ID_USER", $users_row['id']);
    $content_tpl->set_var("I_USERNAME", htmlspecialchars($users_row['username']));
    $content_tpl->set_var("I_EMAIL", htmlspecialchars($users_row['email']));
    $content_tpl->parse("H_USERNAME", "B_USERNAME", true);
  }

  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->set_var("I_USERNAME", "");
  $content_tpl->set_var("I_EMAIL", "");
  $content_tpl->parse("H_ADD_SEASON", "B_ADD_SEASON");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
