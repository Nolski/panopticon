#!bin/bash
npm install && \
    npm run dev && \
    /wait && \
    php artisan serve --host=0.0.0.0 --port=9000 -v
