<?php
namespace  appfront\event;

use Yii;

class Order
{
    public static function before($orderInfo){
        
        $productNum = count($orderInfo['products']);
        
        //print_r($orderInfo);exit;
        $special_sku_arr = ['10001', '10002', '10003', '10005', '10006', '20003', '20002', '10008'];
        
        $orderProducts = $orderInfo['products'];
        
        $level = 0;
        $identity = Yii::$app->user->identity;
        if($identity){
            $level = $identity->level;
        }
        $level_info = Yii::$app->params['level'][$level];
        $maxCountAddToCart = $level_info['day_num'];
        $maxPriceAddToCart = $level_info['rent_price'];


        $customer_id = Yii::$app->user->id;
        /*
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
            if($info['qty'] > 5 && in_array($info['sku'], $special_sku_arr)){
                echo '<script>alert("'.$info['name'].'为特价商品，租借时间不能超过五天，请修改此商品租借天数");window.history.go(-2);</script>';
                exit;
            }

            if($info['qty'] > $maxCountAddToCart){
                echo '<script>alert("内测阶段，所有商品最多只能租用'.$maxCountAddToCart.'天，请修改'.$info['name'].'的租用天数，请谅解");window.history.go(-2);</script>';
                exit;
            }
            //获取订单的商品总金额
            $primaryVal = $info['product_id'];
            $product = Yii::$service->product->getByPrimaryKey($primaryVal);
            $productPrice += $product['cost_price'];
        }


        //获取用户累计租聘金额
        $customerModel = Yii::$service->customer->getByPrimaryKey($customer_id);
        if($customerModel){
            $t_price = $productPrice+$customerModel->summation_cost;

            if($t_price > $maxPriceAddToCart){
                $p = $maxPriceAddToCart-$customerModel->summation_cost;
                echo '<script>alert("所有在租商品总金额不能超过'.$maxPriceAddToCart.'，还可以租'.$p.'以内的道具 继续租用，请谅解");window.history.go(-2);</script>';
                exit;
            }
        }

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
