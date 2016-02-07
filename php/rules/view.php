<?php

################################################################################
#
# $Id: view.php,v 1.2 2006/04/28 19:37:59 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_RULES", "H_VIEW_NO_RULES");
$content_tpl->set_block("F_CONTENT", "B_ADD_SEASON_RULES", "H_ADD_SEASON_RULES");
$content_tpl->set_block("F_CONTENT", "B_ADD", "H_ADD");
$content_tpl->set_block("F_CONTENT", "B_ADD_EXISTING", "H_ADD_EXISTING");
$content_tpl->set_block("F_CONTENT", "B_EDIT_DELETE", "H_EDIT_DELETE");
$content_tpl->set_block("F_CONTENT", "B_VIEW_RULES", "H_VIEW_RULES");

if ($user['usertype_admin'])
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_ADD", "B_ADD");

  // seasons-query
  $seasons_ref = dbQuery("SELECT S1.`id`, S1.`name` AS season_name, S2.`name` AS section_name " .
			  "FROM `{$cfg['db_table_prefix']}seasons` S1, `{$cfg['db_table_prefix']}sections` S2 " .
			  "WHERE S1.`deleted` = 0 AND S1.`id` <> {$_REQUEST['sid']} AND S1.`id_section` = S2.`id`");
  if (dbNumRows($seasons_ref) > 0)
  {
    while ($seasons_row = dbFetch($seasons_ref))
    {
      $content_tpl->set_var("I_ID_SEASON", $seasons_row['id']);
      $content_tpl->set_var("I_SECTION_NAME", htmlspecialchars($seasons_row['section_name']));
      $content_tpl->set_var("I_SEASON_NAME", htmlspecialchars($seasons_row['season_name']));
      $content_tpl->parse("H_ADD_SEASON_RULES", "B_ADD_SEASON_RULES", true);
    }
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_ADD_EXISTING", "B_ADD_EXISTING");
  }
}

// rules-query
$rules_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}rules` " .
		      "WHERE `id_season` = {$season['id']} " .
		      "ORDER BY `subject`");
if (dbNumRows($rules_ref) <= 0)
{
  $content_tpl->parse("H_VIEW_NO_RULES", "B_VIEW_NO_RULES");
}
else
{
  while ($rules_row = dbFetch($rules_ref))
  {
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->set_var("I_ID_RULE", $rules_row['id']);
    $content_tpl->set_var("I_SUBJECT", htmlspecialchars($rules_row['subject']));
    $content_tpl->set_var("I_BODY", Parsedown::instance()->text($rules_row['body']));
    if ($user['usertype_admin'])
    {
      $content_tpl->parse("H_EDIT_DELETE", "B_EDIT_DELETE");
    }
    $content_tpl->parse("H_VIEW_RULES", "B_VIEW_RULES", true);
    }
}

?>
