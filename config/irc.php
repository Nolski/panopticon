<?php

return [
    'commcare_api_key' => env('COMMCARE_API_KEY'),
    'ml_api_key' => env('ML_API_KEY'),
    'password_regex_validation' => '/^.*(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d].*$/',
];