#!/bin/bash
#only blank
read ppp
mysqldump  -d -uroot dc_general -p$ppp > dc_general_blank.sql
for tname in examination profile report
do
	mysqldump  -uroot dc_general $tname -p$ppp > "dc_general_$tname.sql"
done
git add *
git commit
git push https://github.com/nishishailesh/dc_general master
