<?php

////////////////////////////////////////////////////////////////////////////////
// User check - checks if a user is logged in/valid
//
// $Id: user.inc.php,v 1.1 2006/03/16 00:05:17 eb Exp $
//
// Copyright (c) 2004 A.Beisler <eb@subdevice.org> http://www.subdevice.org/
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
////////////////////////////////////////////////////////////////////////////////

unsetUser();

// check user
if (isset($_COOKIE["{$cfg['path']}" . $cfg['site_abbreviation'] . "data"]))
{
  setUser($_COOKIE["{$cfg['path']}" . $cfg['site_abbreviation'] . "data"]);
}

function setUser($cookie) {
  global $user;
  global $cfg;
  unsetUser($user);

  $cookie = stripslashes($cookie);
  list($id_user, $md5_password) = unserialize($cookie);

  if ($id_user != "")
  {
    // users query
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");

    if ($users_row = dbFetch($users_ref) and $md5_password == md5($users_row['password']))
    {
      $user['uid'] = $users_row['id'];
      $user['username'] = $users_row['username'];

      // season_users-query
      $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
				   "WHERE `id_user` = {$users_row['id']} AND (`id_season` = 0 OR `id_season` = {$_REQUEST['sid']})");
      while ($season_users_row = dbFetch($season_users_ref))
      {
	if ($season_users_row['usertype_root'] == 1)
	{
	  $user['usertype_root'] = $season_users_row['usertype_root'];
	}
	if ($season_users_row['usertype_headadmin'] == 1)
	{
	  $user['usertype_headadmin'] = $season_users_row['usertype_headadmin'];
	}
	if ($season_users_row['usertype_admin'] == 1)
	{
	  $user['usertype_admin'] = $season_users_row['usertype_admin'];
	}
	if ($season_users_row['usertype_player'] == 1)
	{
	  $user['usertype_player'] = $season_users_row['usertype_player'];
	}
      }
    }
  }
}

function unsetUser() {
  global $user;

  $user['uid'] = 0;
  $user['username'] = "";
  $user['usertype_root'] = 0;
  $user['usertype_headadmin'] = 0;
  $user['usertype_admin'] = 0;
  $user['usertype_player'] = 0;
}

?>
