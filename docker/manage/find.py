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
        args = argparser.parse_args()

        conn = pymysql.connect(
            host=os.environ['PHPTOURNEY_DB_HOST'], port=int(os.environ['PHPTOURNEY_DB_PORT']),
            user=os.environ['PHPTOURNEY_DB_USERNAME'], passwd=os.environ['PHPTOURNEY_DB_PASSWORD'],
            db=os.environ['PHPTOURNEY_DB_DATABASE']
        )
        c = conn.cursor(cursor=pymysql.cursors.DictCursor)

        c.execute('''
            SELECT `email` FROM `users` GROUP BY `email` HAVING COUNT(*) > 1
        ''',)
        users_rows = c.fetchall()
        if users_rows:
            print()
            print('Duplicate accounts')
            for users_row in users_rows:
                print(list(users_row.values())[0])

        c.execute('''
            SELECT `email` FROM `users`
                LEFT JOIN `season_users` ON `users`.`id` = `season_users`.`id_user`
                LEFT JOIN `news` ON `users`.`id` = `news`.`id_user`
                LEFT JOIN `news_comments` ON `users`.`id` = `news_comments`.`id_user`
                LEFT JOIN `matches` AS `m1` ON `users`.`id` = `m1`.`id_player1`
                LEFT JOIN `matches` AS `m2` ON `users`.`id` = `m2`.`id_player2`
                LEFT JOIN `match_comments` ON `users`.`id` = `match_comments`.`id_user`
                WHERE `season_users`.`id_user` IS NULL
                    AND `news`.`id_user` IS NULL
                    AND `news_comments`.`id_user` IS NULL
                    AND `m1`.`id_player1` IS NULL
                    AND `m2`.`id_player2` IS NULL
                    AND `match_comments`.`id_user` IS NULL
        ''',)
        users_rows = c.fetchall()
        if users_rows:
            print()
            print('Empty accounts')
            for users_row in users_rows:
                print(list(users_row.values())[0])

        conn.close()

    except Exception as e:
        print(traceback.format_exc(), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    run()