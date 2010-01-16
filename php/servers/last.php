<?php

################################################################################
#
# $Id: last.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_MESSAGE_LAST", "H_MESSAGE_LAST");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_LAST", "H_WARNING_LAST");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SERVERLIST", "H_WARNING_SERVERLIST");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// access for headadmins only
if ($user['usertype_headadmin'])
{
  $seasons_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}seasons` " .
			  "WHERE `id_section` = {$section['id']} AND `deleted` = 0 ORDER BY `submitted` DESC");
  while ($seasons_row = dbFetch($seasons_ref))
  {
    if ($seasons_row['id'] == $_REQUEST['sid'])
    {
      if ($seasons_row = dbFetch($seasons_ref))
      {
	if (file_exists("data/serverlists/{$seasons_row['id']}"))
	{
	  $f_serverlist = "data/serverlists/{$_REQUEST['sid']}";
	  $f_serverlist_old = "data/serverlists/{$seasons_row['id']}";
	  if (file_exists($f_serverlist))
	  {
	    unlink($f_serverlist);
	  }
	  copy($f_serverlist_old, $f_serverlist);
	  $content_tpl->parse("H_MESSAGE_LAST", "B_MESSAGE_LAST");
	  $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
	  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
	}
	else
	{
	  $content_tpl->parse("H_WARNING_SERVERLIST", "B_WARNING_SERVERLIST");
	  $content_tpl->parse("H_WARNING", "B_WARNING");
	  $content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	  $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
	}
      }
      else
      {
	$content_tpl->parse("H_WARNING_LAST", "B_WARNING_LAST");
	$content_tpl->parse("H_WARNING", "B_WARNING");
	$content_tpl->set_var("I_ID_SEASON", $_REQUEST['sid']);
	$content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
      }
    }
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
