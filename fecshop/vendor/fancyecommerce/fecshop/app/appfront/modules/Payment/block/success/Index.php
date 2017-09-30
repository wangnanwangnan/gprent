<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\block\success;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $_customerMemberModelName = '\fecshop\models\mysqldb\customer\Member';
    
    public function getLastData()
    {
        $increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$increment_id) {
            Yii::$service->url->redirectHome();
        }
        $order = Yii::$service->order->getInfoByIncrementId($increment_id);
        
        //如果订单为会员卡押金
        if($order['is_membercard'] == 1){
            $this->insertCustomerMember($order);
        }

        //支付成功通知
        $this->noticePaySuccess($order);
        //$this->sendRentUserMail($order);

        //更新用户租借额度 特价商品数量
        $this->updateUserCost($order);
        //$custoner_id = $order['customer_id'];
        //$customerModel = $this->_customerModel->findIdentity($customer_id);
        //$customerModel->summation_cost = ;

        // 清空购物车。这里针对的是未登录用户进行购物车清空。
        if (Yii::$app->user->isGuest) {
            Yii::$service->cart->clearCartProductAndCoupon();
        }
        // 清空session中存储的当前订单编号。
        Yii::$service->order->removeSessionIncrementId();

        return [
            'increment_id' => $increment_id,
            'order'            => $order,
        ];
    }

    public function insertCustomerMember($order){
        //如果为会员卡押金
        $customerMemberModel = new $this->_customerMemberModelName();
        $customerMemberModel['customer_id'] = Yii::$app->user->identity->id;
        $customerMemberModel['level']       = 1;
        $customerMemberModel['order_id']    = $order['order_id'];
        
        $customerMemberModel->save();
    }

    public function updateUserCost($order){
        $customer_id = $order['customer_id'];
        //$order_id = $order['order_id'];
        $customerModel = Yii::$service->customer->getByPrimaryKey($customer_id);
        //获取订单下所有的商品价格
        //$order_info = Yii::$service->order->getOrderInfoById($order_id);
        $total_cost_price = 0;
        $special_lock = $customerModel->special_lock;
        if($order){
            foreach($order['items'] as $pinfo){
                $total_cost_price += $pinfo['cost_price'];
                $product = Yii::$service->product->getByPrimaryKey($pinfo['product_id']);
                if(!empty($product->special_price)){
                    $special_lock += 1;
                }
            }
        }
        $customerModel->special_lock = $special_lock;
        $customerModel->summation_cost = $customerModel->summation_cost+$total_cost_price;
        $customerModel->save();

    }

    public function noticePaySuccess($order){
        $custoner_id = $order['customer_id'];
        $customer = Yii::$service->customer->getByPrimaryKey($custoner_id);
        
        $emailArr = ['617990822@qq.com', '2366629496@qq.com', '15632055895@163.com'];
        foreach($emailArr as $email){
        
            $htmlBody = '用户'.$customer['realname'].'刚刚订单支付成功，总价为'.$order['grand_total'].'，竟快发货';
            $sendInfo = [
                        'to'            => $email,
                        'subject'       => '有人已经付款完成！',
                        'htmlBody'      => $htmlBody,
                        'senderName'    => Yii::$service->store->currentStore,
                    ];
            Yii::$service->email->send($sendInfo, 'default');
        }
    }

    public function sendRentUserMail($order){
        $custoner_id = $order['customer_id'];
        $customer = Yii::$service->customer->getByPrimaryKey($custoner_id);
        
        $parent_invite_code = $customer->parent_invite_code;
        $parent_invite_code = 'qwerty';
        if(!empty($parent_invite_code)){
            $parentCustomer = Yii::$service->customer->getUserIdentityByInviteCode($parent_invite_code);           
            $parentCustomerEmail = $parentCustomer->email;
            $parentCustomerEmail = '617990822@qq.com';
            $htmlBody = '你邀请的用户'.$customer['realname'].'刚刚下了订单，总价为'.$order['grand_total'].'，您将得到订单总金额的20%佣金返还给您。';
            $sendInfo = [
                        'to'            => $parentCustomerEmail,
                        'subject'       => '你邀请的用户已经租用物品了呦，你将会得到20%的佣金！',
                        'htmlBody'      => $htmlBody,
                        'senderName'    => Yii::$service->store->currentStore,
                    ];
            Yii::$service->email->send($sendInfo, 'default');
            exit;
        }
    }
}
