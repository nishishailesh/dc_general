#!/bin/bash
#only blank
mysqldump  -d -uroot dc_general -p > dc_general_blank.sql 
#full
#mysqldump  -uroot dc_general -p > dc_general.sql 
git add *
git commit
git push https://github.com/nishishailesh/dc_general master
