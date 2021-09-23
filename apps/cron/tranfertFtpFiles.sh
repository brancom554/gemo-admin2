#!/bin/sh
# transfert files from ftp server to webserver + delete source files

/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepArticles/* /home/occazetneuf/data/import/RepArticles/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepContacts/* /home/occazetneuf/data/import/RepContacts/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepContrats/* /home/occazetneuf/data/import/RepContrats/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepImagesArticles/* /home/occazetneuf/data/import/RepImagesArticles/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepImagesMagasins/* /home/occazetneuf/data/import/RepImagesMagasins/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepMagasins/* /home/occazetneuf/data/import/RepMagasins/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepStock/* /home/occazetneuf/data/import/RepStock/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepTypeFamille/* /home/occazetneuf/data/import/RepTypeFamille/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepTypeSousFamille/* /home/occazetneuf/data/import/RepTypeSousFamille/ &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' root@vm73.jiscomputing.com:/home/occaz-desktop/import/RepVentes/* /home/occazetneuf/data/import/RepVentes/ &

# transfert files from ftp server to webserver

/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' /home/occazetneuf/data/import/backup/* root@vm73.jiscomputing.com:/home/occaz-desktop/import/backup/  &
/usr/bin/rsync --remove-source-files -avrz -e 'ssh -p 222' /home/occazetneuf/data/import/error/* root@vm73.jiscomputing.com:/home/occaz-desktop/import/error/ &