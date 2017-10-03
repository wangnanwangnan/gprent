<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;

use fec\helpers\CUrl;
use Yii;
use fecshop\models\mysqldb\Order;
/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit
{
    public $_saveUrl;

    public function init()
    {
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $order_id = Yii::$app->request->get('order_id');
        //$order = Yii::$service->order->getByPrimaryKey($order_id);
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $order_info = $this->getViewOrderInfo($order_info);
        return [
            'order' => $order_info,
            //'editBar' 	=> $this->getEditBar(),
            //'textareas'	=> $this->_textareas,
            //'lang_attr'	=> $this->_lang_attr,
            'saveUrl' 	    => Yii::$service->url->getUrl('sales/orderinfo/managereditsave'),
        ];
    }

    //删除订单
    public function delete()
    {
        $order_ids = Yii::$app->request->get('order_ids');
        if($order_ids){
            $arr_id = explode(',',$order_ids);
            $order = new Order();
            foreach($arr_id as $order_id){
                $res = $order->updateAll(['is_delete' => 1],'order_id = :order_ids',[':order_ids' => $order_id]);
            }
            if($res){
            echo  json_encode([
                'statusCode'=>'200',
                'message'=>'remove data  success',
            ]);
            exit;
            }else{
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$res->errors,
            ]);
            exit;
            }
            exit;
        }
    }
    
    public function getViewOrderInfo($order_info){
        // 订单状态部分
        $orderStatusArr = Yii::$service->order->getStatusArr();
        //var_dump($orderStatusArr);exit;
        $order_info['order_status_options'] = $this->getOptions($orderStatusArr,$order_info['order_status']);
    
        // 货币部分
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currencyArr = [];
        if(is_array($currencys)){
            foreach( $currencys as $code => $v){
                $currencyArr[$code] = $code;
            }
        }
        $order_info['order_currency_code_options'] = $this->getOptions($currencyArr,$order_info['order_currency_code']);
        // 支付类型
        $checkTypeArr = Yii::$service->order->getCheckoutTypeArr();
        $order_info['checkout_method_options'] = $this->getOptions($checkTypeArr,$order_info['checkout_method']);
        // 游客下单
        $customerOrderArr = [ 1 => '是',2 => '否',];
        $order_info['customer_is_guest_options'] = $this->getOptions($customerOrderArr,$order_info['customer_is_guest']);
        // 省
        $order_info['customer_address_country_options'] = Yii::$service->helper->country->getCountryOptionsHtml($order_info['customer_address_country']);
        // 市
        $order_info['customer_address_state_options'] = Yii::$service->helper->country->getStateOptionsByContryCode($order_info['customer_address_country'],$order_info['customer_address_state']);
        
        return $order_info;
    }
    
    
    
    
    public function getOptions($orderStatusArr,$order_status){
        $str = '';
        if(is_array($orderStatusArr)){
            foreach($orderStatusArr as $k => $v){
                if($order_status == $k ){
                    $str .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
                }else{
                    $str .= '<option value="'.$k.'">'.$v.'</option>';
                
                }
            }
        }
        
        return $str;
    }
    
    public function save(){
        $editForm = Yii::$app->request->post('editForm');
        $order_id = $editForm['order_id'];
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);
        if(is_array($editForm) && $orderModel['order_id']){
            $order_start = $orderModel->order_status;
            foreach($editForm as $k => $v){
                if(isset($orderModel[$k])){
                    //判断是否修改订单状态 如果修改判断是否换回来物品 是 则将用户累计租赁金额还原
                    if($k == 'order_status' && $v == 'complete' && $order_start == 'holded'){
                        $order_info = Yii::$service->order->getOrderInfoById($order_id);
                        $customer_id = $order_info['customer_id'];
                        $total_cost_price = 0;
                        foreach($order_info['products'] as $pinfo){
                            $total_cost_price += $pinfo['cost_price'];
                        }
                        $customerModel = Yii::$service->customer->getByPrimaryKey($customer_id);
                        if($customerModel->summation_cost >= $total_cost_price){
                            $customerModel->summation_cost = $customerModel->summation_cost-$total_cost_price;
                            $customerModel->save();
                        }
                    }
                    $orderModel[$k] = $v;
                }
            } 
            $orderModel->save();
        }
        echo  json_encode([
            'statusCode'=>'200',
            'message'=>'save success',
        ]);
        exit;
    }
    
    
    
    
    
}
