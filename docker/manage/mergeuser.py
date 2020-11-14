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
        argparser.add_argument('email_dst', type=str)
        argparser.add_argument('id_dst', type=int)
        argparser.add_argument('email_src', type=str)
        argparser.add_argument('id_src', type=int)
        args = argparser.parse_args()

        conn = pymysql.connect(
            host=os.environ['PHPTOURNEY_DB_HOST'], port=int(os.environ['PHPTOURNEY_DB_PORT']),
            user=os.environ['PHPTOURNEY_DB_USERNAME'], passwd=os.environ['PHPTOURNEY_DB_PASSWORD'],
            db=os.environ['PHPTOURNEY_DB_DATABASE']
        )
        c = conn.cursor(cursor=pymysql.cursors.DictCursor)

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s
        ''', (args.email_dst,))
        dst_users_rows = c.fetchall()
        if not dst_users_rows:
            raise Exception('Destination user doesn\'t exist')

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s AND `id` = %s
        ''', (args.email_dst, args.id_dst,))
        dst_users_rows = c.fetchall()
        if not dst_users_rows:
            raise Exception('Destination ID and email don\'t match')

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s
        ''', (args.email_src,))
        src_users_rows = c.fetchall()
        if not src_users_rows:
            raise Exception('Source user doesn\'t exist')

        c.execute('''
            SELECT * FROM `users` WHERE `email` = %s AND `id` = %s
        ''', (args.email_src, args.id_src,))
        src_users_rows = c.fetchall()
        if not src_users_rows:
            raise Exception('Source ID and email don\'t match')

        c.execute('''
            UPDATE `season_users` SET id_user = %s WHERE `id_user` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))

        c.execute('''
            UPDATE `news` SET id_user = %s WHERE `id_user` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))

        c.execute('''
            UPDATE `news_comments` SET id_user = %s WHERE `id_user` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))

        c.execute('''
            UPDATE `matches` SET id_player1 = %s WHERE `id_player1` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))
        c.execute('''
            UPDATE `matches` SET id_player2 = %s WHERE `id_player2` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))

        c.execute('''
            UPDATE `match_comments` SET id_user = %s WHERE `id_user` = %s
        ''', (dst_users_rows[0]['id'], src_users_rows[0]['id'],))

        c.execute('''
            DELETE FROM `users` WHERE `id` = %s
        ''', (src_users_rows[0]['id'],))

        conn.commit()

        conn.close()

    except Exception as e:
        print(traceback.format_exc(), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    run()