<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AccountController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
    }

        /**
         * 账户中心.
         */
        public function actionIndex()
        {
            if (Yii::$app->user->isGuest) {
                return Yii::$service->url->redirectByUrlKey('customer/account/login');
            }
            $data = $this->getBlock()->getLastData();

            return $this->render($this->action->id, $data);
        }

   /**
    * 登录.
    */
   public function actionLogin()
   {
       /*
        $toEmail = 'zqy234@126.com';
        // \fecshop\app\appfront\modules\Mailer\Email::sendLoginEmail($toEmail);
        \fecshop\app\appfront\modules\Mailer\Email::sendRegisterEmail($toEmail);
        exit;
        */
        $steam = Yii::$app->request->get('steam');

        if($steam == 1){
            $invite_code = Yii::$app->request->get('invite_code');
            
            //邀请码放在session里
            if($invite_code){
                Yii::$app->session['invite_code'] = $invite_code;
            }
            Yii::$service->customer->steam->login();
            /*
            $openid = new LightOpenID($_SERVER['SERVER_NAME']);
            
            if(!$openid->mode) {
                $openid->identity = 'http://steamcommunity.com/openid';
                header('Location: ' . $openid->authUrl());exit;
            }
            */
        }

        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
       $param = Yii::$app->request->post('editForm');
       if (!empty($param) && is_array($param)) {
           $this->getBlock()->login($param);
           if (!Yii::$app->user->isGuest) {
               return Yii::$service->customer->loginSuccessRedirect('customer/account');
           }
       }
       $data = $this->getBlock()->getLastData($param);

       return $this->render($this->action->id, $data);
   }
    /**
     * steam注册并登陆.
     */
    public function actionRegisterbysteam()
    {
        $session = Yii::$app->session;
        $steamid = $session['steamid'];
 
        //得到邀请码
        $invite_code = $session['invite_code'];
        
        $steamKey = Yii::$app->params['steam']['key'];
        $steamGetPlayerSummariesUrl = Yii::$app->params['steam']['GetPlayerSummariesUrl'];
        $url = $steamGetPlayerSummariesUrl.'?key='.$steamKey.'&steamids='.$steamid;
        $steamContents = file_get_contents($url);
        $steamResponse = json_decode($steamContents, true);
        
        $steamInfoArr = $steamResponse['response']['players'][0];
        $param['lastname']      = $steamInfoArr['personaname'];
        $param['email']         = $steamid.'@gprent.cn';
        $param['password']      = 'gprent';
        $param['confirmation']  = 'gprent';
        $param['steamid']       = $steamid;
        $param['steam_avatar']   = $steamInfoArr['avatarfull'];
        $param['parent_invite_code']   = $invite_code;
        
        //是否为绑定
        if (!Yii::$app->user->isGuest) {
            $isRegister = Yii::$service->customer->getUserIdentityBySteamid($steamid);
            if(!empty($isRegister)){
                echo '<script>alert("该账号已经被绑定，请直接用steam帐号登陆");window.location.href="/customer/account";</script>';
                exit;
            }else{
                $identity = Yii::$app->user->identity;
            
                $identity->steamid = $steamid;
                $identity->steam_avatar = $steamInfoArr['avatarfull'];
                $identity->lastname = $steamInfoArr['personaname'];
                $identity->save();
            }
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }

        //看是否已经注册过
        $isRegister = Yii::$service->customer->getUserIdentityBySteamid($steamid);
        if(!empty($isRegister)){
            $registerStatus = true;
        }else{
            //$registerStatus = $this->getBlock()->registerbysteam($param);
            $registerStatus = $this->getBlock()->register($param);
        }
        if ($registerStatus) {
            $params_register = Yii::$app->getModule('customer')->params['register'];
            // 注册成功后，是否自动登录
            if (isset($params_register['successAutoLogin']) && $params_register['successAutoLogin']) {
                Yii::$service->customer->login($param);
                $this->getBlock()->sendCoupon(Yii::$app->user->identity->id);
            }
            if (!Yii::$app->user->isGuest) {
                // 注册成功后，跳转的页面，如果值为false， 则不跳转。
                $urlKey = 'customer/account';
                if (isset($params_register['loginSuccessRedirectUrlKey']) && $params_register['loginSuccessRedirectUrlKey']) {
                    $urlKey = $params_register['loginSuccessRedirectUrlKey'];
                }
                return Yii::$service->customer->loginSuccessRedirect($urlKey);
            }
        }else{
            return Yii::$service->url->redirectByUrlKey('customer/account/login?invite_code='.$invite_code);
        }
    }


    /**
     * 注册.
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $param = \Yii::$service->helper->htmlEncode($param);
            $registerStatus = $this->getBlock()->register($param);
            //echo $registerStatus;exit;
            if ($registerStatus) {
                $params_register = Yii::$app->getModule('customer')->params['register'];
                // 注册成功后，是否自动登录
                if (isset($params_register['successAutoLogin']) && $params_register['successAutoLogin']) {
                    Yii::$service->customer->login($param);
                }
                if (!Yii::$app->user->isGuest) {
                    // 注册成功后，跳转的页面，如果值为false， 则不跳转。
                    $urlKey = 'customer/account';
                    if (isset($params_register['loginSuccessRedirectUrlKey']) && $params_register['loginSuccessRedirectUrlKey']) {
                        $urlKey = $params_register['loginSuccessRedirectUrlKey'];
                    }

                    return Yii::$service->customer->loginSuccessRedirect($urlKey);
                }
            }
        }
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }

    /**
     * 登出账户.
     */
    public function actionLogout()
    {
        $rt = Yii::$app->request->get('rt');
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            Yii::$service->cart->clearCart();
        }
        if ($rt) {
            $redirectUrl = base64_decode($rt);
            Yii::$service->url->redirect($redirectUrl);
        } else {
            Yii::$service->url->redirect(Yii::$service->url->HomeUrl());
        }
    }

    /**
     * ajax 请求 ，得到是否登录账户的信息.
     */
    public function actionLogininfo()
    {
        if (!Yii::$app->user->isGuest) {
            echo json_encode([
                'loginStatus' => true,
            ]);
            exit;
        }
    }

    /**
     * 忘记密码？
     */
    public function actionForgotpassword()
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account');
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionForgotpasswordsubmit()
    {
        $editForm = Yii::$app->request->post('editForm');
        $data = [
            'forgotPasswordUrl' => Yii::$service->url->getUrl('customer/account/forgotpassword'),
            'contactUrl'        => Yii::$service->url->getUrl('customer/contacts'),
        ];
        if (!empty($editForm)) {
            $identity = $this->getBlock('forgotpassword')->sendForgotPasswordMailer($editForm);
            //var_dump($identity);
            if ($identity) {
                $data['identity'] = $identity;
            } else {
                $redirectUrl = Yii::$service->url->getUrl('customer/account/forgotpassword');
                Yii::$service->url->redirect($redirectUrl);
            }
        }

        return $this->render($this->action->id, $data);
    }

    public function actionResetpassword()
    {
        $editForm = Yii::$app->request->post('editForm');
        if (!empty($editForm)) {
            $resetStatus = $this->getBlock()->resetPassword($editForm);
            if ($resetStatus) {
                // 重置成功，跳转
                $resetSuccessUrl = Yii::$service->url->getUrl('customer/account/resetpasswordsuccess');
                Yii::$service->url->redirect($resetSuccessUrl);
            }
        }
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionResetpasswordsuccess()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionFacebook()
    {
    }

    public function actionGoogle()
    {
    }
}
