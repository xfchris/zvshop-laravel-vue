<?php

return [
    'num_rows_per_table' => env('NUM_ROWS_PER_TABLE', 8),
    'num_product_rows_per_table' => env('NUM_PRODUCT_ROWS_PER_TABLE', 16),
    'currency' => env('CURRENCY', 'USD'),
    'image_max_size' => env('IMAGE_MAX_SIZE', 2048),
    'file_max_size' => env('FILE_MAX_SIZE', 20000),
    'image_products_max_number' => env('IMAGE_PRODUCTS_MAX_NUMBER', 8),
    'default_poster' => 'https://i.imgur.com/N8duDnu.png',
    'expiration_days' => 1,
    'reports_expiration_days' => 2,
    'report_directory' => 'reports/',
];
