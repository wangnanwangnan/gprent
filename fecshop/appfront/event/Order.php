<?php
namespace  appfront\event;

use Yii;

class Order
{
    public static function before($orderInfo){
        
        $productNum = count($orderInfo['products']);
        
        //print_r($orderInfo);exit;

        $special_sku_arr = ['10001, 10002, 10003, 10005, 10006'];
        foreach($orderInfo as $info){
            if($info['qty'] > 1 && in_array($info['sku'], $special_sku_arr)){
                echo '<script>alert("'.$info['name'].'为特价商品，租借时间不能超过一天，请修改");window.history.go(-2);</script>';
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
            $emailInfo['email'] = $email;
            Yii::$service->email->customer->sendLoginEmail($emailInfo);
            //$emailInfo['customer_email'] = $email;
            //Yii::$service->email->order->sendCreateEmail($emailInfo);
        }
    }
}
