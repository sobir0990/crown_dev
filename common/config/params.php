<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'upload_dir_file' => getenv( 'UPLOAD_DIR_FILE' ),
    'upload_dir_file_src' => getenv( 'UPLOAD_DIR_FILE_SRC' ),
    'thumbs' => [
        'icon' => [
            'w' => 50,
            'h' => 50,
            'q' => 65,
            'slug' => 'icon'
        ],
        'small' => [
            'w' => 320,
            'h' => 320,
            'q' => 65,
            'slug' => 'small'
        ],
        'low' => [
            'w' => 640,
            'h' => 640,
            'q' => 65,
            'slug' => 'low'
        ],
        'normal' => [
            'w' => 1024,
            'h' => 1024,
            'q' => 65,
            'slug' => 'normal'
        ]
    ],
    'images_ext' => [
        'jpg',
        'jpeg',
        'png',
        'bmp',
        'gif'
    ],
    'use_file_name' => true,
    'use_queue' => false,
    'file_not_founded' => '14',
];
