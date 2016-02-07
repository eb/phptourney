<?php

################################################################################
#
# $Id: add.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_ADD_COUNTRY", "H_ADD_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_SIGNUP", "H_SIGNUP");
$content_tpl->set_block("F_CONTENT", "B_ADD_ACCOUNT", "H_ADD_ACCOUNT");

// countries
$countries_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}countries` " .
			  "WHERE `active` = 1 " .
			  "ORDER BY `name` ASC");
while ($countries_row = dbFetch($countries_ref))
{
  $content_tpl->set_var("I_ID_COUNTRY", $countries_row['id']);
  $content_tpl->set_var("I_COUNTRY", htmlspecialchars($countries_row['name']));
  $content_tpl->parse("H_ADD_COUNTRY", "B_ADD_COUNTRY", true);
}

// signup
if ($season_exists and $season['status'] == "signups")
{
  $content_tpl->parse("H_SIGNUP", "B_SIGNUP");
}
   
$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
$content_tpl->parse("H_ADD_ACCOUNT", "B_ADD_ACCOUNT");

?>
