<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * service mail ：邮件服务部分
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Sms extends Service
{
    
    protected $SMS_KEY = "s9pvvcg2ow7esP2XUL3QkdL16dNBGMrQ";
    protected $SMSAPIURL = "http://www.sendcloud.net/smsapi/send";

    const TEMPLATE_RENT_SUCCESS = 9477; 
    const TEMPLATE_WILL_BE_EXPIRED = 9476; 
    
    public function actionSendsms($params)
    {
            $param_str = "";
            $signature = $this->getSignature($params);
            $params["signature"] = $signature;

            foreach($params as $key => $val)
            {
                    $param_str.= $key . "=" . $params[$key] . "&";
            }

            $param_str = substr( $param_str, 0, strlen( $param_str ) - 1 );


            #Ezample : $cmd = "curl -d 'smsUser=gprent&phone=13810407216&templateId=9428&signature=6921562f9b0fa3db5ff4da322575b7be' http://www.sendcloud.net/smsapi/send";
            $cmd = sprintf("curl -d '%s' %s 2>&1",
                            $param_str,
                            $this->SMSAPIURL);

            exec($cmd, $msg);
            #print_r($msg);
    }

    public function getSignature($params)
    {
            $keys = array_keys($params);
            sort($keys);
            $param_str = "";

            foreach($keys as $key => $val)
            {
                    $param_str.= $val . "=" . $params[$val] . "&";
            }

            $param_str = substr( $param_str, 0, strlen( $param_str ) - 1 );

            $sign_str = sprintf("%s&%s&%s",
                            $this->SMS_KEY,
                            $param_str,
                            $this->SMS_KEY
            );
            $signature = md5($sign_str);
            return $signature;
    }

    

    /**
     * @property $phone | String  手机号
     * @return bool 如果格式正确，返回true
     */
    protected function validateFormat($phone)
    {
        if (preg_match("^1[3|4|5|7|8][0-9]{9}$", $email_address)) {
            return true;
        } else {
            return false;
        }
    }
}
