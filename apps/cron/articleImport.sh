#!/bin/sh
# actual import
/bin/mv /home/devoccaz/data/import/RepImagesArticles/*.* /home/devoccaz/www/filesLib/images/articles_img/ &
# /bin/rm /home/devoccaz/www/filesLib/articles_thumbnail/* -f
/usr/bin/php5 -q /home/devoccaz/apps/cron/php/ArticlesImport.php >/dev/null &
/home/devoccaz/apps/cron/purge_cache.sh &

/usr/bin/php5 -q /home/devoccaz/apps/cron/php/famille.php >/dev/null &
