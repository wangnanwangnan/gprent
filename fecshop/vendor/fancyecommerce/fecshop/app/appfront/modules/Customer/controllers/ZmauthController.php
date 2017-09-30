<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

use Greedying\Zhima\Foundation\Application;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ZmauthController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';
    public $zhimaObj;

    public function init()
    {
        parent::init();
        
        $options = [
            'app_id'    => '1004294',
            //'scene'     => 'yourscene',
            'private_key_file' => "/var/www/rent/fecshop/key/rsa_private_key.pem",
            'zhima_public_key_file' => "/var/www/rent/fecshop/key/rsa_public_key.pem",
        ];
        $this->zhimaObj = new Application($options);
    }
    
    public function actionExist()
    {
        $realname = Yii::$app->request->post('zm_realname');
        $identity_card = Yii::$app->request->post('zm_identity_card');
        
        //搜索是否该用户已经被验证
        $db = \Yii::$app->db;
        $sql = "select id from customer where identity_card = '".$identity_card."' and zm_scroe != 0 limit 1 ";
        $data = $db->createCommand($sql,[])->queryOne();
        
        $is_exist = 0;
        if(!empty($data)){
            $is_exist = 1;
        }
        echo $is_exist;
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $realname = Yii::$app->request->post('zm_realname');
        $identity_card = Yii::$app->request->post('zm_identity_card');
        
        //搜索是否该用户已经被验证
        $db = \Yii::$app->db;
        $sql = "select id from customer where identity_card = '".$identity_card."' and zm_scroe != 0 limit 1 ";
        $data = $db->createCommand($sql,[])->queryOne();
        

        $identity = Yii::$app->user->identity;
        $identity->realname = $realname;
        $identity->identity_card = $identity_card;
        $identity->save();
        
        $auth = $this->zhimaObj->auth;
        $auth->identity_type = '2';
        $auth->identity_param = json_encode([
            'certNo'    => $identity_card,
            'certType'  => 'IDENTITY_CARD',
            'name'      => $realname,
        ]);
        $auth->state = ''; //自定义字符串
        $url = $auth->getPcUrl();

        $this->redirect($url);
    }

    public function actionCallback()
    {
        $params = Yii::$app->request->get('params');
        $sign = Yii::$app->request->get('sign');
        
        // 判断串中是否有%，有则需要decode
        $params = strstr ( $params, '%' ) ? urldecode ( $params ) : $params;
        $sign = strstr ( $sign, '%' ) ? urldecode ( $sign ) : $sign;
        
        $auth = $this->zhimaObj->auth;
        $auth->handleNotify(function ($notify, $successful) {

            $transaction_id = date('YmdHis').$this->getMillisecond();
            $score = $this->zhimaObj->score->score($notify->open_id, $transaction_id);
            //print_r($score); exit;
            $identity = Yii::$app->user->identity;
            $identity->zm_scroe = $score;
            $identity->zm_transaction_id = $transaction_id;
        
            $customer_level = 0;
            $zmScore = Yii::$app->params['zmScore'];
            if($score >= $zmScore){
                $customer_level = 1;
            }
            $identity->level = $customer_level;

            if($identity->save()){
                $this->redirect('/customer/editaccount');
            }
        });  
    }

    private function getMillisecond() { 
        list($s1, $s2) = explode(' ', microtime()); 
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000); 
    }

    public function actionGetzmstatus(){
        $identity = Yii::$app->user->identity;
        
        $zmScoreLow = Yii::$app->params['zmScoreLow'];
        $errcode = 0;
        if(empty($identity['zm_scroe'])){
            $errcode = 1;
        }elseif($identity['zm_scroe'] < $zmScoreLow){
            $errcode = 2;
        }
        echo $errcode;
    }
}
