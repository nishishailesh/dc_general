#!/bin/bash
#only blank
mysqldump  -d -uroot dc_general -p > dc_general_blank.sql 
for tname in examination profile report 
do
	mysqldump  -uroot dc_general $tname -p$password > "dc_general_$tname.sql"
done
git add *
git commit
git push https://github.com/nishishailesh/dc_general master
