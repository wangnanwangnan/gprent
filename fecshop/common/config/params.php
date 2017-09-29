<?php

return [
    //'adminEmail' => 'admin@example.com',
    //'supportEmail' => 'support@example.com',
    //'user.passwordResetTokenExpire' => 3600,
    
    'zmScore' => '680',
    'zmScoreLow' => '600',

    'memberCard' => [
                        'store' => 'www.gprent.cn',
                        'subject' => 'Gprent信用押金',
                        'member_level' => [
                                            '1' => 0.01,
                                            '2' => 100,
                                            '3' => 200
                                        ]
                    ],    

    'level' => [
        0 => [
            'day_num' => 18,
            'rent_price' => 800,
        ],
        1 => [
            'day_num' => 20,
            'rent_price' => 2000,
        ],
    ],
];
