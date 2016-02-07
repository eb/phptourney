<?php

################################################################################
#
# $Id: view.php,v 1.2 2006/04/28 19:37:59 eb Exp $
#
# Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
#
################################################################################

// template blocks
$content_tpl->set_block("F_CONTENT", "B_NO_SERVERLIST", "H_NO_SERVERLIST");
$content_tpl->set_block("F_CONTENT", "B_SERVERLIST", "H_SERVERLIST");
$content_tpl->set_block("F_CONTENT", "B_SERVER_MANAGEMENT", "H_SERVER_MANAGEMENT");
$content_tpl->set_block("F_CONTENT", "B_VIEW_NO_SERVERS", "H_VIEW_NO_SERVERS");
$content_tpl->set_block("F_CONTENT", "B_SERVER", "H_SERVER");
$content_tpl->set_block("F_CONTENT", "B_COUNTRY", "H_COUNTRY");
$content_tpl->set_block("F_CONTENT", "B_VIEW_SERVERS", "H_VIEW_SERVERS");

$no_servers = 0;

// read serverlist
$f_serverlist = "data/serverlists/{$_REQUEST['sid']}";
if (file_exists($f_serverlist))
{
  // serverlist
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_SERVERLIST", "B_SERVERLIST");

  // servers
  $fh_serverlist = fopen($f_serverlist, "r");
  $serverlist = explode("\n", fread($fh_serverlist, filesize($f_serverlist)));
  fclose($fh_serverlist);

  $serverlist_assoc = array();
  foreach($serverlist as $server) {
    if (preg_match("/\"(.*)\"\s*\"(.*)\"\s*\"(.*)\"/", $server, $matches))
    {
      if (!isset($serverlist_assoc[$matches[3]]))
      {
	$serverlist_assoc[$matches[3]] = array();
      }
      array_push($serverlist_assoc[$matches[3]], array("name" => $matches[2], "address" => $matches[1]));
    }
  }

  ksort($serverlist_assoc);
  $no_servers = 1;
  foreach(array_keys($serverlist_assoc) as $country) {
    sort($serverlist_assoc[$country]);
    $no_servers = 0;
    $content_tpl->set_var("H_SERVER", "");
    foreach($serverlist_assoc[$country] as $server) {
      $content_tpl->set_var("I_NAME", htmlspecialchars($server['name']));
      $content_tpl->set_var("I_SERVER", htmlspecialchars($server['address']));
      $content_tpl->parse("H_SERVER", "B_SERVER", true);
    }
    $content_tpl->set_var("I_COUNTRY", htmlspecialchars($country));
    $content_tpl->parse("H_COUNTRY", "B_COUNTRY", true);
  }
  $content_tpl->parse("H_VIEW_SERVERS", "B_VIEW_SERVERS", true);
}
else
{
  $content_tpl->parse("H_NO_SERVERLIST", "B_NO_SERVERLIST");
  $content_tpl->parse("H_VIEW_NO_SERVERS", "B_VIEW_NO_SERVERS");
}
if ($user['usertype_headadmin'])
{
  $content_tpl->set_var("I_ID_SEASON", $season['id']);
  $content_tpl->parse("H_SERVER_MANAGEMENT", "B_SERVER_MANAGEMENT");
}
if ($no_servers)
{
  $content_tpl->parse("H_VIEW_NO_SERVERS", "B_VIEW_NO_SERVERS");
}

?>
