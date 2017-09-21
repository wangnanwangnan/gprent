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
    public function getLastData()
    {
        $increment_id = Yii::$service->order->getSessionIncrementId();
        if (!$increment_id) {
            Yii::$service->url->redirectHome();
        }
        $order = Yii::$service->order->getInfoByIncrementId($increment_id);
        $this->sendRentUserMail($order);

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
