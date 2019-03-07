# /bin/bash
php artisan sync:structure && \
php artisan sync:users && \
php artisan sync:data job-seeker && \
php artisan sync:data firm && \
php artisan sync:data match && \
php artisan sync:data job-opening && \
php artisan sync:activities job-seeker-monthly-followup && \
php artisan sync:activities firm-monthly-followup
