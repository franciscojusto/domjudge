mysqldump --opt -u root --single-transaction domjudge | gzip > /home/ubuntu/domjudge/backup/db_backup_$(date +%Y-%m-%d).sql.gz
