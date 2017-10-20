<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use Yii;
use fecshop\models\mysqldb\Order;
use fecshop\services\product\ProductMongodb;

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
        $this->_primaryKey = 'order_id';
        $order_ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $order_ids = (array)$id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $order_ids = explode(',', $ids);
        }
        //$order_ids = Yii::$app->request->get('order_ids');
        if($order_ids){
            //$arr_id = explode(',',$order_ids);
            $order = new Order();
            foreach($order_ids as $order_id){
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
        
        $itemInfo = $order_info['products'];
        foreach($itemInfo as $item){
            $item_id = $item['item_id'];
            
            $item['item_status'] = ($item['item_status']) ? $item['item_status'] : $order_info['order_status'];
            $order_info['item_status_options'][$item_id] = $this->getOptions($orderStatusArr, $item['item_status']);
        }
        
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
        $editItem = Yii::$app->request->post('editItem');
        $order_id = $editForm['order_id'];
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);

        $item_status_all = '';
        if($orderModel->order_status != $editForm['order_status']){
            $item_status_all = $editForm['order_status'];
            $orderModel->pay_updated_at = time();
        }
        
        $ProductMongodb = new ProductMongodb();
        if(is_array($editItem)){
            $cost_price = 0;
            foreach($editItem['item_status'] as $itemId => $itemVal){
                $itemModel = Yii::$service->order->item->getByPrimaryKey($itemId);
                $currentItemStatus = $itemModel->item_status;
                
                if($currentItemStatus == 'holded'){
                    if(($item_status_all && $item_status_all == 'complete') || ($itemVal == 'complete')){
                        $product_id = $itemModel->product_id;
                        $product_info = $ProductMongodb->getByPrimaryKey($product_id);
                        $cost_price += $product_info->cost_price;
                    }
                
                }elseif($currentItemStatus == 'processing'){
                    if($itemVal == 'holded'){
                    }
                }

                $itemModel->item_status = ($item_status_all) ? $item_status_all : $itemVal;
                $itemModel->save();
            }
            $order_info = Yii::$service->order->getOrderInfoById($order_id);
            $customer_id = $order_info['customer_id'];
            
            $customerModel = Yii::$service->customer->getByPrimaryKey($customer_id);
            if($customerModel->summation_cost >= $cost_price){
                $customerModel->summation_cost = $customerModel->summation_cost - $cost_price;
                $customerModel->save();
            }

        }
        
        if(is_array($editForm) && $orderModel['order_id']){
            $order_start = $orderModel->order_status;
            foreach($editForm as $k => $v){
                if(isset($orderModel[$k])){
                    //判断是否修改订单状态 如果修改判断是否换回来物品 是 则将用户累计租赁金额还原
                    /*
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
                    */
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
    
    // 批量删除
    /*
    public function delete()
    {
        $this->_primaryKey = 'order_id';
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }

        $this->_service = Yii::$service->order;
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode'=>'200',
                'message'=>'remove data  success',
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }
    */
}
