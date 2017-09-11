<?php
namespace  appfront\event;

class Order
{
    public static function before($orderInfo){
        /*
        $productNum = count($orderInfo['products']);
        if($productNum >2){
            echo '<script>alert("下单失败！每人最多只可租用两件商品");window.history.go(-2);</script>';
            exit;
        }
        */


        $json = json_encode($orderInfo);
        file_put_contents('/tmp/aaa1', '|'.$json);
    }
}
