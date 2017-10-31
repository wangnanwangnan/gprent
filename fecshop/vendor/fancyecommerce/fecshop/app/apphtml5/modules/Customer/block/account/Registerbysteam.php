<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\block\account;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Registerbysteam
{
    protected $_couponMemberModelName = '\fecshop\models\mysqldb\customer\Coupon';
    public function getLastData($param)
    {
        $firstname = isset($param['firstname']) ? $param['firstname'] : '';
        $lastname = isset($param['lastname']) ? $param['lastname'] : '';
        $email = isset($param['email']) ? $param['email'] : '';
        $registerParam = \Yii::$app->getModule('customer')->params['register'];
        $registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;

        return [
            'firstname'        => $firstname,
            'lastname'        => $lastname,
            'email'            => $email,
            'is_subscribed'    => $is_subscribed,
            'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
            'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
            'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
            'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
            'registerPageCaptcha' => $registerPageCaptcha,
        ];
    }
    
    public function registerBySteam($param)
    {
        //$password = $this->randStr(6,'CHAR');
        //$param['invite_code'] = $password;
        Yii::$service->customer->register($param);
        $errors = Yii::$service->page->message->addByHelperErrors();
        if (!$errors) {
            // 发送注册邮件
            //$this->sendRegisterEmail($param);

            return true;
        }
    }

    public function register($param)
    {
        $captcha = $param['captcha'];
        $registerParam = \Yii::$app->getModule('customer')->params['register'];
        $registerPageCaptcha = isset($registerParam['registerPageCaptcha']) ? $registerParam['registerPageCaptcha'] : false;
        // 如果开启了验证码，但是验证码验证不正确就报错返回。
        if ($registerPageCaptcha && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $registerPageCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        $password = $this->randStr(6,'CHAR');
        $param['invite_code'] = $password;
        Yii::$service->customer->register($param);
        $errors = Yii::$service->page->message->addByHelperErrors();
        if (!$errors) {
            // 发送注册邮件
            $this->sendRegisterEmail($param);

            return true;
        }
    }

    /**
      *生成优惠券
      */
    public function sendCoupon($customer_id)
    {
        $couponMemberModelName = new $this->_couponMemberModelName;
        $newly_coupon_config = Yii::$app->params['newly_coupon_config'];
        $coupon_code = $this->randStr(6,'CHAR');
        $coupon['coupon_code'] = $coupon_code;
        $coupon['created_person'] = $customer_id;
        $coupon['users_per_customer'] = 1;
        $coupon['type'] = $newly_coupon_config['type'];
        $coupon['conditions'] = $newly_coupon_config['conditions'];
        $coupon['discount'] = $newly_coupon_config['discount'];
        $expiration_date = $newly_coupon_config['expiration_date'];
        $coupon['expiration_date'] = strtotime("+$expiration_date day");
        $one = Yii::$service->cart->coupon->save($coupon);
        if($one){
            if($customer_id){
                $couponMemberModelName['customer_id'] = $customer_id;
                $couponMemberModelName['coupon'] = $coupon_code;
                $couponMemberModelName['coupon_id'] = $one;
                $couponMemberModelName['expiration_date'] = strtotime("+$expiration_date day");
                $couponMemberModelName['add_time'] = date('Y-m-d H:i:s',time());
                $couponMemberModelName['coupon_msg'] = "新注册用户 获取".$newly_coupon_config['discount']."元优惠券 满".$newly_coupon_config['conditions']."元可用";
                $couponMemberModelName->save();
                return true;
            }
        }

    
    }

    /**
     * 发送登录邮件.
     */
    public function sendRegisterEmail($param)
    {
        if ($param) {
            //Email::sendRegisterEmail($param);
            //Yii::$service->email->customer->sendRegisterEmail($param);
        }
    }
    /**
    * 生成邀请码
    */
    public function randStr($len = 6, $format = 'ALL') {
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        mt_srand((double) microtime() * 1000000 * getmypid());
        $password = "";
        while (strlen($password) < $len) {
            $password.=substr($chars, (mt_rand() % strlen($chars)), 1);
        }
        return $password;
    }
}
