<?php

return [
    //'adminEmail' => 'admin@example.com',
    //'supportEmail' => 'support@example.com',
    //'user.passwordResetTokenExpire' => 3600,
    
    'zmScore' => '700',
    'zmScoreLow' => '640',

    'memberCard' => [
                        'store' => 'www.gprent.cn',
                        'subject' => 'Gprent信用押金',
                        'member_level' => [
                                            '1' => 198,
                                            '2' => 100,
                                            '3' => 200
                                        ]
                    ],    

    'level' => [
        0 => [
            'day_num' => 20,
            'rent_price' => 500,
            'special_num' => 1,
            'special_days' => 5,
        ],
        1 => [
            'day_num' => 20,
            'rent_price' => 2000,
            'special_num' => 1,
            'special_days' => 5,
        ],
    ],
    //黑名单
    'blacklist' => [
        '370782199904217438'
    ],
    'steam' => [
        'key' => '3DC2EC41F468ADAB42B8A549A1BB0CF3',
        'GetPlayerSummariesUrl' => 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/',
    ],
    'invite_coupon_config' => [
        'type' => 1, //1折扣 2现金
        'expiration_date' => 30, //有效期
        'conditions' => 1,//满多少金额
        'discount' => 50, //五折 60 为4折
    ],
    'newly_coupon_config' => [
        'type' => 2, //1折扣 2现金
        'expiration_date' => 7, //有效期
        'conditions' => 2.01,//满多少金额
        'discount' => 2, //减金额
    ],
];
