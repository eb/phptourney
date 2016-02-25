<?php

unsetUser();

// Check user
if (isset($_COOKIE["user_id"]))
{
  setUser($_COOKIE["user_id"]);
}

function setUser($user_id_md5) {
  global $user;
  global $cfg;
  global $season;
  unsetUser($user);

  $user_id_md5 = stripslashes($user_id_md5);
  list($id_user, $md5_password) = unserialize($user_id_md5);

  if ($id_user != "")
  {
    $users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}users` WHERE `id` = $id_user");

    if ($users_row = dbFetch($users_ref) and $md5_password == md5($users_row['password']))
    {
      $user['uid'] = $users_row['id'];
      $user['username'] = $users_row['username'];

      if (!isset($season))
      {
        $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
  				   "WHERE `id_user` = {$users_row['id']} AND `id_season` = 0");
      }
      else
      {
        $season_users_ref = dbQuery("SELECT * FROM `{$cfg['db_table_prefix']}season_users` " .
  				   "WHERE `id_user` = {$users_row['id']} AND (`id_season` = 0 OR `id_season` = {$season['id']})");
      }
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
