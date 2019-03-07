#!bin/bash
/wait && php artisan migrate -v && php artisan sync:structure -v && php artisan serve --host=0.0.0.0 --port=9000 -v
