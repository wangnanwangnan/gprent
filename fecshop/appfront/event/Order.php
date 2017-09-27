<?php
namespace  appfront\event;

use Yii;

class Order
{
    public static function before($orderInfo){
        
        $productNum = count($orderInfo['products']);
        
        //print_r($orderInfo);exit;
        //$special_sku_arr = ['10001, 10002, 10003, 10005, 10006'];
        
        $orderProducts = $orderInfo['products'];
        
        /*
        $customer_id = Yii::$app->user->id;
        $filter = [
            'where'            => [
                ['customer_id' => $customer_id],
                //['order_status' => 'processing'],
            ],
            'asArray' => true,
        ];
        $customer_order_list_coll = Yii::$service->order->coll($filter);
        $customer_order_list = $customer_order_list_coll['coll'];
        foreach($customer_order_list as $customer_order){
            if($customer_order['order_status'] == 'processing' || $customer_order['order_status'] == 'holded'){
                
            }
        }
*/
        $productPrice = 0;
        foreach($orderProducts as $info){

            $primaryVal = $info['product_id'];
            $product = Yii::$service->product->getByPrimaryKey($primaryVal);
            $productPrice += $product['remark'];
            
            //print_r($product['remark']);exit;
        //    if($info['qty'] > 1 && in_array($info['sku'], $special_sku_arr)){
        //        echo '<script>alert("'.$info['name'].'为特价商品，租借时间不能超过一天，请修改");window.history.go(-2);</script>';
        //        exit;
            //}
        }
//echo $productPrice;exit;

/*
        if($productNum >1){
            echo '<script>alert("下单失败！内测阶段,每人最多只可租借一件商品");window.history.go(-2);</script>';
            exit;
        }
  */      
    }
    
    public static function after($orderInfo){
        $emailArr = ['617990822@qq.com', '2366629496@qq.com'];
        
        foreach($emailArr as $email){
            $sendInfo = [
                'to'        => $email,
                'subject'    => '已经有人下单，正在支付中',
                'htmlBody'    => $htmlBody,
                'senderName'=> Yii::$service->store->currentStore,
            ];
            
            Yii::$service->email->send($sendInfo, 'default');
        }
    }
}
