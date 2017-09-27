<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Payment\controllers\alipay;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StandardController extends AppfrontController
{
    
    public $enableCsrfValidation = false;
    /**
     * 在网站下单页面，选择支付宝支付方式后，
     * 跳转到支付宝支付页面前准备的部分。
     */
    public function actionStart()
    {
        //$AopSdkFile = Yii::getAlias('@fecshop/lib/alipay/AopSdk.php');
        //require($AopSdkFile);
        echo '支付宝支付跳转中...';
        return Yii::$service->payment->alipay->start();
        
    }
    /**
     * 从支付宝支付成功后，跳转返回 fec-shop 的部分
     */
    public function actionReview()
    {
        $reviewStatus = Yii::$service->payment->alipay->review();
        if($reviewStatus){
            $successRedirectUrl = Yii::$service->payment->getStandardSuccessRedirectUrl();
         /*
            //邮件通知
            $emailArr = ['617990822@qq.com', '2366629496@qq.com'];
  
            foreach($emailArr as $email){
               $sendInfo = [
                   'to'        => $email,
                   'subject'    => '有人已经付款完成！',
                   'htmlBody'    => '有人已经付款完成',
                   'senderName'=> Yii::$service->store->currentStore,
               ];
               Yii::$service->email->send($sendInfo, 'default');
                    
                //$emailInfo['email'] = $email;
                //Yii::$service->email->customer->sendLoginEmail($emailInfo);
                //$emailInfo['customer_email'] = $email;
                //Yii::$service->email->order->sendCreateEmail($emailInfo);
            }
           */ 
            return Yii::$service->url->redirect($successRedirectUrl);
        }else{
            echo Yii::$service->helper->errors->get('<br/>');
            return;
        }
    }
    /**
     * IPN，支付宝消息接收部分
     */
    public function actionIpn()
    {
        \Yii::info('alipay ipn begin', 'fecshop_debug');
       
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            \Yii::info('', 'fecshop_debug');
            $post = \Yii::$service->helper->htmlEncode($post);
            ob_start();
            ob_implicit_flush(false);
            var_dump($post);
            $post_log = ob_get_clean();
            \Yii::info($post_log, 'fecshop_debug');
            $ipnStatus = Yii::$service->payment->alipay->receiveIpn($post);
            if($ipnStatus){
                echo 'success';
                return;
            }
        }
    }
    
    /*
    public function actionCancel()
    {
        $innerTransaction = Yii::$app->db->beginTransaction();
		try {
            if(Yii::$service->order->cancel()){
                $innerTransaction->commit();
            }else{
                $innerTransaction->rollBack();
            }
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
        return Yii::$service->url->redirectByUrlKey('checkout/onepage');
    }
    */
    
}
