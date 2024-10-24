<?php

return [
    'role_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
        ],
        'subadmin' => [
            'users' => 'c,r,u'
        ],
    ],
    'user_roles' => [
        'admin' => [
            ['name' => "Admin", "email" => "admin@example.com", "password" => 'Test@123'],
        ],
        'subadmin' => [
            ['name' => "Sub Admin", "email" => "subadmin@example.com", "password" => 'Test@123'],
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];