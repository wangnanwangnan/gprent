<?php
   return [
   'store' => [
        'class'  => 'fecshop\services\Store',
        'stores' => [
            // store key������ȥ��http���֣���Ϊkey����������������塣
            'fecshop.appserver.fancyecommerce.com' => [
                'language'         => 'en_US',        // ���Լ�����Ҫ��@common/config/fecshop_local_services/FecshopLang.php �ж��塣
                'languageName'     => 'English',    // ���Լ����Ӧ���������ƣ���������������л��б�����ʾ��
                'currency'         => 'USD', // ��ǰstore��Ĭ�ϻ���,������Ҽ��룬�����ڻ�������������
                
                // ����sitemap������������
                'https'            => false,
                
            ],
            
        ],

    ],

];
