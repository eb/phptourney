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
        argparser.add_argument('searchstring', type=str)
        args = argparser.parse_args()

        conn = pymysql.connect(
            host=os.environ['PHPTOURNEY_DB_HOST'], port=int(os.environ['PHPTOURNEY_DB_PORT']),
            user=os.environ['PHPTOURNEY_DB_USERNAME'], passwd=os.environ['PHPTOURNEY_DB_PASSWORD'],
            db=os.environ['PHPTOURNEY_DB_DATABASE']
        )
        c = conn.cursor(cursor=pymysql.cursors.DictCursor)

        c.execute('''
            SELECT * FROM `users` WHERE `username` LIKE %s OR `email` LIKE %s
        ''', ('%'+args.searchstring+'%', '%'+args.searchstring+'%',))
        users_rows = c.fetchall()
        if not users_rows:
            print('Nothing found')
        elif len(users_rows) > 1:
            print('Users')
            print(list(map(lambda x: x[0], c.description)))
            for users_row in users_rows:
                print(list(users_row.values()))
        else:
            print('User')
            print(list(map(lambda x: x[0], c.description)))
            print(list(users_rows[0].values()))

            c.execute('''
                SELECT * FROM `season_users` WHERE `id_user` = %s
            ''', (users_rows[0]['id'],))
            season_users_rows = c.fetchall()
            if season_users_rows:
                print()
                print('Season Users')
                print(list(map(lambda x: x[0], c.description)))
                for season_users_row in season_users_rows:
                    print(list(season_users_row.values()))

            c.execute('''
                SELECT * FROM `news` WHERE `id_user` = %s
            ''', (users_rows[0]['id'],))
            news_rows = c.fetchall()
            print('%s news' % len(news_rows))

            c.execute('''
                SELECT * FROM `news_comments` WHERE `id_user` = %s
            ''', (users_rows[0]['id'],))
            news_comments_rows = c.fetchall()
            print('%s news comments' % len(news_comments_rows))
            for news_comments_row in news_comments_rows:
                print(list(news_comments_row.values()))

            c.execute('''
                SELECT * FROM `matches` WHERE `id_player1` = %s OR `id_player2` = %s
            ''', (users_rows[0]['id'], users_rows[0]['id'],))
            matches_rows = c.fetchall()
            print('%s matches' % len(matches_rows))

            c.execute('''
                SELECT * FROM `match_comments` WHERE `id_user` = %s
            ''', (users_rows[0]['id'],))
            match_comments_rows = c.fetchall()
            print('%s match comments' % len(match_comments_rows))

        conn.close()

    except Exception as e:
        print(traceback.format_exc(), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    run()
