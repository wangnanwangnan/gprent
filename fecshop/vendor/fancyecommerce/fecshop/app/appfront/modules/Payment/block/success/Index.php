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
    protected $_couponMemberModelName = '\fecshop\models\mysqldb\customer\Coupon';
    
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
        //佣金
        //$this->sendRentUserMail($order);
        //优惠券
        $this->sendCouponUserMail($order);
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
        $coupon_code = $order['coupon_code'];
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
            $customerModel->special_lock = $special_lock;
            $customerModel->summation_cost = $customerModel->summation_cost+$total_cost_price;
            $customerModel->save();
        }

        //判断是否使用优惠券 如果使用判断是不是邀请好友得来的 如果是则更新成已使用状态
        $couponMemberModelName = new $this->_couponMemberModelName;
        $coupon_info = $couponMemberModelName->find()->where(['coupon' => $coupon_code,'customer_id' => $customer_id,'status' => 0])->one();
        if($coupon_info){
            $coupon_info->status = 1;
            $coupon_info->save();
        }

    }

    public function noticePaySuccess($order){
        $custoner_id = $order['customer_id'];
        $customer = Yii::$service->customer->getByPrimaryKey($custoner_id);
        
        //$emailArr = ['617990822@qq.com', '2366629496@qq.com', '15632055895@163.com'];
        $emailArr = ['gprent@163.com', '2366629496@qq.com'];
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
        }
    }
    public function getRandomString($len)
    {
        $chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXY3456789";
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];  
        }
        return $str;
    }
    //发送优惠券
    public function sendCouponUserMail($order){
        $customer_id = $order['customer_id'];
        $customer = Yii::$service->customer->getByPrimaryKey($customer_id);
        $parent_invite_code = $customer->parent_invite_code;
        //$parent_invite_code = 'qwerty';
        $couponMemberModelName = new $this->_couponMemberModelName;
        if(!empty($parent_invite_code)){
            //判断是否已经有过订单了 第一次下单生成优惠券
            $orderInfo = Yii::$service->order->getFristOrder($customer_id);
            $orderNum = count($orderInfo);
            if($orderNum == 1){
                //获取优惠券规则
                $invite_coupon_config = Yii::$app->params['invite_coupon_config'];
                //生成优惠券
                $coupon = [];
                $coupon_code = $this->getRandomString(6);
                $coupon['coupon_code'] = $coupon_code;
                $coupon['users_per_customer'] = 1;
                $coupon['type'] = $invite_coupon_config['type'];
                $coupon['conditions'] = $invite_coupon_config['conditions'];
                $coupon['discount'] = $invite_coupon_config['discount'];
                $expiration_date = $invite_coupon_config['expiration_date'];
                $coupon['expiration_date'] = strtotime("+$expiration_date day");
                $one = Yii::$service->cart->coupon->save($coupon);
                if($one){
                    $parentCustomer = Yii::$service->customer->getUserIdentityByInviteCode($parent_invite_code);
                    if($parentCustomer){
                        $couponMemberModelName['customer_id'] = $parentCustomer->id;
                        $couponMemberModelName['coupon'] = $coupon_code;
                        $couponMemberModelName['coupon_id'] = $one;
                        $couponMemberModelName['expiration_date'] = strtotime("+$expiration_date day");
                        $couponMemberModelName['add_time'] = date('Y-m-d H:i:s',time());
                        $discount_arr = ['30' => 7,'40' => 6,'50' => 5,'60' => 4,'70' => 3,'80' => 2];
                        $discount = $discount_arr[$invite_coupon_config['discount']];
                        $couponMemberModelName['coupon_msg'] = '邀请好友'.$customer->lastname." 获取".$discount."折优惠券 满".$invite_coupon_config['conditions']."元可用";
                        $couponMemberModelName->save();
                    }
                    /*
                    $parentCustomerEmail = $parentCustomer->email;
                    $htmlBody = '你邀请的用户'.$customer['realname'].'刚刚下了订单，Gprent赠送一张优惠券'.$coupon_code.'(六折优惠券 满5元可用)  有效期7天 赶快去享受去吧。。。';
                    $sendInfo = [
                                'to'            => $parentCustomerEmail,
                                'subject'       => '你邀请的用户已经租用物品了呦，Gprent赠送一张优惠券，请注意查收！',
                                'htmlBody'      => $htmlBody,
                                'senderName'    => Yii::$service->store->currentStore,
                            ];
                    Yii::$service->email->send($sendInfo, 'default');
                    */
                }
            }
        }
    }
}
