#!/usr/bin/env bash

mysqldump -h127.0.0.1 -p3306 -uroot -pWMxP7Xd9MVZtvZ1D --skip-add-drop-table --compact phptourney > docker/populate.sql
