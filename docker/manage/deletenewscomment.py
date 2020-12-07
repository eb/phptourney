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
            SELECT * FROM `news_comments` WHERE `id_user` = %s AND `id` = %s
        ''', (users_rows[0]['id'], args.id,))
        news_comments_rows = c.fetchall()
        if not news_comments_rows:
            raise Exception('ID and email don\'t match')

        c.execute('''
            DELETE FROM `news_comments` WHERE `id` = %s
        ''', (args.id,))

        conn.commit()

        conn.close()

    except Exception as e:
        print(traceback.format_exc(), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    run()
