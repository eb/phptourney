<?php

################################################################################
#
# $Id: overview.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_NO_SECTIONS", "H_NO_SECTIONS");
$content_tpl->set_block("F_CONTENT", "B_SECTION", "H_SECTION");
$content_tpl->set_block("F_CONTENT", "B_SECTIONS", "H_SECTIONS");
$content_tpl->set_block("F_CONTENT", "B_OVERVIEW_SECTIONS", "H_OVERVIEW_SECTIONS");

// access for root only
if ($user['usertype_root'])
{
  // sections-query
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `deleted` = 0 ORDER BY `name` ASC");
  if (dbNumRows($sections_ref) <= 0)
  {
    $content_tpl->parse("H_NO_SECTIONS", "B_NO_SECTIONS");
  }
  else
  {
    $section_counter = 0;
    while ($sections_row = dbFetch($sections_ref))
    {
      $content_tpl->set_var("I_SECTION_COUNTER", ++$section_counter);
      $content_tpl->set_var("I_ID_SECTION", $sections_row['id']);
      $content_tpl->set_var("I_SECTION_NAME", htmlspecialchars($sections_row['name']));
      $content_tpl->set_var("I_SECTION_ABBREVIATION", htmlspecialchars($sections_row['abbreviation']));
      $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
      $content_tpl->parse("H_SECTION", "B_SECTION", true);
    }
    $content_tpl->parse("H_SECTIONS", "B_SECTIONS");
  }
  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_OVERVIEW_SECTIONS", "B_OVERVIEW_SECTIONS");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
