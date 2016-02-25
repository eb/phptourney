<?php

$content_tpl->set_block("F_CONTENT", "B_MESSAGE_SERVERLIST_UPLOADED", "H_MESSAGE_SERVERLIST_UPLOADED");
$content_tpl->set_block("F_CONTENT", "B_MESSAGE", "H_MESSAGE");
$content_tpl->set_block("F_CONTENT", "B_WARNING_NO_ACCESS", "H_WARNING_NO_ACCESS");
$content_tpl->set_block("F_CONTENT", "B_WARNING_SERVERLIST", "H_WARNING_SERVERLIST");
$content_tpl->set_block("F_CONTENT", "B_WARNING", "H_WARNING");
$content_tpl->set_block("F_CONTENT", "B_BACK", "H_BACK");
$content_tpl->set_block("F_CONTENT", "B_BACK_OVERVIEW", "H_BACK_OVERVIEW");

// Access for headadmins only
if ($user['usertype_headadmin'])
{ 
  $src_file['serverlist'] = $_FILES['serverlist']['name'];
  $tmp_file['serverlist'] = $_FILES['serverlist']['tmp_name'];
  $dst_file['serverlist'] = "data/serverlists/{$season['id']}";

  $is_complete = 1;
  if (!is_uploaded_file($tmp_file['serverlist']) or !file_exists($tmp_file['serverlist']))
  {
    $is_complete = 0;
    $content_tpl->parse("H_WARNING_SERVERLIST", "B_WARNING_SERVERLIST");
  }

  if ($is_complete)
  {
    if (file_exists($dst_file['serverlist']))
    {
      unlink($dst_file['serverlist']);
    }
    move_uploaded_file($tmp_file['serverlist'], $dst_file['serverlist']);
    chmod($dst_file['serverlist'], 0646);
    $content_tpl->parse("H_MESSAGE_SERVERLIST_UPLOADED", "B_MESSAGE_SERVERLIST_UPLOADED");
    $content_tpl->parse("H_MESSAGE", "B_MESSAGE");
    $content_tpl->set_var("I_ID_SEASON", $season['id']);
    $content_tpl->parse("H_BACK_OVERVIEW", "B_BACK_OVERVIEW");
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
