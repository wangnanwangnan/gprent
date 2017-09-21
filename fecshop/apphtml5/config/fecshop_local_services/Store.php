<?php
   return [
   'store' => [
        'class'  => 'fecshop\services\Store',
        'stores' => [
            // store key：域名去掉http部分，作为key，这个必须这样定义。
            'wap.gprent.cn' => [
                'language'         => 'zh_CN',
                'languageName'     => '中文',
                'localThemeDir'    => '@apphtml5/theme/terry/theme01',
                'thirdThemeDir'    => [],
                'currency'         => 'CNY',
                'sitemapDir' => '@apphtml5/web/cn/sitemap.xml',
            ],
        ],

    ],

];
