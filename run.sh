#!bin/bash
npm install && \
    npm run production && \
    /wait && \
    php artisan migrate -v && \
    php artisan sync:structure -v && \
    php artisan cache:clear && \
    php artisan key:generate && \
    php artisan serve --host=0.0.0.0 --port=9000 -v
