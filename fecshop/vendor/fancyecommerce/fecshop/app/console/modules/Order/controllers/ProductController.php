<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Order\controllers;

use Yii;
use yii\console\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends Controller
{
    public function actionReturnpendingstock()
    {
        Yii::$service->order->returnPendingStock();
    }

    /*

Array
(
    [order_id] => 55
    [increment_id] => 1100000055
    [order_status] => holded
    [store] => www.gprent.cn
    [created_at] => 1505213368
    [updated_at] => 1505213368
    [items_count] => 10
    [total_weight] => 0.00
    [order_currency_code] => CNY
    [order_to_base_rate] => 1.0000
    [grand_total] => 1.00
    [base_grand_total] => 1.00
    [subtotal] => 1.00
    [base_subtotal] => 1.00
    [subtotal_with_discount] => 
    [base_subtotal_with_discount] => 
    [is_changed] => 1
    [checkout_method] => standard
    [customer_id] => 13
    [customer_group] => 
    [steam_link] => https://steamcommunity.com/tradeoffer/new/?partner=387607368&token=iu0UJosY
    [customer_email] => 821566063@qq.com
    [customer_firstname] => 荣
    [customer_lastname] => 王
    [customer_is_guest] => 1
    [remote_ip] => 
    [coupon_code] => 
    [payment_method] => alipay_standard
    [shipping_method] => fast_shipping
    [shipping_total] => 0.00
    [base_shipping_total] => 0.00
    [customer_telephone] => 15158195883
    [customer_address_country] => 
    [customer_address_state] => 
    [customer_address_city] => 
    [customer_address_zip] => 
    [customer_address_street1] => 
    [customer_address_street2] => 
    [txn_type] => 
    [txn_id] => 2017091221001004240245826726
    [payer_id] => 
    [ipn_track_id] => 
    [receiver_id] => 
    [verify_sign] => 
    [charset] => 
    [payment_fee] => 
    [payment_type] => 
    [correlation_id] => 
    [base_payment_fee] => 
    [protection_eligibility] => 
    [protection_eligibility_type] => 
    [secure_merchant_account_id] => 
    [build] => 
    [paypal_order_datetime] => 
    [theme_type] => 
    [if_is_return_stock] => 2
    [payment_token] => 
    [version] => 0
)


       */

    public function actionRemind()
    {
        $orderStatusArr = Yii::$service->order->getStatusArr();
        //print_r($orderStatusArr);

        $filter =   [
                        'where' =>  [
                                ['order_status' => 'holded'],
                            ],
                            'asArray' => true,
                    ];
        $completeArr = Yii::$service->order->coll($filter);
        
        $emailArr = ['617990822@qq.com', '2366629496@qq.com'];
        $remindTime = 3600 * 24;
        $atonceTime = 3600;
        $currentTime = time();
        $delayedTime = 3600;
        foreach($completeArr['coll'] as $complete){
            $begiTime = $complete['created_at'];
            $rentTime = $complete['items_count'] * 3600 * 24;
            
            $returnTime = $begiTime + $rentTime + $delayedTime;

            $beginDate = date('Y-m-d H:i:s', $begiTime);
            $returnDate = date('Y-m-d H:i:s', $returnTime);
            
            //小于24小时提醒
            $diff = $returnTime - $currentTime;

            if($diff < $atonceTime){
                $htmlBody = $complete['customer_lastname'].$complete['customer_firstname'].'的道具将在'.$returnDate.'到期，别忘记收回（'.$beginDate.'-'.$returnDate.'），Steam链接：'.$complete['steam_link'].'，电话：'.$complete['customer_telephone'].'，邮箱：'.$complete['customer_email'];
                foreach($emailArr as $email){
                    $sendInfo = [
                        'to'        => $email,
                        'subject'    => '道具将马上到期！请及时收回',
                        'htmlBody'    => $htmlBody,
                        'senderName'=> Yii::$service->store->currentStore,
                    ];
                    Yii::$service->email->send($sendInfo, 'default');
                }
            }elseif($diff < $remindTime){
                $htmlBody = $complete['customer_lastname'].$complete['customer_firstname'].'的道具将在'.$returnDate.'到期，别忘记收回（'.$beginDate.'-'.$returnDate.'），Steam链接：'.$complete['steam_link'].'，电话：'.$complete['customer_telephone'].'，邮箱：'.$complete['customer_email'];
                
                foreach($emailArr as $email){
                    $sendInfo = [
                        'to'        => $email,
                        'subject'    => '道具收回提醒',
                        'htmlBody'    => $htmlBody,
                        'senderName'=> Yii::$service->store->currentStore,
                    ];
                    Yii::$service->email->send($sendInfo, 'default');
                }
            }
        }
    }

}
