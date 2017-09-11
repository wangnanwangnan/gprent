<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 *
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'email' => [
        'mailerConfig' => [
            // 默认通用配置
            'default' => [
                'class'     => 'yii\swiftmailer\Mailer',
                
                'transport' => [
                    'class'       => 'Swift_SmtpTransport',
                    'host'        => 'smtp.qq.com',            //SMTP Host
                    'username'    => '2420577683@qq.com',   //SMTP 账号
                    'password'    => 'zdveeweuevnldjgd',    //SMTP 密码
                    'port'        => '587',                    //SMTP 端口
                    'encryption'  => 'tls',
                ],
                

                /*
                'transport' => [
                    'class'       => 'Swift_SmtpTransport',
                    'host'        => 'smtp.sendcloud.net',            //SMTP Host
                    'username'    => 'gprent@gprent.cn',   //SMTP 账号
                    'password'    => 'FnFNFE4VGcVWYZL8',    //SMTP 密码
                    'port'        => '25',                    //SMTP 端口
                    //'encryption'  => 'ssl',
                ],
                */
                'messageConfig'=> [
                   'charset'=> 'UTF-8',
                ],
            ],
        ],
    ],
];
