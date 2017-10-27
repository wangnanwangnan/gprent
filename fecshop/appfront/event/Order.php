<?php
namespace  appfront\event;

use Yii;

class Order
{
    public static function before($orderInfo){
        
        $orderProducts = $orderInfo['products'];
        
        $level = 0;
        $identity = Yii::$app->user->identity;
        if($identity){
            $level = $identity->level;
        }
        $level_info = Yii::$app->params['level'][$level];
        $maxCountAddToCart = $level_info['day_num'];
        $maxPriceAddToCart = $level_info['rent_price'];


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

        $customer_id = Yii::$app->user->id;
        $customerModel = Yii::$service->customer->getByPrimaryKey($customer_id);
        $customer_level = $customerModel->level;
        $customer_level_info = Yii::$app->params['level'][$customer_level];
        
        $productPrice = 0;
        $special_num = $customerModel->special_lock;
        
        foreach($orderProducts as $info){
            $product = Yii::$service->product->getByPrimaryKey($info['product_id']);

            if(!empty($product->special_price)){
                $special_num += 1;
            }
            
            if($special_num > $customer_level_info['special_num']){
                echo '<script>alert("特价商品只能同时租'.$customer_level_info['special_num'].'件，请修改");window.history.go(-2);</script>';
                exit;
            }

            if($info['qty'] > $customer_level_info['special_days'] && !empty($product->special_price)){
                echo '<script>alert("'.$info['name'].'为特价商品，根据您的用户级别，租借时间不能超过'.$customer_level_info['special_days'].'天，请修改此商品租借天数");window.history.go(-2);</script>';
                exit;
            }

            if($info['qty'] > $maxCountAddToCart){
                echo '<script>alert("商品目前最多只能租用'.$maxCountAddToCart.'天，请修改'.$info['name'].'的租用天数，请谅解");window.history.go(-2);</script>';
                exit;
            }
            //获取订单的商品总金额
            $primaryVal = $info['product_id'];
            $product = Yii::$service->product->getByPrimaryKey($primaryVal);
            $productPrice += $product['cost_price'];

        }

        //获取用户累计租聘金额
        if($customerModel){
            $t_price = $productPrice + $customerModel->summation_cost;

            if($t_price > $maxPriceAddToCart){
                $p = $maxPriceAddToCart-$customerModel->summation_cost;
                
                if($maxPriceAddToCart < 2000){
                    echo "<script>alert('你的会员等级对应租用的商品总金额不能超过".$maxPriceAddToCart."，还可以租".$p."以内的道具，如需提高租用商品金额，请到 我的账户 -> 账户信息 中充值押金。');window.history.go(-2);</script>";
                }else{
                    echo "<script>alert('你的会员等级对应租用的商品总金额不能超过".$maxPriceAddToCart."，还可以租".$p."以内的道具');window.history.go(-2);</script>";
                }
                    
                exit;
            }
        }

/*
        $productNum = count($orderInfo['products']);
        if($productNum >1){
            echo '<script>alert("下单失败！内测阶段,每人最多只可租借一件商品");window.history.go(-2);</script>';
            exit;
        }
  */      
    }
    
    public static function after($orderInfo){
        //$emailArr = ['617990822@qq.com', '2366629496@qq.com'];
        $emailArr = ['gprent@163.com', '2366629496@qq.com'];
        
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
