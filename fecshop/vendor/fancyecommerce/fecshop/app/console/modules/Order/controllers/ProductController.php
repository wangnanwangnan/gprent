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
               //$remindTime = 3600 * 24;
        $currentTime = time();
        $oneHour = 3600;
        
        $htmlBody = '';
        $n = 0;

        foreach($completeArr['coll'] as $complete){
            //$beginTime = $complete['pay_updated_at'];
            //$beginDate = date('Y-m-d H:i:s', $beginTime);
            $order_item = Yii::$service->order->item->getByOrderId($complete['order_id']);
            foreach($order_item as $item_info){
                if($item_info['item_status'] == 'complete'){
                    continue;
                }

                $dayNum = $item_info['qty'];
                $rentItemTime = $dayNum * 3600 * 24;
                $item_name = $item_info['name'];        
                
                $beginTime = $item_info['updated_at'];
                $beginDate = date('Y-m-d H:i:s', $beginTime);
                
                $returnItemTime = $beginTime + $rentItemTime;
                $returnItemDate = date('Y-m-d H:i:s', $returnItemTime);
                $diff = $returnItemTime - $currentTime;

                //小于1小时提醒
                if($diff < $oneHour){
                    $n++;
                    $htmlBody .= $complete['customer_lastname'].$complete['customer_firstname'].'的道具('.$item_name.')将在'.$returnItemDate.'到期(总计：'.$dayNum.'天)，别忘记收回（'.$beginDate.'－'.$returnItemDate.'），Steam链接：'.$complete['steam_link'].'，电话：'.$complete['customer_telephone'].'，邮箱：'.$complete['customer_email'].'，订单号：'.$complete['increment_id']."\r\n<br><br>\r\n\r\n<br><br>\r\n";
                }
            }
        }
        $emailArr = ['gprent@163.com', '2366629496@qq.com'];
        //$emailArr = ['617990822@qq.com'];
        foreach($emailArr as $email){
            $sendInfo = [
                'to'        => $email,
                'subject'    => '总共'.$n.'件道具将马上到期!',
                'htmlBody'    => $htmlBody,
                'senderName'=> Yii::$service->store->currentStore,
            ];
            $r = Yii::$service->email->send($sendInfo, 'default');
        }

        //已支付订单数量
        $filter =   [
                        'where' =>  [
                                ['order_status' => 'processing'],
                                ['is_membercard' => 0],
                                ['is_delete' => 0],
                            ],
                            'asArray' => true,
                    ];
        $prolist = Yii::$service->order->coll($filter);
        $j = 0;
        $orderBody = '';
        if($prolist){
            foreach($prolist['coll'] as $complete){
                $addtime = date('Y-m-d H:i:s',$complete['create_at']);
                $orderBody .= $complete['customer_lastname'].$complete['customer_firstname'].'的订单('.$complete['increment_id'].'金额：('.$complete['grand_total'].'),在'.$addtime.')已支付,Steam链接：'.$complete['steam_link']." 请尽快确认是忘记改状态 还是没有发货\r\n<br><br>\r\n\r\n<br><br>\r\n";
            }
            $j = count($prolist['coll']);
        }
        
        //$emailArr = ['617990822@qq.com', '2366629496@qq.com'];
        $emailArr = ['gprent@163.com', '2366629496@qq.com'];
        //$emailArr = ['617990822@qq.com'];
        foreach($emailArr as $email){
            $sendInfo = [
                'to'        => $email,
                'subject'    => '当前共有'.$j.'单已支付 需尽快处理',
                'htmlBody'    => $orderBody,
                'senderName'=> Yii::$service->store->currentStore,
            ];
            $r = Yii::$service->email->send($sendInfo, 'default');
        }


    }

    //同步所有商品的价格
    public function actionGetproductprice()
    {
        $filter =   [
                        'where' =>  [
                                ['!=','igxe_url',''],
                            ],
                            'asArray' => true,
                    ];
        $completeArr = Yii::$service->product->coll($filter);
        if($completeArr){
            foreach($completeArr['coll'] as $pInfo){
                $url = $pInfo['igxe_url'];
                $params = $this->getParams($url);
                if($params['qprice']){
                    $pInfo['starting_price'] = $params['qprice'];
                    $a = Yii::$service->product->save($pInfo);
                }
                print_r($params);
            }
        }
    }

    //匹配需要的数据
    public function getParams($url)
    {
        $c = file_get_contents($url);
        
        //图片
        preg_match('/<div class="com-img">(.+?)<\/div>/is', $c, $img);
        
        //名称
        preg_match('/<div class="mod-equipmentDetail-bd" .+?>.+?<h1 class="h3" .+?>(.+?)<\/h1>/is', $c, $match);
        //市场价格
        preg_match('/<div class="mod-equipmentDetail-bd" .+?>.+?<strong .+?>(.+?)<\/strong>/is', $c, $match1);
        //起价
        preg_match('/<div class="mod-equipmentDetail-bd" .+?>.+?<b .+?>(.+?)<\/b>/is', $c, $match2);

        $res['qprice'] = $match2[1];

        return $res;

    }
}
