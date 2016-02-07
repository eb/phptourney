<?php

################################################################################
#
# $Id: edit.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_SECTION", "H_SECTION");
$content_tpl->set_block("F_CONTENT", "B_SECTION_SELECTED", "H_SECTION_SELECTED");
$content_tpl->set_block("F_CONTENT", "B_EDIT_SEASON", "H_EDIT_SEASON");

// access for root only
if ($user['usertype_root'])
{
  $id_season = intval($_REQUEST['opt']);
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `id` = $id_season AND `deleted`  = 0");
  $seasons_row = dbFetch($seasons_ref);
  $content_tpl->set_var("I_ID_SEASON_OPT", $id_season); // XXX
  $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($seasons_row['name']));

  // sections-query
  $sections_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}sections` WHERE `deleted` = 0 ORDER BY `name` ASC");
  while ($sections_row = dbFetch($sections_ref))
  {
    $content_tpl->set_var("I_ID_SECTION", $sections_row['id']);
    $content_tpl->set_var("I_SECTION_NAME", htmlspecialchars($sections_row['name']));
    if ($sections_row['id'] == $seasons_row['id_section'])
    {
      $content_tpl->parse("H_SECTION", "B_SECTION_SELECTED", true);
    }
    else
    {
      $content_tpl->parse("H_SECTION", "B_SECTION", true);
    }
  }

  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
  $content_tpl->parse("H_EDIT_SEASON", "B_EDIT_SEASON");
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
