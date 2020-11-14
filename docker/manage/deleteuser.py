#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import sys
import argparse
import traceback
import pymysql

def run():
    try:
        argparser = argparse.ArgumentParser()
        argparser.add_argument('email', type=str)
        argparser.add_argument('id', type=int)
        args = argparser.parse_args()

        conn = pymysql.connect(
            host=os.environ['PHPTOURNEY_DB_HOST'], port=int(os.environ['PHPTOURNEY_DB_PORT']),
            user=os.environ['PHPTOURNEY_DB_USERNAME'], passwd=os.environ['PHPTOURNEY_DB_PASSWORD'],
            db=os.environ['PHPTOURNEY_DB_DATABASE']
        )
        c = conn.cursor(cursor=pymysql.cursors.DictCursor)

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s
        ''', (args.email,))
        users_rows = c.fetchall()
        if not users_rows:
            raise Exception('User doesn\'t exist')

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s AND `id` = %s
        ''', (args.email, args.id,))
        users_rows = c.fetchall()
        if not users_rows:
            raise Exception('ID and email don\'t match')

        c.execute('''
            SELECT * FROM `season_users` WHERE `id_user` = %s
        ''', (users_rows[0]['id'],))
        season_users_rows = c.fetchall()
        if season_users_rows:
            raise Exception('User account is not empty')

        c.execute('''
            SELECT * FROM `news` WHERE `id_user` = %s
        ''', (users_rows[0]['id'],))
        news_rows = c.fetchall()
        if news_rows:
            raise Exception('User account is not empty')

        c.execute('''
            SELECT * FROM `news_comments` WHERE `id_user` = %s
        ''', (users_rows[0]['id'],))
        news_comments_rows = c.fetchall()
        if news_comments_rows:
            raise Exception('User account is not empty')

        c.execute('''
            SELECT * FROM `matches` WHERE `id_player1` = %s OR `id_player2` = %s
        ''', (users_rows[0]['id'], users_rows[0]['id'],))
        matches_rows = c.fetchall()
        if matches_rows:
            raise Exception('User account is not empty')

        c.execute('''
            SELECT * FROM `match_comments` WHERE `id_user` = %s
        ''', (users_rows[0]['id'],))
        match_comments_rows = c.fetchall()
        if match_comments_rows:
            raise Exception('User account is not empty')

        c.execute('''
            DELETE FROM `users` WHERE `id` = %s
        ''', (users_rows[0]['id'],))

        conn.commit()

        conn.close()

    except Exception as e:
        print(traceback.format_exc(), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    run()