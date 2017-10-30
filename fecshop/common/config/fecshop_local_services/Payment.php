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
    'payment' => [
        'noRelasePaymentMethod' => 'check_money',    // 不需要释放库存的支付方式。譬如货到付款，在系统中
                                                    // pending订单，如果一段时间未付款，会释放产品库存，但是货到付款类型的订单不会释放，
                                                    // 如果需要释放产品库存，客服在后台取消订单即可释放产品库存。
        'paymentConfig' => [        // 支付方式配置
            'standard' => [            // 标准支付类型：在购物车页面进入下单页面，填写支付信息，然后跳转到第三方支付网站的支付类型。
/*  
              'check_money' => [    // 货到付款类型。
                    'label'                => 'Check / Money Order',
                    //'image' => ['images/mastercard.png','common'] ,# 支付页面显示的图片。
                    'supplement'               => 'Off-line Money Payments', // 补充信息
                    'style'                    => '<style></style>',  // 补充css，您可以在这里填写一些css
                    'start_url'                => '@homeUrl/payment/checkmoney/start',    // 点击按钮后，跳转的url，在这个url里面写支付跳转前的提交信息。
                    'success_redirect_url'     => '@homeUrl/payment/success',            // 在支付平台支付成功后，返回的页面
                ],
                
                'paypal_standard' => [
                    'start_url'            => '@homeUrl/payment/paypal/standard/start',
                    'nvp_url'  => 'https://api-3t.sandbox.paypal.com/nvp',
                    'api_url'  => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                    'account'  => 'zqy234api1-facilitator_api1.126.com',
                    'password' => 'HF4TNTTXUD6YQREH',
                    'signature'=> 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
                    'label'=> 'PayPal Express Payments',
                    // 跳转到paypal确认后，返回fecshop的url
                    'return_url' => '@homeUrl/payment/paypal/standard/review',
                    // 取消支付后，返回fecshop的url
                    'cancel_url' => '@homeUrl/payment/paypal/standard/cancel',
                    // 支付成功后，返回fecshop的url
                    'success_redirect_url'    => '@homeUrl/payment/success',
                    // IPN地址
                    'ipn_url' => '@homeUrl/payment/paypal/standard/ipn',
                ],
  */              
                'alipay_standard' => [
                    'label'=> '支付宝支付',
                    // 跳转开始URL
                    'start_url'             => '@homeUrl/payment/alipay/standard/start',
                    // 支付完成后，跳转的地址。
                    'return_url'            => '@homeUrl/payment/alipay/standard/review',
                    // 支付宝发送消息，接收的地址。
                    //'ipn_url'               => '@homeUrl/payment/alipay/standard/ipn',
                    'ipn_url'               => '@homeUrl/payment/success/ipn',
                    'success_redirect_url'  => '@homeUrl/payment/success',
                ],
                
                
                /*
                'paypal_standard' => [    // paypal标准支付类型
                    'label'                 => 'PayPal Website Payments Standard',
                    'image'                 => ['images/paypal_standard.png', 'common'], // 支付页面显示的图片。
                    'supplement'            => 'You will be redirected to the PayPal website when you place an order. ', // 补充,j将被显示在前端页面支付列表底部。
                    // 选择支付后，进入到相应支付页面的start页面。
                    'start_url'            => '@homeUrl/payment/paypal/standard/start',
                    // 接收IPN消息的页面。
                    'IPN_url'                => '@homeUrl/payment/paypal/standard/ipn',
                    // 在第三方支付成功后，跳转到网站的页面
                    'success_redirect_url'    => '@homeUrl/payment/success',
                    // 进入paypal支付页面，点击取消进入网站的页面。
                    'cancel_url'            => '@homeUrl/payment/paypal/standard/cancel',
                    // 第三方支付网站的url
                    'payment_url'=> 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                    // IPN URL可以和上面的 payment_url 的值。因此不需要单独搞一个url配置了。
                    //'ipn_url'	 => 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'
                    //# 用户名
                    //'user' => 'zqy234api1-facilitator@126.com',
                    // 账号
                    'account'=> 'zqy234api1-facilitator@126.com',
                    // 密码
                    'password'=> 'HF4TNTTXUD6YQREH',
                    // 签名
                    'signature'=> 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
                ],
                */
            ],
            'express' => [    // 在购物车页面直接跳转到支付平台，譬如paypal快捷支付方式。
                'paypal_express' => [
                    'nvp_url'  => 'https://api-3t.sandbox.paypal.com/nvp',
                    'api_url'  => 'https://www.sandbox.paypal.com/cgi-bin/webscr',
                    'account'  => 'zqy234api1-facilitator_api1.126.com',
                    'password' => 'HF4TNTTXUD6YQREH',
                    'signature'=> 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ANB-xrkMmTHpTszFaUx2v4EHqknV',
                    'label'=> 'PayPal Express Payments',
                    // 跳转到paypal确认后，返回fecshop的url
                    'return_url' => '@homeUrl/payment/paypal/express/review',
                    // 取消支付后，返回fecshop的url
                    'cancel_url' => '@homeUrl/payment/paypal/express/cancel',
                    // 支付成功后，返回fecshop的url
                    'success_redirect_url'    => '@homeUrl/payment/success',
                    // IPN地址
                    'ipn_url' => '@homeUrl/payment/paypal/express/ipn',
                ],
            ],
        ],
        'childService' => [
            'paypal' => [
                'express_payment_method' => 'paypal_express',
                'standard_payment_method' => 'paypal_standard',
                'version'                => '109.0',
                // 是否使用证书的方式进行paypal api对接（https ssl）
                // 如果配置为true，那么必须在crt_file中配置证书地址。
                // 默认不使用证书验证
                'use_local_certs' => false,
                'crt_file'        => [
                    'www.paypal.com'    => '@fecshop/services/payment/cert/paypal.crt',
                    'api-3t.paypal.com' => '@fecshop/services/payment/cert/api-3tsandboxpaypalcom.crt',
                ],
            ],
            'alipay' => [
                // 沙盒环境在：https://openhome.alipay.com/platform/appDaily.htm 这里获取。
                // 沙盒环境 - 商家appId 在这里获取 https://openhome.alipay.com/platform/appDaily.htm?tab=info
                'appId'         => '2017090808612593',
                // 沙盒环境 - 商家UID ： https://openhome.alipay.com/platform/appDaily.htm?tab=account
                'sellerId'      => '2088821009039857',
                // 应用私钥，可以在这里通过工具生成：https://docs.open.alipay.com/291/105971/
                'rsaPrivateKey' => 'MIIEogIBAAKCAQEAp+5OxtqLBN7vhYO8V+4cFQ7iQBKmASgHqbYyGHFf8xORPR9sDphlNC+PBYAi6Jsjd2pRpfj335/ht8MPJ/Z3iXcscxI3Ex6dCcuz8R/UeXISs18yACNQQ5FhmcHUmKk2cxaW7/fOIk+UxnJJBWB6zPi2GEi9FA+f+QtjlYqFo26R3KS7XnvUtTIga7JmIL8YiO4662e877hZkEwmrMDT0OMqBtS8d6lvqNgEn1KW8uyvva9DzLHfpxAuf+DmiRudb1ATcqD9fF0paXwkSl2E/VisYCyS8JfPSqsISyD+pQL1JdidwuvVSuAFQJST74FMWtkXaMxSdTjhjsqSqB13ZQIDAQABAoIBAArKSi+GzVXzySooC8sOlqlXunvpdtcuLkfeWBuv9T1WLYfi4/uQGTigMa1lzVhq132NzE9AzxSEXmmDVA76TDX9/VIrgnRtDCQb/aTpqapgBQmXfUv6+OZOihnqFXH1tXTxp6Mlpg4YVPPnVf/NcjaHaJPo4JVQZ7QF4lEyhGG61168vlkNEwFE5swlSBVL1pFpZflHA0VRX4aKFAt/qtaNRmozPuColoHx/XW9P2CWbp0B5mEPW/PJWFNG8YxC2J2/P6EuuYlVNjofNjF6piIO4Z/ekgDAJKxJCxis3Lyj6mjHj+NtI+OF0N0sZs/fJVp1Mplg3O48nZz+FiYfPkUCgYEA2zEV2DumKCLlilSq4lGeQOkhV9ituv8kAMUTZqsBHgEjZaS/Wwz3F9hFYQd4kGZGe+/FbnRQCGFpyF3W2mDPXjAzDsGH8D82CW1Rjrh5QH9VBoR+vbJgHZs94FpdVBZitAvPXefs99Mtu/Qs6KcOI3kbJ2Etal9py1cLVzn8TycCgYEAxCGM/1tJLX4urAiOR4cnaovNKuO7+v0/ALW5mJPpwjMzJ5GVZ/z/W5y/LQCOJgzfo8I1vZPak0vkxBTUroYEVhGWZj0VPSPwYIbuMo5/MS2F8mlv1Otoi8j6DSXW2hEb9yKMhaWOgAyJOjjvXDw7pFDmLeHXD4isv23PdJAmXJMCgYBwoLEz0bqYBw5hXQ4NipjBi8kZRXpHitBqINnOOHIzg6w3j0bQN0JEG1nS+K7Hq/Xtuw98qQFyvPNJBIbg4TvMjwG9RE5gcWqHv4dXyYxsSsFavvwM5zoiHGHYBTbNfU8saqEcBI8r7HQkjtwAk85dBd6hBnr6nJpU5J4sLNxrowKBgGCzCgl8wH+lju1S6pNpl414kBdtYOlGoyF+d5s6ki4lgDsqFDfJDT3l8nwFohAwmLLstgJaO5IUAR6MBrBlcw3cbgLKawZSCdoNrLNQfnWItFnokjKwPkNtO5vv5BZwCRG3/wCFt9R6Wc5S8/DN4boKCPLmPlpyUHmxcm9OAxvxAoGAXEki5kTfZqRyiR2pjyGioDwZU5Nt8EKpFa3EY1t0pWRyFckJgCY2YNp68j3VBoTNkNCdU9kTA44CJilOVSjO7Gs5M3FMlIF3voSlmmksnHBEDA/07SAGjfOzD8Jtkes8Af06xeW6bQ3jbqf86q1SJjwtX0XFn5+ZSUCELKNW0PY=',

                // 支付宝公钥，注意，这里不是应用私钥，需要把应用公钥提交后获取的支付宝公钥
                // 对于沙盒账户的步骤可以参看：http://blog.csdn.net/terry_water/article/details/75258175
                'alipayrsaPublicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyyB7Ma2X0RNZS0ja3LuOqRYV840wNhoEyYH6bjYoPIgD4Ndn1Ma9zMO0n4hqfUHgN/T+JP6rjdXYytZbLZlQBBCKTa5liietNfkfwLzL8jaZ5DOvxrs0FJeiTprKL3Toxby2NRHY4VUinp4qnH+y5OfzVBDm3t4nJSt7eMw4wGvapQLPqfmy3iWX8vozAzIXkJbrPG27vZhrHmnvd/ZQGZPu0F9hVr+0Xgwgb8b2jN4BFosqSvR0OZEOchaHVsWsGtLT+pK/743iYcj3xqHS2T8Gzuwjz6SBPr1vJHIBLrzF05P5J1pFQNFhIfuywNYAtpLld1WvBd7qm/K1Cne9CQIDAQAB',
                
                // 下面是沙盒地址， 正式环境请改为：https://openapi.alipay.com/gateway.do
                'gatewayUrl'    => 'https://openapi.alipay.com/gateway.do', 
                //'gatewayUrl'    => 'https://openapi.alipaydev.com/gateway.do', 
            ],
        ],
    ],
];
