<?php

################################################################################
#
# $Id: view.php,v 1.1 2006/03/16 00:05:18 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_POLL", "H_VIEW_NO_POLL");
$content_tpl->set_block("F_CONTENT", "B_CHOICE", "H_CHOICE");
$content_tpl->set_block("F_CONTENT", "B_VIEW_POLL", "H_VIEW_POLL");

// access for admins only
if ($user['usertype_admin'])
{
  // polls query
  $polls_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_polls` WHERE `id_season` = {$_REQUEST['sid']}");
  if (dbNumRows($polls_ref) == 1)
  {
    $polls_row = dbFetch($polls_ref);

    $content_tpl->set_var("I_POLL_HEADING", $polls_row['heading']);
    $content_tpl->set_var("I_POLL_BODY", nl2br($polls_row['body']));
    $choices = explode(";", $polls_row['choices']);
    // votes query
    $votes_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_votes` WHERE `id_poll` = {$polls_row['id']}");
    $num_votes = 0;
    while ($votes_row = dbFetch($votes_ref))
    {
      // users query
      $users_ref = dbQuery("SELECT * " .
			    "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}season_users` SU " .
			    "WHERE U.`id` = {$votes_row['id_user']} " .
			    "AND SU.`id_season` = {$_REQUEST['sid']} " .
			    "AND SU.`rejected` = 0 " .
			    "AND U.`id` = SU.`id_user`");
      if (dbNumRows($users_ref) == 1)
      {
	$num_votes++;
      }
    }

    foreach($choices as $choice) {
      // votes query
      $votes_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}signup_votes` WHERE `id_poll` = {$polls_row['id']} AND `vote` = '$choice'");
      $num_choice_votes = 0;
      while ($votes_row = dbFetch($votes_ref))
      {
	// users query
	$users_ref = dbQuery("SELECT * " .
			      "FROM `{$cfg['db_table_prefix']}users` U, `{$cfg['db_table_prefix']}season_users` SU " .
			      "WHERE U.`id` = {$votes_row['id_user']} " .
			      "AND SU.`id_season` = {$_REQUEST['sid']} " .
			      "AND SU.`rejected` = 0 " .
			      "AND U.`id` = SU.`id_user`");
	if (dbNumRows($users_ref) == 1)
	{
	  $num_choice_votes++;
	}
      }

      $content_tpl->set_var("I_CHOICE", $choice);
      if ($num_votes == 0)
      {
	$content_tpl->set_var("I_WIDTH", number_format(0, 0));
      }
      else
      {
	$content_tpl->set_var("I_WIDTH", number_format($num_choice_votes / $num_votes * 100 * 3, 0));
      }
      $content_tpl->set_var("I_VOTES", $num_choice_votes);
      if ($num_votes == 0)
      {
	$content_tpl->set_var("I_VOTES_PERCENT", number_format(0, 0));
      }
      else
      {
	$content_tpl->set_var("I_VOTES_PERCENT", number_format($num_choice_votes / $num_votes * 100, 2));
      }
      $content_tpl->parse("H_CHOICE", "B_CHOICE", true);
    }
    $content_tpl->parse("H_VIEW_POLL", "B_VIEW_POLL");
  }
  else
  {
    $content_tpl->parse("H_VIEW_NO_POLL", "B_VIEW_NO_POLL");
  }
}
else
{
  $content_tpl->parse("H_WARNING_NO_ACCESS", "B_WARNING_NO_ACCESS");
  $content_tpl->parse("H_WARNING", "B_WARNING");
}

?>
