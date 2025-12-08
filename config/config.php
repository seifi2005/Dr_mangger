<?php

return [
    'app' => [
        'name' => 'سیستم مدیریت پزشکان',
        'url' => 'http://localhost/medic/public',
        'timezone' => 'Asia/Tehran',
    ],
    
    'database' => [
        'host' => 'localhost',
        'name' => 'medical_system',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/jpg'],
        'path' => __DIR__ . '/../public/uploads/',
    ],
];

